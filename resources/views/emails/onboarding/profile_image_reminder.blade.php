<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Your Profile Photo</title>
</head>
<body style="margin:0;padding:0;background-color:#f8f4f5;font-family:Arial,sans-serif;color:#374151;">
<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f8f4f5;padding:32px 16px;">
<tr><td align="center">
<table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 16px rgba(0,0,0,0.08);">

    <!-- Header -->
    <tr>
        <td style="background-color:#490B22;padding:32px 40px;text-align:center;">
            <p style="margin:0;font-size:13px;color:rgba(255,255,255,0.6);letter-spacing:2px;text-transform:uppercase;">Engineer's Matrimony</p>
            <h1 style="margin:8px 0 0;font-size:26px;color:#ffffff;font-weight:700;">Add Your Profile Photo</h1>
        </td>
    </tr>

    <!-- Body -->
    <tr>
        <td style="padding:40px;">
            <p style="margin:0 0 16px;font-size:16px;color:#374151;">Hi <strong>{{ $user->name }}</strong>,</p>
            <p style="margin:0 0 24px;font-size:15px;color:#4b5563;line-height:1.7;">
                We noticed you haven't uploaded a profile photo yet. Did you know that profiles with photos receive <strong style="color:#E33183;">10x more visibility</strong> than those without?
            </p>

            <table width="100%" cellpadding="0" cellspacing="0" style="background:#fff5f8;border-radius:10px;border:1px solid #fce7f3;padding:24px;margin-bottom:28px;">
                <tr>
                    <td>
                        <p style="margin:0 0 10px;font-size:15px;font-weight:700;color:#490B22;">Why add a photo?</p>
                        <p style="margin:0 0 8px;font-size:14px;color:#6b7280;">✓ Get 10x more profile views</p>
                        <p style="margin:0 0 8px;font-size:14px;color:#6b7280;">✓ Build trust with potential matches</p>
                        <p style="margin:0 0 8px;font-size:14px;color:#6b7280;">✓ Stand out in search results</p>
                        <p style="margin:0;font-size:14px;color:#6b7280;">✓ Show you're serious about finding a match</p>
                    </td>
                </tr>
            </table>

            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td align="center" style="padding:8px 0 28px;">
                        <a href="{{ route('profile', $user->id) }}"
                           style="display:inline-block;background-color:#E33183;color:#ffffff;padding:14px 36px;text-decoration:none;border-radius:8px;font-weight:700;font-size:15px;">
                            Upload My Photo
                        </a>
                    </td>
                </tr>
            </table>

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
