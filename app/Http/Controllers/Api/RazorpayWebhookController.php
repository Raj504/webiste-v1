<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Services\BookingService;
use App\Services\RazorpayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RazorpayWebhookController extends Controller
{
    private RazorpayService $razorpay;
    private BookingService $bookingService;

    public function __construct(RazorpayService $razorpay, BookingService $bookingService)
    {
        $this->razorpay = $razorpay;
        $this->bookingService = $bookingService;
    }

    /**
     * POST /api/webhooks/razorpay
     *
     * Public endpoint called by Razorpay itself (not the FE) whenever a payment
     * event happens — this is the backend safety net for verify-payment. If the
     * traveler's browser never completes the verify-payment call (closed tab,
     * dropped connection) after Razorpay already captured the money, this webhook
     * still gets the booking activated instead of it being stuck "pending" forever.
     *
     * Must be registered outside auth:sanctum — Razorpay's servers don't send a
     * Bearer token, only the X-Razorpay-Signature header.
     */
    public function handle(Request $request): JsonResponse
    {
        $signature = $request->header('X-Razorpay-Signature');

        if (!$signature) {
            return ApiResponse::badRequest('missing_signature', 'Missing webhook signature.');
        }

        // Signature is computed over the exact raw bytes Razorpay sent —
        // the parsed/re-encoded request body would not match.
        $rawPayload = $request->getContent();

        if (!$this->razorpay->verifyWebhookSignature($rawPayload, $signature)) {
            return ApiResponse::badRequest('invalid_signature', 'Webhook signature verification failed.');
        }

        if ($request->input('event') === 'payment.captured') {
            $orderId   = $request->input('payload.payment.entity.order_id');
            $paymentId = $request->input('payload.payment.entity.id');

            if ($orderId && $paymentId) {
                try {
                    $this->bookingService->activateFromWebhook($orderId, $paymentId);
                } catch (\Exception $e) {
                    // Logged for manual follow-up — still ack the webhook below so
                    // Razorpay doesn't retry-hammer us for a booking-side issue.
                    report($e);
                }
            }
        }

        return ApiResponse::ok('webhook_processed', 'Webhook processed.');
    }
}
