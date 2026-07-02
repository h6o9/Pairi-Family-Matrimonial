<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? 'Piyari Family' }}</title>
</head>
<body style="margin:0;padding:0;background-color:#f4f4f4;font-family:Arial,Helvetica,sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color:#f4f4f4;padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" style="max-width:600px;width:100%;background-color:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 12px rgba(0,0,0,0.08);">
                    <tr>
                        <td style="background-color:#6E0016;padding:28px 24px;text-align:center;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="center" style="padding-bottom:16px;">
                                        <div style="display:inline-block;background:#ffffff;border-radius:10px;padding:12px 20px;">
                                            <img src="{{ $logoUrl }}" alt="Piyari Family" width="160" style="display:block;max-width:160px;height:auto;">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center">
                                        <h1 style="margin:0;color:#ffffff;font-size:22px;font-weight:700;line-height:1.3;">{{ $heading }}</h1>
                                        <p style="margin:8px 0 0;color:#F5A623;font-size:14px;">Piyari Family — made in heaven</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:32px 28px;color:#333333;font-size:15px;line-height:1.6;">
                            @if(!empty($greeting))
                                <p style="margin:0 0 16px;">{{ $greeting }}</p>
                            @endif
                            {{ $slot }}
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color:#FFF5F5;padding:20px 28px;text-align:center;border-top:1px solid #f0e0e0;">
                            <p style="margin:0 0 6px;color:#6E0016;font-size:13px;font-weight:600;">Piyari Family</p>
                            <p style="margin:0;color:#888888;font-size:12px;">&copy; {{ date('Y') }} Piyari Family. All rights reserved.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
