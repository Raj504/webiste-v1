<?php

namespace App\Services;

use Razorpay\Api\Api;

/**
 * Wraps all Razorpay SDK calls.
 *
 * Add to .env:
 *   RAZORPAY_KEY_ID=rzp_live_xxxxxxxxx
 *   RAZORPAY_KEY_SECRET=xxxxxxxxxxxxxxxxxx
 *   RAZORPAY_WEBHOOK_SECRET=xxxxxxxxxxxxxxxxxx
 *   RAZORPAY_COMMISSION_PCT=10
 */
class RazorpayService
{
    private Api $api;

    public function __construct()
    {
        $this->api = new Api(
            config('services.razorpay.key_id'),
            config('services.razorpay.key_secret'),
        );
    }

    /**
     * Create a Razorpay order.
     * Amount must be in paise (₹1 = 100 paise).
     *
     * @return array { id, amount, currency, receipt }
     */
    public function createOrder(int $amountInRupees, string $receipt, array $notes = []): array
    {
        $order = $this->api->order->create([
            'amount'   => $amountInRupees * 100, // convert to paise
            'currency' => 'INR',
            'receipt'  => $receipt,
            'notes'    => $notes,
        ]);

        return $order->toArray();
    }

    /**
     * Verify Razorpay payment signature.
     * Returns true if valid, false if tampered.
     */
    public function verifySignature(
        string $orderId,
        string $paymentId,
        string $signature
    ): bool {
        try {
            $this->api->utility->verifyPaymentSignature([
                'razorpay_order_id'   => $orderId,
                'razorpay_payment_id' => $paymentId,
                'razorpay_signature'  => $signature,
            ]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Verify a Razorpay webhook's signature.
     * Webhooks are signed with the webhook secret (set in the Razorpay dashboard),
     * not the API key secret used for checkout signature verification.
     *
     * $rawPayload must be the exact, unmodified request body string Razorpay sent.
     */
    public function verifyWebhookSignature(string $rawPayload, string $signature): bool
    {
        try {
            $this->api->utility->verifyWebhookSignature(
                $rawPayload,
                $signature,
                config('services.razorpay.webhook_secret'),
            );
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Calculate commission split.
     *
     * @return array { gross, commission_pct, commission_amount, payout_amount }
     */
    public function calculateSplit(int $grossAmount): array
    {
        $pct        = (float) config('services.razorpay.commission_pct', 10);
        $commission = (int) round($grossAmount * $pct / 100);
        $payout     = $grossAmount - $commission;

        return [
            'gross'             => $grossAmount,
            'commission_pct'    => $pct,
            'commission_amount' => $commission,
            'payout_amount'     => $payout,
        ];
    }
}