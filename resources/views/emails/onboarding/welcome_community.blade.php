<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to the Community</title>
</head>
<body style="margin:0;padding:0;background-color:#f8f4f5;font-family:Arial,sans-serif;color:#374151;">
<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f8f4f5;padding:32px 16px;">
<tr><td align="center">
<table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 16px rgba(0,0,0,0.08);">

    <!-- Header -->
    <tr>
        <td style="background-color:#490B22;padding:32px 40px;text-align:center;">
            <p style="margin:0;font-size:13px;color:rgba(255,255,255,0.6);letter-spacing:2px;text-transform:uppercase;">Engineer's Matrimony</p>
            <h1 style="margin:8px 0 0;font-size:26px;color:#ffffff;font-weight:700;">Welcome to the Community!</h1>
        </td>
    </tr>

    <!-- Body -->
    <tr>
        <td style="padding:40px;">
            <p style="margin:0 0 16px;font-size:16px;color:#374151;">Hi <strong>{{ $user->name }}</strong>,</p>
            <p style="margin:0 0 24px;font-size:15px;color:#4b5563;line-height:1.7;">
                It's been 3 days since you joined us — and we're thrilled to have you! 🎉 We'd love to welcome you into our exclusive community groups where you can connect with other members and stay updated.
            </p>

            <table width="100%" cellpadding="0" cellspacing="0" style="background:#fff5f8;border-radius:10px;border:1px solid #fce7f3;padding:24px;margin-bottom:24px;">
                <tr>
                    <td>
                        <p style="margin:0 0 12px;font-size:15px;font-weight:700;color:#490B22;">Join Our Community</p>
                        <p style="margin:0 0 12px;font-size:14px;color:#6b7280;">
                            📘 <a href="#" style="color:#E33183;text-decoration:none;font-weight:600;">Facebook Page</a> — Stay updated with news and announcements
                        </p>
                        <p style="margin:0;font-size:14px;color:#6b7280;">
                            👥 <a href="#" style="color:#E33183;text-decoration:none;font-weight:600;">Community Group</a> — Meet and connect with fellow members
                        </p>
                    </td>
                </tr>
            </table>

            <p style="margin:0 0 32px;font-size:15px;color:#4b5563;line-height:1.7;">
                Be an active part of our growing community. Share experiences, get advice, and support each other on the journey to finding your life partner.
            </p>

            <p style="margin:0;font-size:15px;color:#374151;">Warm regards,<br><strong style="color:#490B22;">Engineer's Matrimony Team</strong></p>
        </td>
    </tr>

    <!-- Footer -->
    <tr>
        <td style="background-color:#f9f9f9;padding:20px 40px;text-align:center;border-top:1px solid #f0f0f0;">
            <p style="margin:0;font-size:12px;color:#9ca3af;">© {{ date('Y') }} Engineer's Matrimony. All rights reserved.</p>
        </td>
    </tr>

</table>
</td></tr>
</table>
</body>
</html>
