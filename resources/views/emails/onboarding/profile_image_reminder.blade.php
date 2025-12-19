<!DOCTYPE html>
<html>
<head>
    <title>Complete your profile</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #e11d48;">Hello {{ $user->name }},</h2>
        <p>We noticed you haven't uploaded a profile picture yet. Did you know that profiles with photos get 10x more visibility?</p>
        <p>Upload your photo now to find your perfect match faster!</p>
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('profile', $user->id) }}"
                style="background-color: #e11d48; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;">Upload
                Photo</a>
        </div>
        <p>Best regards,<br>The Lara Biye Team</p>
    </div>
</body>
</html>
