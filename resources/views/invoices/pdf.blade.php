<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $invoice->invoice_number }}</title>
</head>
<body style="margin:0;padding:0;font-family:Arial, Helvetica, sans-serif;color:#111111;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td style="background:#FF5C1A;padding:24px 32px;">
                <!-- Future: gym logo image will render here once logo storage is added -->
                <span style="color:#ffffff;font-size:22px;font-weight:bold;">{{ $invoice->gym_name_snapshot }}</span>
                @if ($invoice->gym_address_snapshot)
                    <br>
                    <span style="color:#ffffff;font-size:12px;">{{ $invoice->gym_address_snapshot }}</span>
                @endif
            </td>
        </tr>

        <tr>
            <td style="padding:32px;">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="50%" style="vertical-align:top;">
                            <span style="font-size:11px;color:#999999;text-transform:uppercase;">Billed to</span><br>
                            <span style="font-size:14px;">{{ $invoice->member_name }}</span><br>
                            @if ($invoice->member_phone)
                                <span style="font-size:12px;color:#444444;">{{ $invoice->member_phone }}</span><br>
                            @endif
                            @if ($invoice->member_email)
                                <span style="font-size:12px;color:#444444;">{{ $invoice->member_email }}</span>
                            @endif
                        </td>
                        <td width="50%" style="vertical-align:top;text-align:right;">
                            <span style="font-size:11px;color:#999999;text-transform:uppercase;">Receipt No.</span><br>
                            <span style="font-size:14px;">{{ $invoice->invoice_number }}</span><br>
                            <span style="font-size:11px;color:#999999;text-transform:uppercase;">Date</span><br>
                            <span style="font-size:14px;">{{ $invoice->issued_date->format('d M Y') }}</span>
                        </td>
                    </tr>
                </table>

                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-top:32px;border-top:1px solid #eeeeee;">
                    <tr>
                        <td style="padding:16px 0 8px;font-size:11px;color:#999999;text-transform:uppercase;">
                            {{ $invoice->source === 'booking' ? 'Day-pass booking' : 'Gym membership' }}
                        </td>
                    </tr>
                    <tr style="border-top:1px solid #eeeeee;">
                        <td style="padding:16px 0;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="font-size:14px;">{{ $invoice->plan_label ?: '—' }}</td>
                                    <td style="font-size:14px;text-align:right;">
                                        {{ $invoice->amount !== null ? '₹' . number_format($invoice->amount) : '—' }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-top:16px;">
                    <tr>
                        <td style="text-align:right;">
                            @if ($invoice->payment_status === 'paid')
                                <span style="display:inline-block;padding:4px 12px;border-radius:4px;background:#e6f6ec;color:#1a7f37;font-size:12px;font-weight:bold;">PAID</span>
                            @else
                                <span style="display:inline-block;padding:4px 12px;border-radius:4px;background:#fdf1e0;color:#9a6700;font-size:12px;font-weight:bold;">UNPAID</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td style="padding:16px 32px;background:#f9f9f9;">
                <span style="font-size:11px;color:#999999;">This is a receipt, not a tax invoice.</span><br>
                <span style="font-size:11px;color:#999999;">Sent via GymPass India</span>
            </td>
        </tr>
    </table>
</body>
</html>
