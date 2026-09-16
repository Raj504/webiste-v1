<?php

namespace App\Mail;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public Invoice $invoice;
    private string $pdfBytes;

    public function __construct(Invoice $invoice, string $pdfBytes)
    {
        $this->invoice  = $invoice;
        $this->pdfBytes = $pdfBytes;
    }

    public function build()
    {
        return $this->subject('Your receipt from ' . $this->invoice->gym_name_snapshot)
            ->view('emails.invoice-notification')
            ->attachData($this->pdfBytes, $this->invoice->invoice_number . '.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}
