<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Your Profile</title>
</head>
<body style="margin:0;padding:0;background-color:#f8f4f5;font-family:Arial,sans-serif;color:#374151;">
<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f8f4f5;padding:32px 16px;">
<tr><td align="center">
<table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 16px rgba(0,0,0,0.08);">

    <!-- Header -->
    <tr>
        <td style="background-color:#490B22;padding:32px 40px;text-align:center;">
            <p style="margin:0;font-size:13px;color:rgba(255,255,255,0.6);letter-spacing:2px;text-transform:uppercase;">Engineer's Matrimony</p>
            <h1 style="margin:8px 0 0;font-size:26px;color:#ffffff;font-weight:700;">Complete Your Profile</h1>
            <p style="margin:8px 0 0;font-size:14px;color:rgba(255,255,255,0.75);">Find your perfect match faster</p>
        </td>
    </tr>

    <!-- Body -->
    <tr>
        <td style="padding:40px;">
            <p style="margin:0 0 16px;font-size:16px;color:#374151;">Dear <strong>{{ $user->name }}</strong>,</p>
            <p style="margin:0 0 28px;font-size:15px;color:#4b5563;line-height:1.7;">
                Your profile on <strong>Engineer's Matrimony</strong> is not yet complete. A complete profile significantly increases your chances of finding your perfect match!
            </p>

            <!-- Progress Bar -->
            <table width="100%" cellpadding="0" cellspacing="0" style="background:#f9f0f4;border-radius:10px;padding:24px;margin-bottom:28px;text-align:center;">
                <tr>
                    <td>
                        <p style="margin:0 0 12px;font-size:14px;color:#6b7280;">Your Profile Completion</p>
                        <table width="100%" cellpadding="0" cellspacing="0" style="background:#e5e7eb;border-radius:99px;height:20px;overflow:hidden;margin-bottom:12px;">
                            <tr>
                                <td width="{{ number_format($completionPercentage, 0) }}%" style="background-color:#E33183;border-radius:99px;height:20px;"></td>
                                <td></td>
                            </tr>
                        </table>
                        <p style="margin:0;font-size:28px;font-weight:700;color:#490B22;">{{ number_format($completionPercentage, 0) }}% Complete</p>
                    </td>
                </tr>
            </table>

            @if($completionPercentage < 50)
            <!-- Warning -->
            <table width="100%" cellpadding="0" cellspacing="0" style="background:#fff7ed;border-left:4px solid #f59e0b;border-radius:0 8px 8px 0;padding:16px;margin-bottom:24px;">
                <tr><td>
                    <p style="margin:0 0 4px;font-size:14px;font-weight:700;color:#92400e;">Your profile needs attention!</p>
                    <p style="margin:0;font-size:14px;color:#78350f;">Complete profiles get 5x more views. Take a few minutes to add your information.</p>
                </td></tr>
            </table>
            @elseif($completionPercentage < 80)
            <!-- Almost there -->
            <table width="100%" cellpadding="0" cellspacing="0" style="background:#f0fdf4;border-left:4px solid #22c55e;border-radius:0 8px 8px 0;padding:16px;margin-bottom:24px;">
                <tr><td>
                    <p style="margin:0 0 4px;font-size:14px;font-weight:700;color:#166534;">You're almost there!</p>
                    <p style="margin:0;font-size:14px;color:#15803d;">Just a few more details and your profile will stand out to potential matches.</p>
                </td></tr>
            </table>
            @endif

            <!-- Benefits -->
            <table width="100%" cellpadding="0" cellspacing="0" style="background:#fff5f8;border-radius:10px;border:1px solid #fce7f3;padding:24px;margin-bottom:28px;">
                <tr>
                    <td>
                        <p style="margin:0 0 14px;font-size:15px;font-weight:700;color:#490B22;">Why complete your profile?</p>
                        <p style="margin:0 0 10px;font-size:14px;color:#6b7280;">✓ Up to 5x more profile visibility</p>
                        <p style="margin:0 0 10px;font-size:14px;color:#6b7280;">✓ Receive more genuine connection requests</p>
                        <p style="margin:0 0 10px;font-size:14px;color:#6b7280;">✓ Help matches understand you better</p>
                        <p style="margin:0 0 10px;font-size:14px;color:#6b7280;">✓ Show you're serious about finding a life partner</p>
                        <p style="margin:0;font-size:14px;color:#6b7280;">✓ Get prioritized in search results</p>
                    </td>
                </tr>
            </table>

            <!-- CTA -->
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td align="center" style="padding:8px 0 32px;">
                        <a href="{{ route('profile', $user->id) }}"
                           style="display:inline-block;background-color:#E33183;color:#ffffff;padding:15px 40px;text-decoration:none;border-radius:8px;font-weight:700;font-size:16px;">
                            Complete My Profile Now
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
            <p style="margin:0 0 4px;font-size:12px;color:#9ca3af;">This is an automated reminder. You can update your email preferences in your account settings.</p>
            <p style="margin:0;font-size:12px;color:#9ca3af;">© {{ date('Y') }} Engineer's Matrimony. All rights reserved.</p>
        </td>
    </tr>

</table>
</td></tr>
</table>
</body>
</html>
