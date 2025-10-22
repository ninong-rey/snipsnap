<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f9f9f9; padding: 50px; }
        .container { max-width: 400px; margin: auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { margin-bottom: 20px; }
        input { width: 100%; padding: 8px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; }
        button { background: #3490dc; color: #fff; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #2779bd; }
        .message { padding: 10px; border-radius: 4px; margin-bottom: 15px; }
        .error { background: #f8d7da; color: #721c24; }
        .success { background: #d4edda; color: #155724; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Enter OTP</h2>

        @if(session('error'))
            <div class="message error">{{ session('error') }}</div>
        @endif

        @if(session('success'))
            <div class="message success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('otp.verify') }}" method="POST">
            @csrf
            <label for="otp">OTP Code</label>
            <input type="text" name="otp" id="otp" required>
            <button type="submit">Verify OTP</button>
        </form>
    </div>
</body>
</html>
