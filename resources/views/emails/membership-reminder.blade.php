<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membership Renewal Reminder</title>
</head>
<body style="margin:0;padding:0;background:#f4f4f5;font-family:Arial, Helvetica, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="padding:32px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="480" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:8px;overflow:hidden;">
                    <tr>
                        <td style="background:#FF5C1A;padding:24px 32px;">
                            <span style="color:#ffffff;font-size:20px;font-weight:bold;">{{ $member->gym->name }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:32px;">
                            <p style="font-size:16px;color:#111111;margin:0 0 16px;">Hi {{ $member->name }},</p>

                            @if ($member->status === 'expired')
                                <p style="font-size:14px;color:#444444;line-height:1.6;margin:0 0 16px;">
                                    Your membership at <strong>{{ $member->gym->name }}</strong> expired on
                                    <strong>{{ $member->due_date->format('d M Y') }}</strong>. Please renew to continue your access.
                                </p>
                            @else
                                <p style="font-size:14px;color:#444444;line-height:1.6;margin:0 0 16px;">
                                    Your membership at <strong>{{ $member->gym->name }}</strong> is due for renewal on
                                    <strong>{{ $member->due_date->format('d M Y') }}</strong>.
                                </p>
                            @endif

                            <p style="font-size:14px;color:#444444;line-height:1.6;margin:0;">
                                Please reach out to the gym or visit in person to renew your plan.
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
