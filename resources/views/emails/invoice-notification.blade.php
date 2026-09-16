<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Receipt</title>
</head>
<body style="margin:0;padding:0;background:#f4f4f5;font-family:Arial, Helvetica, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="padding:32px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="480" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:8px;overflow:hidden;">
                    <tr>
                        <td style="background:#FF5C1A;padding:24px 32px;">
                            <span style="color:#ffffff;font-size:20px;font-weight:bold;">{{ $invoice->gym_name_snapshot }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:32px;">
                            <p style="font-size:16px;color:#111111;margin:0 0 16px;">Hi {{ $invoice->member_name }},</p>

                            <p style="font-size:14px;color:#444444;line-height:1.6;margin:0 0 16px;">
                                Thanks! Here's your receipt (<strong>{{ $invoice->invoice_number }}</strong>) for
                                {{ $invoice->plan_label ?: 'your membership' }} at <strong>{{ $invoice->gym_name_snapshot }}</strong>.
                            </p>

                            <p style="font-size:14px;color:#444444;line-height:1.6;margin:0;">
                                It's attached to this email as a PDF.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:16px 32px;background:#f9f9f9;">
                            <span style="font-size:12px;color:#999999;">Sent via GymPass India</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
