<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\GymPlan;
use App\Models\Settlement;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingService
{
    private RazorpayService $razorpay;

    public function __construct(RazorpayService $razorpay)
    {
        $this->razorpay = $razorpay;
    }

    /**
     * Step 1 — Create a Razorpay order.
     * Called when user clicks "Book Now".
     *
     * Returns the order details needed by the FE to open Razorpay checkout.
     */
    public function createOrder(User $user, GymPlan $plan): array
    {
        // Create pending booking first
        $booking = Booking::create([
            'user_id'    => $user->id,
            'gym_id'     => $plan->gym_id,
            'gym_plan_id'=> $plan->id,
            'booking_ref'=> $this->generateRef(),
            'start_date' => today(),
            'end_date'   => $this->calculateEndDate($plan),
            'amount'     => $plan->price,
            'status'     => 'pending',
        ]);

        // Create Razorpay order
        $order = $this->razorpay->createOrder(
            $plan->price,
            $booking->booking_ref,
            [
                'booking_id' => $booking->id,
                'gym_id'     => $plan->gym_id,
                'plan'       => $plan->name,
                'traveler'   => $user->name,
            ],
        );

        // Store order ID on booking
        $booking->update(['razorpay_order_id' => $order['id']]);

        return [
            'booking_id'       => $booking->id,
            'booking_ref'      => $booking->booking_ref,
            'razorpay_order_id'=> $order['id'],
            'amount'           => $plan->price,
            'amount_paise'     => $plan->price * 100,
            'currency'         => 'INR',
            'key_id'           => config('services.razorpay.key_id'),
            'gym_name'         => $plan->gym->name,
            'plan_name'        => $plan->name,
        ];
    }

    /**
     * Step 2 — Verify payment and activate booking.
     * Called after Razorpay checkout succeeds.
     *
     * Verifies signature → marks booking paid → creates settlement record → issues QR code.
     */
    public function verifyAndActivate(int $bookingId, string $razorpayOrderId, string $razorpayPaymentId, string $razorpaySignature): Booking
    {
        $booking = Booking::with(['plan.gym'])->findOrFail($bookingId);

        // Guard: already paid
        if ($booking->status === 'paid') {
            return $booking;
        }

        // Guard: order ID mismatch
        if ($booking->razorpay_order_id !== $razorpayOrderId) {
            throw new \RuntimeException('Order ID mismatch.');
        }

        // Verify Razorpay signature
        $valid = $this->razorpay->verifySignature(
            $razorpayOrderId,
            $razorpayPaymentId,
            $razorpaySignature,
        );

        if (!$valid) {
            throw new \RuntimeException('Payment signature verification failed.');
        }

        return $this->activate($booking, $razorpayPaymentId, $razorpaySignature);
    }

    /**
     * Activate a booking from a Razorpay webhook event (e.g. payment.captured).
     *
     * Used as a backend safety net: if the traveler's browser never completes the
     * verify-payment call (closed tab, dropped connection) after Razorpay has
     * actually captured the money, the webhook still gets the booking activated.
     *
     * The webhook layer (RazorpayWebhookController) verifies the request's
     * authenticity itself, so no checkout-style order/payment signature is
     * needed here — just idempotency against a booking that's already paid.
     */
    public function activateFromWebhook(string $razorpayOrderId, string $razorpayPaymentId): ?Booking
    {
        $booking = Booking::with(['plan.gym'])
            ->where('razorpay_order_id', $razorpayOrderId)
            ->first();

        if (!$booking || $booking->status === 'paid') {
            return $booking;
        }

        return $this->activate($booking, $razorpayPaymentId, null);
    }

    // ── Private helpers ───────────────────────────────────────────────────────

    /**
     * Shared activation step: mark booking paid, issue QR code, create settlement.
     * Called only after the caller has already verified the payment is genuine
     * (either via checkout signature, or via webhook signature).
     */
    private function activate(Booking $booking, string $razorpayPaymentId, ?string $razorpaySignature): Booking
    {
        DB::transaction(function () use ($booking, $razorpayPaymentId, $razorpaySignature) {
            $gym  = $booking->plan->gym;
            $split = $this->razorpay->calculateSplit($booking->amount);

            // Mark booking as paid
            $booking->update([
                'razorpay_payment_id' => $razorpayPaymentId,
                'razorpay_signature'  => $razorpaySignature,
                'status'              => 'paid',
                'qr_code'             => $this->generateQrCode($booking),
            ]);

            // Create settlement record
            Settlement::create([
                'booking_id'        => $booking->id,
                'gym_id'            => $booking->gym_id,
                'gross_amount'      => $split['gross'],
                'commission_pct'    => $split['commission_pct'],
                'commission_amount' => $split['commission_amount'],
                'payout_amount'     => $split['payout_amount'],
                'gym_upi_id'        => $gym->upi_id ?? '',
                'payout_status'     => 'pending',
            ]);
        });

        // TODO: trigger SMS / WhatsApp / email booking-confirmation notification to the traveler here (not built yet — planned for later).

        return $booking->fresh(['plan', 'gym', 'settlement', 'user']);
    }

    private function generateRef(): string
    {
        do {
            $ref = 'GP-' . date('Y') . '-' . strtoupper(Str::random(6));
        } while (Booking::where('booking_ref', $ref)->exists());

        return $ref;
    }

    private function generateQrCode(Booking $booking): string
    {
        // Unique token used as QR payload — gym scans this
        return 'GP-QR-' . $booking->id . '-' . Str::random(16);
    }

    private function calculateEndDate(GymPlan $plan): \Carbon\Carbon
    {
        switch ($plan->unit) {
            case 'day':
                return today()->addDays($plan->duration - 1);
    
            case 'month':
                return today()->addMonths($plan->duration)->subDay();   
    
            default:
                return today();
        }
    }
}