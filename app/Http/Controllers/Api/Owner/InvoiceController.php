<?php

namespace App\Http\Controllers\Api\Owner;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Services\InvoiceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InvoiceController extends Controller
{
    /**
     * GET /api/owner/gym/invoices
     */
    public function index(Request $request): JsonResponse
    {
        $gym = $request->user()->gym;
        if (!$gym) {
            return ApiResponse::badRequest('gym_not_found', 'Gym not found.');
        }

        $invoices = $gym->invoices()
            ->orderByDesc('issued_date')
            ->get()
            ->map(fn ($i) => $this->format($i));

        return ApiResponse::ok('invoices_fetched', 'Invoices fetched.', ['invoices' => $invoices]);
    }

    /**
     * GET /api/owner/gym/invoices/{invoiceId}
     */
    public function show(Request $request, int $invoiceId): JsonResponse
    {
        $gym     = $request->user()->gym;
        $invoice = $gym ? $gym->invoices()->find($invoiceId) : null;

        if (!$invoice) {
            return ApiResponse::badRequest('invoice_not_found', 'Invoice not found.');
        }

        return ApiResponse::ok('invoice_fetched', 'Invoice fetched.', ['invoice' => $this->format($invoice)]);
    }

    /**
     * GET /api/owner/gym/invoices/{invoiceId}/download
     */
    public function download(Request $request, int $invoiceId, InvoiceService $invoiceService): Response|JsonResponse
    {
        $gym     = $request->user()->gym;
        $invoice = $gym ? $gym->invoices()->find($invoiceId) : null;

        if (!$invoice) {
            return ApiResponse::badRequest('invoice_not_found', 'Invoice not found.');
        }

        $pdf = $invoiceService->renderPdf($invoice);

        return response($pdf, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $invoice->invoice_number . '.pdf"');
    }

    // ── Private ───────────────────────────────────────────────────────────────

    private function format(Invoice $invoice): array
    {
        return [
            'id'              => $invoice->id,
            'invoice_number'  => $invoice->invoice_number,
            'source'          => $invoice->source,
            'member_name'     => $invoice->member_name,
            'member_phone'    => $invoice->member_phone,
            'member_email'    => $invoice->member_email,
            'plan_label'      => $invoice->plan_label,
            'amount'          => $invoice->amount,
            'payment_status'  => $invoice->payment_status,
            'issued_date'     => $invoice->issued_date->format('Y-m-d'),
        ];
    }
}
