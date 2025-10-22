<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign Up - SnipSnap</title>
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
h2{margin-left:40%;}

</style>
</head>
<body>

<div class="card">
    <h2>Sign Up</h2>
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
    <form method="POST" action="{{ route('signup.perform') }}">
    @csrf
    <input type="text" name="name" placeholder="Full Name" value="{{ old('name') }}" required>
    <br><br>
    <input type="text" name="username" placeholder="Username" value="{{ old('username') }}" required>
    <br><br>
    <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
    <br><br>
    <input type="tel" name="phone_number" placeholder="Phone Number" value="{{ old('phone_number') }}">
    <br><br>
    <div class="input-group">
        <input type="password" id="signup_password" name="password" placeholder="Password" required>
        <i class="fas fa-eye eye-toggle" onclick="togglePassword('signup_password', this)"></i>
    </div>
    <br>
    <div class="input-group">
        <input type="password" id="signup_confirm_password" name="password_confirmation" placeholder="Confirm Password" required>
        <i class="fas fa-eye eye-toggle" onclick="togglePassword('signup_confirm_password', this)"></i>
    </div>
    <br>
    <button type="submit" class="btn">Sign Up</button>
</form>


    <div style="text-align:center; margin-top:15px;">
        <a href="{{ route('login') }}">Already have an account? Login</a>
    </div>
</div>

<script>
function togglePassword(fieldId, icon){
    const field=document.getElementById(fieldId);
    if(field.type==="password"){field.type="text";icon.classList.remove("fa-eye");icon.classList.add("fa-eye-slash");}
    else{field.type="password";icon.classList.remove("fa-eye-slash");icon.classList.add("fa-eye");}
}
</script>
</body>
</html>
