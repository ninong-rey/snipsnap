<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Forgot Password - SnipSnap</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<style>
:root {
    --accent: #fe2c55;
    --page-bg: #f7f7f8;
    --card-bg: #fff;
}
body {
    margin: 0;
    padding: 0;
    font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, "Helvetica Neue", Arial;
    background: var(--page-bg);
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}
.card {
    background: var(--card-bg);
    padding: 40px 30px;
    border-radius: 20px;
    box-shadow: 0 18px 50px rgba(2,6,23,0.06);
    width: 100%;
    max-width: 450px;
    display: flex;
    flex-direction: column;
    gap: 15px;
}
h2 {
    margin: 0;
    font-size: 22px;
    font-weight: 700;
    color: #111;
    text-align: center;
}
input[type=text], input[type=email], input[type=tel] {
    padding: 12px 15px;
    border: 1px solid #ddd;
    border-radius: 10px;
    font-size: 14px;
    width: 100%;
    box-sizing: border-box;
}
.btn {
    width: 100%;
    
    padding: 14px 15px;
    background: var(--accent);
    color: #fff;
    border: none;
    border-radius: 10px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 4px 12px rgba(254,44,85,0.3);
}
.btn:hover {
    background: #e6284f;
    box-shadow: 0 6px 15px rgba(254,44,85,0.4);
}
.message-box {
    padding: 12px;
    border-radius: 8px;
    font-size: 14px;
    margin-bottom: 15px;
    text-align: center;
}
.success {
    background: #e6ffed;
    border: 1px solid #38a169;
    color: #276749;
    display: block;
}
.error {
    background: #fff5f5;
    border: 1px solid #e53e3e;
    color: #9b2c2c;
    display: block;
}
.hidden {
    display: none;
}
a {
    color: var(--accent);
    text-decoration: none;
    font-size: 13px;
    font-weight: 500;
}
a:hover {
    opacity: 0.8;
}
.loader-overlay {
    position: fixed;
    inset: 0;
    background: rgba(255,255,255,0.7);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
    display: none;
}
.loader {
    border: 5px solid #f3f3f3;
    border-top: 5px solid var(--accent);
    border-radius: 50%;
    width: 50px;
    height: 50px;
    animation: spin 1s linear infinite;
}
@keyframes spin {
    0% { transform: rotate(0deg);}
    100% { transform: rotate(360deg);}
}
.toast {
    position: fixed;
    bottom: 30px;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(0,0,0,0.85);
    color: #fff;
    padding: 12px 20px;
    border-radius: 20px;
    opacity: 0;
    font-weight: 600;
    transition: all 0.4s ease;
    z-index: 10000;
}
.toast.show {
    opacity: 1;
    bottom: 50px;
}
</style>
</head>
<body>

<div class="card">
    <h2>Forgot Password</h2>

    <!-- Success Message -->
    @if (session('success'))
        <div class="message-box success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="message-box error">
            @foreach ($errors->all() as $error)
                <div><i class="fas fa-exclamation-circle"></i> {{ $error }}</div>
            @endforeach
        </div>
    @endif

    <!-- Original hidden message boxes (keep for JavaScript) -->
    <div id="errorBox" class="message-box error hidden"></div>
    <div id="successBox" class="message-box success hidden"></div>

    <form method="POST" action="{{ route('otp.request') }}" onsubmit="showLoader()">
        @csrf
        <input type="text" name="login" placeholder="Email or Phone" value="{{ old('login') }}" required>
        <br>
        <br>
        <button type="submit" class="btn"><i class="fas fa-paper-plane"></i> Send OTP</button>
    </form>

    <div style="text-align:center; margin-top:15px;">
        <a href="/login"><i class="fas fa-arrow-left"></i> Back to Login</a>
    </div>
</div>

<!-- Loader Spinner -->
<div class="loader-overlay" id="loader"><div class="loader"></div></div>
<div class="toast" id="toast"></div>

<script>
function showLoader(){
    document.getElementById('loader').style.display='flex';
}
function hideLoader(){
    document.getElementById('loader').style.display='none';
}
function showToast(msg,duration=3000){
    const toast=document.getElementById('toast');
    toast.textContent=msg;
    toast.classList.add('show');
    setTimeout(()=>toast.classList.remove('show'),duration);
}

// Auto-hide messages after 5 seconds
setTimeout(() => {
    const messages = document.querySelectorAll('.message-box');
    messages.forEach(msg => {
        if (!msg.classList.contains('hidden')) {
            msg.style.display = 'none';
        }
    });
}, 5000);
</script>
</body>
</html>