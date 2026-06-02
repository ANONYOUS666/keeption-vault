<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Sign In - Keeption Vault</title>
<link rel="icon" type="image/png" href="/favicon.png"/>
<link href="https://fonts.googleapis.com/css2?family=Clash+Display:wght@700&family=Epilogue:wght@300;400;500&display=swap" rel="stylesheet"/>
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
<style>
:root{--bg:#04060a;--surface:#0c0f16;--border:rgba(255,255,255,0.07);--accent:#00f5a0;--text:#eef0f6;--muted:#636b7d;}
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box;}
body{font-family:Epilogue,sans-serif;background:var(--bg);color:var(--text);min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:40px 24px;}
.auth-wrap{width:100%;max-width:420px;}
.auth-card{background:var(--surface);border:1px solid var(--border);border-radius:20px;padding:40px;}
.auth-card h1{font-family:Clash Display,sans-serif;font-size:1.8rem;font-weight:700;letter-spacing:-1px;margin-bottom:6px;}
.auth-sub{color:var(--muted);font-size:0.88rem;margin-bottom:32px;}
.form-group{margin-bottom:18px;}
.form-group label{display:block;font-size:0.8rem;color:var(--muted);margin-bottom:7px;}
.form-group input{width:100%;background:rgba(255,255,255,0.04);border:1px solid var(--border);border-radius:10px;padding:12px 16px;color:var(--text);font-size:0.9rem;outline:none;}
.form-group input::placeholder{color:var(--muted);}
.form-row{display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;}
.checkbox-label{display:flex;align-items:center;gap:8px;font-size:0.82rem;color:var(--muted);cursor:pointer;}
.forgot-link{font-size:0.82rem;color:var(--muted);text-decoration:none;}
.forgot-link:hover{color:#00f5a0;}
.btn-submit{width:100%;padding:13px;background:#00f5a0;border:none;border-radius:10px;color:#04060a;font-family:Clash Display,sans-serif;font-weight:700;font-size:0.95rem;cursor:pointer;margin-bottom:24px;}
.auth-switch{text-align:center;font-size:0.85rem;color:var(--muted);}
.auth-switch a{color:#00f5a0;text-decoration:none;}
.auth-note{text-align:center;font-size:0.78rem;color:var(--muted);margin-top:20px;padding-top:20px;border-top:1px solid var(--border);}
.top-nav{position:fixed;top:0;left:0;right:0;padding:16px 40px;display:flex;align-items:center;gap:12px;background:rgba(4,6,10,0.85);backdrop-filter:blur(20px);border-bottom:1px solid rgba(255,255,255,0.07);z-index:100;text-decoration:none;}
.top-nav img{width:38px;height:38px;border-radius:10px;object-fit:contain;background:#fff;padding:3px;}
.top-nav-text{font-family:Clash Display,sans-serif;font-weight:700;font-size:1.2rem;color:#eef0f6;}
.top-nav-text em{color:#00f5a0;font-style:normal;}
.divider{display:flex;align-items:center;gap:12px;margin:0 0 16px;}
.divider::before,.divider::after{content:'';flex:1;height:1px;background:var(--border);}
.divider span{font-size:0.78rem;color:var(--muted);}
.social-btns{display:flex;gap:12px;margin-bottom:24px;}
.btn-social{flex:1;display:flex;align-items:center;justify-content:center;gap:8px;background:rgba(255,255,255,0.04);border:1px solid var(--border);border-radius:10px;padding:11px;color:var(--muted);font-size:0.8rem;cursor:default;}
</style>
</head>
<body>
<a href="/" class="top-nav"><img src="/favicon.png" alt="logo"/><span class="top-nav-text">Keeption<em> Vault</em></span></a>
<div class="auth-wrap" style="margin-top:80px;">
  <div class="auth-card">
    <h1>Welcome back</h1>
    <p class="auth-sub">Sign in to your encrypted vault</p>
    <form action="/login" method="POST">
      @csrf
      @if($errors->any())
      <div style="background:rgba(255,80,80,0.1);border:1px solid rgba(255,80,80,0.3);border-radius:10px;padding:12px 16px;margin-bottom:18px;font-size:0.85rem;color:#ff6b6b;">{{ $errors->first() }}</div>
      @endif
      @if(session('success'))
      <div style="background:rgba(0,245,160,0.1);border:1px solid rgba(0,245,160,0.3);border-radius:10px;padding:12px 16px;margin-bottom:18px;font-size:0.85rem;color:#00f5a0;">{{ session('success') }}</div>
      @endif
      <div class="form-group">
        <label for="email">Email address</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="you@example.com"/>
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <div style="position:relative;">
          <input type="password" id="password" name="password" placeholder="Password" style="padding-right:40px;"/>
          <button type="button" onclick="togglePassword(this, 'password')" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--muted);cursor:pointer;padding:4px;display:flex;outline:none;">
            <i data-lucide="eye" style="width:18px;height:18px;"></i>
          </button>
        </div>
      </div>
      <div class="form-row">
        <label class="checkbox-label"><input type="checkbox" name="remember"/> Remember this device</label>
        <a href="/forgot-password" class="forgot-link">Forgot password?</a>
      </div>
      <button type="submit" class="btn-submit">Sign In</button>
    </form>
    <div style="display:flex;gap:10px;margin-bottom:20px;">
      <div style="flex:1;display:flex;align-items:center;gap:8px;background:rgba(0,245,160,0.05);border:1px solid rgba(0,245,160,0.15);border-radius:10px;padding:10px 12px;font-size:0.78rem;color:var(--muted);">
        <i data-lucide="shield-check" style="width:14px;height:14px;color:#00f5a0;flex-shrink:0;"></i> End-to-end encrypted
      </div>
      <div style="flex:1;display:flex;align-items:center;gap:8px;background:rgba(0,245,160,0.05);border:1px solid rgba(0,245,160,0.15);border-radius:10px;padding:10px 12px;font-size:0.78rem;color:var(--muted);">
        <i data-lucide="eye-off" style="width:14px;height:14px;color:#00f5a0;flex-shrink:0;"></i> Zero knowledge
      </div>
    </div>
    <div class="auth-switch">No account? <a href="/register">Start free</a></div>
    <div class="auth-note">Your files are encrypted. Even we cannot see them.</div>
  </div>
</div>
<script>
  lucide.createIcons();
  function togglePassword(btn, inputId) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
      input.type = 'text';
      icon.setAttribute('data-lucide', 'eye-off');
    } else {
      input.type = 'password';
      icon.setAttribute('data-lucide', 'eye');
    }
    lucide.createIcons();
  }
</script>

</body>
</html>
