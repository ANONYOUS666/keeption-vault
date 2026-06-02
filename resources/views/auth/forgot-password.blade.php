<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Forgot Password — Keeption Vault</title>
<link rel="icon" type="image/png" href="/favicon.png"/>
<link href="https://fonts.googleapis.com/css2?family=Clash+Display:wght@700&family=Epilogue:wght@300;400;500&display=swap" rel="stylesheet"/>
<style>
:root{--bg:#04060a;--surface:#0c0f16;--border:rgba(255,255,255,0.07);--accent:#00f5a0;--text:#eef0f6;--muted:#636b7d;--red:#ff4444;}
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box;}
body{font-family:'Epilogue',sans-serif;background:var(--bg);color:var(--text);min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:20px;}
.top-nav{position:fixed;top:0;left:0;right:0;padding:16px 40px;display:flex;align-items:center;gap:12px;background:rgba(4,6,10,.85);backdrop-filter:blur(20px);border-bottom:1px solid rgba(255,255,255,.07);z-index:100;text-decoration:none;}
.top-nav img{width:32px;height:32px;border-radius:8px;background:#fff;padding:2px;object-fit:contain;}
.top-nav-text{font-family:'Clash Display',sans-serif;font-weight:700;font-size:1.1rem;}
.top-nav-text em{color:var(--accent);font-style:normal;}
.card{background:var(--surface);border:1px solid var(--border);border-radius:20px;padding:40px;width:100%;max-width:420px;margin-top:80px;}
.icon{font-size:48px;text-align:center;margin-bottom:16px;}
h1{font-family:'Clash Display',sans-serif;font-size:1.6rem;font-weight:700;letter-spacing:-1px;margin-bottom:6px;text-align:center;}
.sub{color:var(--muted);font-size:.88rem;margin-bottom:28px;text-align:center;line-height:1.6;}
.form-group{margin-bottom:18px;}
.form-group label{display:block;font-size:.78rem;color:var(--muted);margin-bottom:7px;text-transform:uppercase;letter-spacing:.04em;}
.form-group input{width:100%;background:rgba(255,255,255,.04);border:1px solid var(--border);border-radius:10px;padding:13px 16px;color:var(--text);font-size:.9rem;outline:none;font-family:'Epilogue',sans-serif;transition:border-color .2s;}
.form-group input:focus{border-color:rgba(0,245,160,.4);background:rgba(0,245,160,.02);}
.form-group input::placeholder{color:var(--muted);}
.btn{width:100%;padding:13px;background:var(--accent);border:none;border-radius:10px;color:#04060a;font-family:'Clash Display',sans-serif;font-weight:700;font-size:.95rem;cursor:pointer;margin-bottom:16px;transition:opacity .2s;}
.btn:hover{opacity:.88;}
.back-link{text-align:center;font-size:.83rem;color:var(--muted);}
.back-link a{color:var(--accent);text-decoration:none;}
.alert-error{background:rgba(255,68,68,.1);border:1px solid rgba(255,68,68,.3);border-radius:10px;padding:12px 16px;margin-bottom:18px;font-size:.85rem;color:var(--red);}
.alert-success{background:rgba(0,245,160,.1);border:1px solid rgba(0,245,160,.3);border-radius:10px;padding:12px 16px;margin-bottom:18px;font-size:.85rem;color:var(--accent);}
@media(max-width:480px){.card{padding:28px 20px;}}
</style>
</head>
<body>
<a href="/" class="top-nav">
  <img src="/favicon.png" alt="logo"/>
  <span class="top-nav-text">Keeption<em> Vault</em></span>
</a>

<div class="card">
  <div class="icon">🔑</div>
  <h1>Forgot password?</h1>
  <p class="sub">Enter your email and we'll send you a 6-digit verification code to reset your password.</p>

  @if($errors->any())
  <div class="alert-error">{{ $errors->first() }}</div>
  @endif

  @if(session('success'))
  <div class="alert-success">{{ session('success') }}</div>
  @endif

  <form method="POST" action="/forgot-password">
    @csrf
    <div class="form-group">
      <label>Email address</label>
      <input type="email" name="email" value="{{ old('email', request('email')) }}" placeholder="you@example.com" required autofocus/>
    </div>
    <button type="submit" class="btn">Send Verification Code</button>
  </form>

  <div class="back-link"><a href="/login">← Back to Sign In</a></div>
</div>
</body>
</html>
