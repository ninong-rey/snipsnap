<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reset Password - SnipSnap</title>
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
input[type=text], input[type=password] {
    padding: 12px 15px;
    border: 1px solid #ddd;
    border-radius: 10px;
    font-size: 14px;
    width: 100%;
    box-sizing: border-box;
}
.input-wrapper { position: relative; width: 100%; }
.input-wrapper .password-toggle {
    position: absolute;
    top: 50%;
    right: 10px;
    transform: translateY(-50%);
    cursor: pointer;
    background:none;
    border:none;
    font-size:16px;
    line-height:1;
    color:#888;
}
.btn {
    width: 100%;
    padding: 12px 15px;
    background: var(--accent);
    color: #fff;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
}
.btn:hover{ background:#e6284f; }
.message-box{
    padding:12px;
    border-radius:8px;
    font-size:14px;
    margin-bottom:15px;
    display:none;
}
.success{ background:#e6ffed; border:1px solid #38a169; color:#276749; }
.error{ background:#fff5f5; border:1px solid #e53e3e; color:#9b2c2c; }
a{ color:var(--accent); text-decoration:none; font-size:13px; }
a:hover{ opacity:0.8; }
.loader-overlay{ position:fixed; inset:0; background:rgba(255,255,255,0.7); display:flex; justify-content:center; align-items:center; z-index:9999; display:none; }
.loader{ border:5px solid #f3f3f3; border-top:5px solid var(--accent); border-radius:50%; width:50px; height:50px; animation:spin 1s linear infinite;}
@keyframes spin{0%{transform:rotate(0deg);}100%{transform:rotate(360deg);}}
.toast{position:fixed; bottom:30px; left:50%; transform:translateX(-50%); background:rgba(0,0,0,0.85); color:#fff; padding:12px 20px; border-radius:20px; opacity:0; font-weight:600; transition:all 0.4s ease; z-index:10000;}
.toast.show{ opacity:1; bottom:50px; }
h2{margin-left:32%;}
</style>
</head>
<body>

<div class="card">
    <h2>Reset Password</h2>
    <center>
    @if(session('error'))
        <div class="message-box error" style="display:block;">{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="message-box success" style="display:block;">{{ session('success') }}</div>
    @endif
    </center>
    <form method="POST" action="{{ route('otp.verify') }}">
        @csrf
        <input type="text" name="otp" placeholder="Enter OTP" required>
        <br>
        <br>
        <div class="input-wrapper">
            <input type="password" name="password" placeholder="New Password" id="password" required>
            <button type="button" class="password-toggle" id="togglePassword"><i class="fas fa-eye"></i></button>
        </div>
        <br>
        <div class="input-wrapper">
            <input type="password" name="password_confirmation" placeholder="Confirm Password" id="password_confirmation" required>
            <button type="button" class="password-toggle" id="togglePasswordConfirm"><i class="fas fa-eye"></i></button>
        </div>
        <br>
        <button type="submit" class="btn">Reset Password</button>
    </form>

    <div style="text-align:center; margin-top:15px;">
        <a href="/login">Back to Login</a>
    </div>
</div>

<div class="loader-overlay" id="loader"><div class="loader"></div></div>
<div class="toast" id="toast"></div>

<script>
function setupPasswordToggle(inputId,toggleId){
    const input=document.getElementById(inputId);
    const toggle=document.getElementById(toggleId);
    const icon=toggle.querySelector('i');
    toggle.addEventListener('click',()=>{
        const isPassword=input.type==='password';
        input.type=isPassword?'text':'password';
        icon.classList.toggle('fa-eye',!isPassword);
        icon.classList.toggle('fa-eye-slash',isPassword);
    });
}
document.addEventListener('DOMContentLoaded',()=>{
    setupPasswordToggle('password','togglePassword');
    setupPasswordToggle('password_confirmation','togglePasswordConfirm');
});
</script>
</body>
</html>
