<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Your Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .email-container {
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #490B22 0%, #E33183 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .header p {
            margin: 10px 0 0 0;
            font-size: 16px;
            opacity: 0.9;
        }
        .content {
            padding: 30px;
        }
        .greeting {
            font-size: 18px;
            color: #490B22;
            margin-bottom: 20px;
        }
        .progress-container {
            background-color: #f0f0f0;
            border-radius: 20px;
            padding: 20px;
            margin: 25px 0;
            text-align: center;
        }
        .progress-bar-wrapper {
            background-color: #e0e0e0;
            border-radius: 10px;
            height: 30px;
            overflow: hidden;
            margin: 15px 0;
            position: relative;
        }
        .progress-bar {
            background: linear-gradient(90deg, #E33183 0%, #490B22 100%);
            height: 100%;
            width: {{ $completionPercentage }}%;
            border-radius: 10px;
            transition: width 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 14px;
        }
        .progress-text {
            font-size: 24px;
            font-weight: bold;
            color: #490B22;
            margin-top: 10px;
        }
        .benefits-section {
            background-color: #fff5f8;
            padding: 20px;
            border-radius: 8px;
            margin: 25px 0;
            border-left: 4px solid #E33183;
        }
        .benefits-section h3 {
            color: #490B22;
            margin-top: 0;
            font-size: 18px;
        }
        .benefits-list {
            list-style: none;
            padding: 0;
            margin: 15px 0;
        }
        .benefits-list li {
            padding: 10px 0;
            padding-left: 30px;
            position: relative;
        }
        .benefits-list li:before {
            content: "✓";
            position: absolute;
            left: 0;
            color: #E33183;
            font-weight: bold;
            font-size: 18px;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #E33183 0%, #490B22 100%);
            color: white;
            padding: 15px 40px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            font-size: 16px;
            margin: 20px 0;
            text-align: center;
            box-shadow: 0 4px 6px rgba(227, 49, 131, 0.3);
        }
        .cta-container {
            text-align: center;
            margin: 30px 0;
        }
        .footer {
            text-align: center;
            padding: 20px;
            background-color: #f9f9f9;
            color: #666;
            font-size: 14px;
        }
        .footer p {
            margin: 5px 0;
        }
        .missing-sections {
            background-color: #fff9e6;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #f59e0b;
        }
        .missing-sections p {
            margin: 5px 0;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>Complete Your Profile!</h1>
            <p>Find Your Perfect Match</p>
        </div>

        <div class="content">
            <p class="greeting">Dear {{ $user->name }},</p>

            <p>We noticed that your profile on <strong>Engineer's Matrimony</strong> is not yet complete. A complete profile significantly increases your chances of finding your perfect match!</p>

            <div class="progress-container">
                <p style="margin: 0; color: #666; font-size: 16px;">Your Profile Completion</p>
                <div class="progress-bar-wrapper">
                    <div class="progress-bar">{{ number_format($completionPercentage, 0) }}%</div>
                </div>
                <p class="progress-text">{{ number_format($completionPercentage, 0) }}% Complete</p>
            </div>

            <div class="benefits-section">
                <h3>Why Complete Your Profile?</h3>
                <ul class="benefits-list">
                    <li>Increase profile visibility by up to 5x</li>
                    <li>Receive more connection requests from interested matches</li>
                    <li>Help others understand you better</li>
                    <li>Show you're serious about finding your life partner</li>
                    <li>Get prioritized in search results</li>
                </ul>
            </div>

            @if($completionPercentage < 50)
            <div class="missing-sections">
                <p><strong>⚠️ Your profile needs attention!</strong></p>
                <p>Complete profiles get 5x more views. Take a few minutes to add your information and increase your chances of finding your perfect match.</p>
            </div>
            @elseif($completionPercentage < 70)
            <div class="missing-sections">
                <p><strong>📝 You're almost there!</strong></p>
                <p>Just a few more details and your profile will be complete. Complete profiles stand out and attract more genuine connections.</p>
            </div>
            @endif

            <div class="cta-container">
                <a href="{{ route('profile', $user->id) }}" class="cta-button">Complete My Profile Now</a>
            </div>

            <p>Don't miss out on the opportunity to connect with your ideal life partner. Complete your profile today and take the first step toward your happily ever after!</p>

            <p style="margin-top: 30px;">Best regards,<br>
            <strong>Engineer's Matrimony Team</strong></p>
        </div>

        <div class="footer">
            <p>This is an automated reminder. You can update your email preferences in your account settings.</p>
            <p>&copy; {{ date('Y') }} Engineer's Matrimony. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
