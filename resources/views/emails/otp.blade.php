<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>OTP Code</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f7f7f8; padding: 20px;">
    <div style="max-width: 500px; margin: auto; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,.1);">
        <h2 style="color: #fe2c55; text-align: center;">Password Reset</h2>
        <p>Hello,</p>
        <p>Your OTP code is:</p>
        <h1 style="text-align: center; color: #333;">{{ $otp }}</h1>
        <p>This code is valid for <strong>10 minutes</strong>. Enter it on the reset password page to continue.</p>
        <br>
        <p style="font-size: 12px; color: #999;">If you didn’t request this, please ignore this email.</p>
    </div>
</body>
</html>
