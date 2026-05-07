<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Invoice</title>
</head>
<body style="margin:0;padding:0;background-color:#f8f4f5;font-family:Arial,sans-serif;color:#374151;">
<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f8f4f5;padding:32px 16px;">
<tr><td align="center">
<table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 16px rgba(0,0,0,0.08);">

    <!-- Header -->
    <tr>
        <td style="background-color:#490B22;padding:32px 40px;text-align:center;">
            <p style="margin:0;font-size:13px;color:rgba(255,255,255,0.6);letter-spacing:2px;text-transform:uppercase;">Engineer's Matrimony</p>
            <h1 style="margin:8px 0 0;font-size:26px;color:#ffffff;font-weight:700;">Payment Invoice</h1>
            <p style="margin:8px 0 0;font-size:14px;color:rgba(255,255,255,0.75);">Your payment has been successfully processed</p>
        </td>
    </tr>

    <!-- Body -->
    <tr>
        <td style="padding:40px;">
            <p style="margin:0 0 20px;font-size:16px;color:#374151;">Dear <strong>{{ $user->name }}</strong>,</p>
            <p style="margin:0 0 28px;font-size:15px;color:#4b5563;line-height:1.7;">
                Thank you for your purchase! Below are the details of your transaction for your records.
            </p>

            <!-- Success Badge -->
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:28px;">
                <tr>
                    <td align="center">
                        <span style="display:inline-block;background-color:#dcfce7;color:#166534;padding:6px 20px;border-radius:99px;font-size:13px;font-weight:700;border:1px solid #bbf7d0;">
                            ✓ {{ $paymentResponse['transactionStatus'] ?? 'Payment Completed' }}
                        </span>
                    </td>
                </tr>
            </table>

            <!-- Transaction Details -->
            <table width="100%" cellpadding="0" cellspacing="0" style="background:#fff5f8;border-radius:10px;border:1px solid #fce7f3;margin-bottom:20px;">
                <tr>
                    <td style="padding:20px 24px 8px;">
                        <p style="margin:0;font-size:15px;font-weight:700;color:#490B22;">Transaction Details</p>
                    </td>
                </tr>
                <tr>
                    <td style="padding:0 24px 20px;">
                        <table width="100%" cellpadding="0" cellspacing="0">
                            <tr>
                                <td style="padding:10px 0;border-bottom:1px solid #fce7f3;font-size:14px;color:#6b7280;font-weight:600;">Invoice Number</td>
                                <td style="padding:10px 0;border-bottom:1px solid #fce7f3;font-size:14px;color:#374151;text-align:right;">{{ $paymentResponse['merchantInvoiceNumber'] ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td style="padding:10px 0;border-bottom:1px solid #fce7f3;font-size:14px;color:#6b7280;font-weight:600;">Transaction ID</td>
                                <td style="padding:10px 0;border-bottom:1px solid #fce7f3;font-size:14px;color:#374151;text-align:right;">{{ $paymentResponse['trxID'] ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td style="padding:10px 0;border-bottom:1px solid #fce7f3;font-size:14px;color:#6b7280;font-weight:600;">Payment ID</td>
                                <td style="padding:10px 0;border-bottom:1px solid #fce7f3;font-size:14px;color:#374151;text-align:right;">{{ $paymentResponse['paymentID'] ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td style="padding:10px 0;border-bottom:1px solid #fce7f3;font-size:14px;color:#6b7280;font-weight:600;">Date</td>
                                <td style="padding:10px 0;border-bottom:1px solid #fce7f3;font-size:14px;color:#374151;text-align:right;">{{ now()->format('F d, Y — h:i A') }}</td>
                            </tr>
                            <tr>
                                <td style="padding:10px 0;font-size:14px;color:#6b7280;font-weight:600;">Payment Method</td>
                                <td style="padding:10px 0;font-size:14px;color:#374151;text-align:right;">bKash</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <!-- Package Details -->
            <table width="100%" cellpadding="0" cellspacing="0" style="background:#fff5f8;border-radius:10px;border:1px solid #fce7f3;margin-bottom:24px;">
                <tr>
                    <td style="padding:20px 24px 8px;">
                        <p style="margin:0;font-size:15px;font-weight:700;color:#490B22;">Package Details</p>
                    </td>
                </tr>
                <tr>
                    <td style="padding:0 24px;">
                        <table width="100%" cellpadding="0" cellspacing="0">
                            <tr>
                                <td style="padding:10px 0;border-bottom:1px solid #fce7f3;font-size:14px;color:#6b7280;font-weight:600;">Package</td>
                                <td style="padding:10px 0;border-bottom:1px solid #fce7f3;font-size:14px;color:#374151;text-align:right;">{{ $package->name }}</td>
                            </tr>
                            <tr>
                                <td style="padding:10px 0;border-bottom:1px solid #fce7f3;font-size:14px;color:#6b7280;font-weight:600;">Connections</td>
                                <td style="padding:10px 0;border-bottom:1px solid #fce7f3;font-size:14px;color:#374151;text-align:right;">{{ $package->connections }} connections</td>
                            </tr>
                            @if($package->description)
                            <tr>
                                <td style="padding:10px 0;border-bottom:1px solid #fce7f3;font-size:14px;color:#6b7280;font-weight:600;">Description</td>
                                <td style="padding:10px 0;border-bottom:1px solid #fce7f3;font-size:14px;color:#374151;text-align:right;">{{ $package->description }}</td>
                            </tr>
                            @endif
                        </table>
                    </td>
                </tr>
                <!-- Total Row -->
                <tr>
                    <td style="padding:16px 24px;background-color:#490B22;border-radius:0 0 10px 10px;">
                        <table width="100%" cellpadding="0" cellspacing="0">
                            <tr>
                                <td style="font-size:16px;font-weight:700;color:#ffffff;">Total Amount</td>
                                <td style="font-size:20px;font-weight:700;color:#ffffff;text-align:right;">৳{{ number_format($package->price, 2) }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <!-- What's Next -->
            <table width="100%" cellpadding="0" cellspacing="0" style="background:#f0fdf4;border-left:4px solid #22c55e;border-radius:0 8px 8px 0;padding:16px;margin-bottom:28px;">
                <tr><td>
                    <p style="margin:0 0 4px;font-size:14px;font-weight:700;color:#166534;">Your connections are ready!</p>
                    <p style="margin:0;font-size:14px;color:#15803d;">{{ $package->connections }} connections have been added to your account. You can now start connecting with potential matches.</p>
                </td></tr>
            </table>

            <p style="margin:0 0 8px;font-size:14px;color:#6b7280;">If you have any questions about this transaction, please contact our support team.</p>

            <p style="margin:0;font-size:15px;color:#374151;">Warm regards,<br><strong style="color:#490B22;">Engineer's Matrimony Team</strong></p>
        </td>
    </tr>

    <!-- Footer -->
    <tr>
        <td style="background-color:#f9f9f9;padding:20px 40px;text-align:center;border-top:1px solid #f0f0f0;">
            <p style="margin:0 0 4px;font-size:12px;color:#9ca3af;">This is an automated email. Please do not reply to this message.</p>
            <p style="margin:0;font-size:12px;color:#9ca3af;">© {{ date('Y') }} Engineer's Matrimony. All rights reserved.</p>
        </td>
    </tr>

</table>
</td></tr>
</table>
</body>
</html>
