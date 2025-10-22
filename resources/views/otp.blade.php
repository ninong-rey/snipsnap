<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>SnipSnap OTP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f8;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .otp-code {
            background: #fe2c55;
            color: white;
            padding: 15px 30px;
            border-radius: 10px;
            font-size: 32px;
            font-weight: bold;
            text-align: center;
            letter-spacing: 5px;
            margin: 20px 0;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>SnipSnap Verification Code</h2>
        </div>
        
        <p>Hello,</p>
        
        <p>Use the following OTP code to reset your password:</p>
        
        <div class="otp-code">
            {{ $otpCode }}
        </div>
        
        <p>This code will expire in 10 minutes.</p>
        
        <p>If you didn't request this code, please ignore this email.</p>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} SnipSnap. All rights reserved.</p>
        </div>
    </div>
</body>
</html>