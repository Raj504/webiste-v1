<?php

namespace App\Services;

use App\Mail\InvoiceMail;
use App\Models\Booking;
use App\Models\Gym;
use App\Models\GymMember;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;

class InvoiceService
{
    /**
     * Issue a receipt for a paid day-pass booking. Only ever called after
     * Razorpay activation has succeeded, so payment_status is always 'paid'.
     */
    public function issueForBooking(Booking $booking): Invoice
    {
        $gym    = $booking->gym;
        $member = GymMember::where('gym_id', $booking->gym_id)
            ->where('phone', $booking->user->phone)
            ->first();

        $invoice = $this->create([
            'gym_id'               => $gym->id,
            'gym_member_id'        => $member->id ?? null,
            'booking_id'           => $booking->id,
            'source'               => 'booking',
            'gym_name_snapshot'    => $gym->name,
            'gym_address_snapshot' => $gym->address_text,
            'member_name'          => $booking->user->name,
            'member_phone'         => $booking->user->phone,
            'member_email'         => $booking->user->email,
            'plan_label'           => $booking->plan->name,
            'amount'               => $booking->amount,
            'payment_status'       => 'paid',
            'issued_date'          => today(),
        ]);

        $this->sendInvoiceEmail($invoice);

        return $invoice;
    }

    /**
     * Issue a receipt for a manually-added gym member. There's no platform
     * payment gateway involved here — amount and payment_status are whatever
     * the owner tells us, never assumed.
     */
    public function issueForGymMember(Gym $gym, GymMember $member, ?int $amount, string $paymentStatus = 'unpaid'): Invoice
    {
        $invoice = $this->create([
            'gym_id'               => $gym->id,
            'gym_member_id'        => $member->id,
            'booking_id'           => null,
            'source'               => 'manual',
            'gym_name_snapshot'    => $gym->name,
            'gym_address_snapshot' => $gym->address_text,
            'member_name'          => $member->name,
            'member_phone'         => $member->phone,
            'member_email'         => $member->email,
            'plan_label'           => $member->plan_label,
            'amount'               => $amount,
            'payment_status'       => $paymentStatus,
            'issued_date'          => today(),
        ]);

        $this->sendInvoiceEmail($invoice);

        return $invoice;
    }

    public function renderPdf(Invoice $invoice): string
    {
        return Pdf::loadView('invoices.pdf', ['invoice' => $invoice])->output();
    }

    // ── Private ───────────────────────────────────────────────────────────────

    private function create(array $attributes): Invoice
    {
        $invoice = Invoice::create($attributes);
        $invoice->update(['invoice_number' => sprintf('INV-%06d', $invoice->id)]);

        return $invoice;
    }

    private function sendInvoiceEmail(Invoice $invoice): void
    {
        if (!$invoice->member_email) {
            return;
        }

        try {
            $pdf = $this->renderPdf($invoice);
            Mail::to($invoice->member_email)->send(new InvoiceMail($invoice, $pdf));
        } catch (\Exception $e) {
            report($e);
        }
    }
}
