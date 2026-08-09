<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\GymPlan;
use App\Services\BookingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    private BookingService $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    /**
     * POST /api/bookings/create-order
     *
     * Creates a Razorpay order and a pending booking.
     * FE uses the returned order_id to open Razorpay checkout.
     *
     * Body: { plan_id }
     */
    public function createOrder(Request $request): JsonResponse
    {
        $request->validate([
            'plan_id' => ['required', 'integer', 'exists:gym_plans,id'],
        ]);

        $plan = GymPlan::with('gym')->find($request->plan_id);

        // Guard: plan must be enabled
        if (!$plan->is_enabled) {
            return ApiResponse::badRequest(
                'plan_unavailable',
                'This plan is currently unavailable.'
            );
        }

        // Guard: gym must be active
        if ($plan->gym->status !== 'active') {
            return ApiResponse::badRequest(
                'gym_unavailable',
                'This gym is currently not accepting bookings.'
            );
        }

        try {
            $order = $this->bookingService->createOrder($request->user(), $plan);
        } catch (\Exception $e) {
            report($e);
            return ApiResponse::serverError('Failed to create order. Please try again.');
        }

        return ApiResponse::created(
            'order_created',
            'Order created. Proceed to payment.',
            $order,
        );
    }

    /**
     * POST /api/bookings/verify-payment
     *
     * Called after Razorpay checkout succeeds.
     * Verifies signature, activates booking, creates settlement.
     *
     * Body: { booking_id, razorpay_order_id, razorpay_payment_id, razorpay_signature }
     */
    public function verifyPayment(Request $request): JsonResponse
    {
        $request->validate([
            'booking_id'           => ['required', 'integer', 'exists:bookings,id'],
            'razorpay_order_id'    => ['required', 'string'],
            'razorpay_payment_id'  => ['required', 'string'],
            'razorpay_signature'   => ['required', 'string'],
        ]);

        try {
            $booking = $this->bookingService->verifyAndActivate(
                $request->booking_id,
                $request->razorpay_order_id,
                $request->razorpay_payment_id,
                $request->razorpay_signature
            );
        } catch (\RuntimeException $e) {
            return ApiResponse::badRequest('payment_failed', $e->getMessage());
        } catch (\Exception $e) {
            report($e);
            return ApiResponse::serverError('Payment verification failed. Contact support.');
        }

        return ApiResponse::ok(
            'booking_confirmed',
            'Booking confirmed! Your QR pass is ready.',
            [
                'booking' => [
                    'id'             => $booking->id,
                    'booking_ref'    => $booking->booking_ref,
                    'qr_code'        => $booking->qr_code,
                    'gym_name'       => $booking->gym->name,
                    'gym_address'    => $booking->gym->address_text,
                    'gym_city'       => $booking->gym->city,
                    'gym_area'       => $booking->gym->area,
                    'plan_name'      => $booking->plan->name,
                    'start_date'     => $booking->start_date->format('d M Y'),
                    'end_date'       => $booking->end_date->format('d M Y'),
                    'amount'         => $booking->amount,
                    'status'         => $booking->status,
                    'traveler_name'  => $booking->user->name,
                    'traveler_phone' => $booking->user->phone,
                ],
                'settlement' => [
                    'gross_amount'      => $booking->settlement->gross_amount,
                    'commission_amount' => $booking->settlement->commission_amount,
                    'payout_amount'     => $booking->settlement->payout_amount,
                    'gym_upi_id'        => $booking->settlement->gym_upi_id,
                    'payout_status'     => $booking->settlement->payout_status,
                ],
            ],
        );
    }

    /**
     * GET /api/bookings/{id}
     *
     * Get booking details + QR code for the traveler.
     * Used to re-fetch the QR pass from the app.
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $booking = $request->user()
            ->bookings()
            ->with(['gym', 'plan'])
            ->find($id);

        if (!$booking) {
            return ApiResponse::badRequest('booking_not_found', 'Booking not found.');
        }

        return ApiResponse::ok('booking_fetched', 'Booking details fetched.', [
            'booking' => [
                'id'          => $booking->id,
                'booking_ref' => $booking->booking_ref,
                'qr_code'     => $booking->qr_code,
                'gym_name'    => $booking->gym->name,
                'gym_address' => $booking->gym->address_text,
                'plan_name'   => $booking->plan->name,
                'start_date'  => $booking->start_date->format('d M Y'),
                'end_date'    => $booking->end_date->format('d M Y'),
                'amount'      => $booking->amount,
                'status'      => $booking->status,
                'is_active'   => $booking->isActive(),
            ],
        ]);
    }

    /**
     * GET /api/bookings
     *
     * List all bookings for the logged-in traveler.
     */
    public function index(Request $request): JsonResponse
    {
        $bookings = $request->user()
            ->bookings()
            ->with(['gym', 'plan'])
            ->latest()
            ->get()
            ->map(fn($b) => [
                'id'          => $b->id,
                'booking_ref' => $b->booking_ref,
                'qr_code'     => $b->qr_code,
                'gym_name'    => $b->gym->name,
                'gym_city'    => $b->gym->city,
                'plan_name'   => $b->plan->name,
                'start_date'  => $b->start_date->format('d M Y'),
                'end_date'    => $b->end_date->format('d M Y'),
                'amount'      => $b->amount,
                'status'      => $b->status,
                'is_active'   => $b->isActive(),
            ]);

        return ApiResponse::ok('bookings_fetched', 'Bookings fetched.', [
            'bookings' => $bookings,
        ]);
    }
}