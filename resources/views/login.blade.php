<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - SnipSnap</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<style>
:root{--accent:#fe2c55;--page-bg:#f7f7f8;--card-bg:#fff;}
body{margin:0;padding:0;font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,"Helvetica Neue",Arial;background:var(--page-bg);display:flex;justify-content:center;align-items:center;min-height:100vh;}
.card{background:var(--card-bg);padding:40px 30px;border-radius:20px;box-shadow:0 18px 50px rgba(2,6,23,0.06);width:100%;max-width:450px;display:flex;flex-direction:column;gap:15px;}
.input-group{position:relative;width:100%;}
input[type=text],input[type=email],input[type=password],input[type=tel]{padding:12px 15px;border:1px solid #ddd;border-radius:10px;font-size:14px;width:100%;box-sizing:border-box;}
input:focus{outline:none;border-color:var(--accent);box-shadow:0 0 0 1px var(--accent);}
.eye-toggle{position:absolute;top:50%;right:12px;transform:translateY(-50%);cursor:pointer;color:#888;}
.btn{width:100%;padding:12px 15px;background:var(--accent);color:#fff;border:none;border-radius:10px;font-weight:600;cursor:pointer;transition:background 0.2s;}
.btn:hover{background:#e6284f;}
.message-box{padding:12px;border-radius:8px;font-size:14px;margin-bottom:15px;}
.success{background:#e6ffed;border:1px solid #38a169;color:#276749;}
.error{background:#fff5f5;border:1px solid #e53e3e;color:#9b2c2c;}
a{color:var(--accent);text-decoration:none;font-size:13px;}
a:hover{opacity:0.8;}
.loader-overlay{position:fixed;inset:0;background:rgba(255,255,255,0.7);display:flex;justify-content:center;align-items:center;z-index:9999;display:none;}
.loader{border:5px solid #f3f3f3;border-top:5px solid var(--accent);border-radius:50%;width:50px;height:50px;animation:spin 1s linear infinite;}
@keyframes spin{0%{transform:rotate(0deg);}100%{transform:rotate(360deg);}}
.toast{position:fixed;bottom:30px;left:50%;transform:translateX(-50%);background:rgba(0,0,0,0.85);color:#fff;padding:12px 20px;border-radius:20px;opacity:0;font-weight:600;transition:all 0.4s ease;z-index:10000;}
.toast.show{opacity:1;bottom:50px;}
h2{margin-left:40%;}


</style>
</head>
<body>

<div class="card">
    <h2>Login</h2>
<center>
    @if(session('success'))
        <div class="message-box success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="message-box error">
            @foreach($errors->all() as $error) {{ $error }}<br>@endforeach
        </div>
    @endif
    </center>

    <form method="POST" action="{{ route('login.perform') }}" onsubmit="showLoader()">
        @csrf
        <input type="text" name="login" placeholder="Email or Phone" value="{{ old('login') }}" required>

        <div class="input-group" style="margin-top:15px;">
            <input type="password" id="login_password" name="password" placeholder="Password" required>
            <i class="fas fa-eye eye-toggle" onclick="togglePassword('login_password', this)"></i>
        </div>

        <br>
        <label style="font-size:13px; margin-top:5px;">
            <input type="checkbox" name="remember"> Remember me
        </label>

        <button type="submit" class="btn" style="margin-top:15px;">Login</button>
    </form>

    <div style="text-align:center; margin-top:15px;">
        <a href="{{ route('signup.view') }}">Sign up</a> | 
        <a href="{{ route('password.request') }}">Forgot Password?</a>
    </div>
</div>



<div class="loader-overlay" id="loader"><div class="loader"></div></div>
<div class="toast" id="toast"></div>

<script>
function showLoader(){document.getElementById('loader').style.display='flex';}
function hideLoader(){document.getElementById('loader').style.display='none';}
function showToast(msg){const toast=document.getElementById('toast');toast.textContent=msg;toast.classList.add('show');setTimeout(()=>toast.classList.remove('show'),3000);}
document.addEventListener('DOMContentLoaded',()=>{const successMsg=<?php echo json_encode(session('success')); ?>;if(successMsg)showToast(successMsg);});
function togglePassword(fieldId, icon){
    const field=document.getElementById(fieldId);
    if(field.type==="password"){field.type="text";icon.classList.remove("fa-eye");icon.classList.add("fa-eye-slash");}
    else{field.type="password";icon.classList.remove("fa-eye-slash");icon.classList.add("fa-eye");}
}
</script>
</body>
</html>