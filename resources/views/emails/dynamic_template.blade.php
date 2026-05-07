<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
</head>
<body style="margin:0;padding:0;background-color:#f8f4f5;font-family:Arial,sans-serif;color:#374151;">
<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f8f4f5;padding:32px 16px;">
<tr><td align="center">
<table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 16px rgba(0,0,0,0.08);">

    <!-- Header -->
    <tr>
        <td style="background-color:#490B22;padding:32px 40px;text-align:center;">
            <p style="margin:0;font-size:13px;color:rgba(255,255,255,0.6);letter-spacing:2px;text-transform:uppercase;">Engineer's Matrimony</p>
            <h1 style="margin:8px 0 0;font-size:24px;color:#ffffff;font-weight:700;">{{ $subject }}</h1>
        </td>
    </tr>

    <!-- Body -->
    <tr>
        <td style="padding:40px;font-size:15px;color:#374151;line-height:1.7;">
            {!! $content !!}
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
