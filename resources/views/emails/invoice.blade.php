<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #490B22;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f9f9f9;
            padding: 30px;
            border: 1px solid #ddd;
        }
        .invoice-details {
            background-color: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
            border: 1px solid #e0e0e0;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .label {
            font-weight: bold;
            color: #666;
        }
        .value {
            color: #333;
        }
        .total-row {
            background-color: #490B22;
            color: white;
            padding: 15px;
            margin-top: 20px;
            border-radius: 5px;
            font-size: 18px;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 14px;
        }
        .success-badge {
            background-color: #10b981;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            display: inline-block;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Payment Invoice</h1>
        <p>Engineer's Matrimony</p>
    </div>

    <div class="content">
        <p>Dear {{ $user->name }},</p>

        <p>Thank you for your purchase! Your payment has been successfully processed.</p>

        <div class="invoice-details">
            <h2 style="margin-top: 0; color: #490B22;">Invoice Details</h2>

            <div class="detail-row">
                <span class="label">Invoice Number:</span>
                <span class="value">{{ $paymentResponse['merchantInvoiceNumber'] ?? 'N/A' }}</span>
            </div>

            <div class="detail-row">
                <span class="label">Transaction ID:</span>
                <span class="value">{{ $paymentResponse['trxID'] ?? 'N/A' }}</span>
            </div>

            <div class="detail-row">
                <span class="label">Payment ID:</span>
                <span class="value">{{ $paymentResponse['paymentID'] ?? 'N/A' }}</span>
            </div>

            <div class="detail-row">
                <span class="label">Date:</span>
                <span class="value">{{ now()->format('F d, Y - h:i A') }}</span>
            </div>

            <div class="detail-row">
                <span class="label">Payment Method:</span>
                <span class="value">bKash</span>
            </div>

            <div class="detail-row">
                <span class="label">Status:</span>
                <span class="value">
                    <span class="success-badge">{{ $paymentResponse['transactionStatus'] ?? 'Completed' }}</span>
                </span>
            </div>
        </div>

        <div class="invoice-details">
            <h2 style="margin-top: 0; color: #490B22;">Package Details</h2>

            <div class="detail-row">
                <span class="label">Package Name:</span>
                <span class="value">{{ $package->name }}</span>
            </div>

            <div class="detail-row">
                <span class="label">Description:</span>
                <span class="value">{{ $package->description }}</span>
            </div>

            <div class="detail-row">
                <span class="label">Profile Views:</span>
                <span class="value">{{ $package->connections }} connections</span>
            </div>

            <div class="total-row" style="display: flex; justify-content: space-between;">
                <span>Total Amount:</span>
                <span>৳{{ number_format($package->price, 2) }}</span>
            </div>
        </div>

        <div style="background-color: #e7f3ff; padding: 15px; border-left: 4px solid #490B22; margin: 20px 0;">
            <p style="margin: 0;"><strong>What's Next?</strong></p>
            <p style="margin: 5px 0 0 0;">Your {{ $package->connections }} profile view connections have been added to your account. You can now start viewing profiles and connecting with potential matches!</p>
        </div>

        <p>If you have any questions or concerns about this transaction, please don't hesitate to contact our support team.</p>

        <p>Best regards,<br>
        <strong>Engineer's Matrimony Team</strong></p>
    </div>

    <div class="footer">
        <p>This is an automated email. Please do not reply to this message.</p>
        <p>&copy; {{ date('Y') }} Engineer's Matrimony. All rights reserved.</p>
    </div>
</body>
</html>
