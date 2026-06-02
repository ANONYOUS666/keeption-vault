<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Create Account - Keeption Vault</title>
<link rel="icon" type="image/png" href="/favicon.png"/>
<link href="https://fonts.googleapis.com/css2?family=Clash+Display:wght@700&family=Epilogue:wght@300;400;500&display=swap" rel="stylesheet"/>
<style>
:root{--bg:#04060a;--surface:#0c0f16;--border:rgba(255,255,255,0.07);--accent:#00f5a0;--text:#eef0f6;--muted:#636b7d;}
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box;}
body{font-family:Epilogue,sans-serif;background:var(--bg);color:var(--text);min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:40px 24px;}
.auth-wrap{width:100%;max-width:440px;}
.auth-card{background:var(--surface);border:1px solid var(--border);border-radius:20px;padding:40px;}
.step-indicator{display:inline-flex;align-items:center;gap:8px;background:rgba(0,245,160,0.08);border:1px solid rgba(0,245,160,0.2);border-radius:99px;padding:5px 14px;font-size:0.75rem;color:#00f5a0;margin-bottom:24px;}
.step-dot{width:6px;height:6px;border-radius:50%;background:#00f5a0;}
.auth-card h1{font-family:Clash Display,sans-serif;font-size:1.8rem;font-weight:700;letter-spacing:-1px;margin-bottom:6px;}
.auth-sub{color:var(--muted);font-size:0.88rem;margin-bottom:28px;}
.badges-row{display:flex;gap:10px;flex-wrap:wrap;margin-bottom:16px;}
.badge-pill{display:inline-flex;align-items:center;gap:6px;font-size:0.75rem;border-radius:99px;padding:5px 12px;}
.badge-free{background:rgba(0,245,160,0.08);border:1px solid rgba(0,245,160,0.2);color:#00f5a0;}
.badge-pro{background:rgba(0,245,160,0.12);border:1px solid rgba(0,245,160,0.3);color:#00f5a0;}
.badge-teams{background:rgba(0,212,255,0.1);border:1px solid rgba(0,212,255,0.3);color:#00d4ff;}
.badge-nocc{background:rgba(255,255,255,0.05);border:1px solid var(--border);color:var(--muted);}
.plan-banner{border-radius:10px;padding:10px 14px;margin-bottom:20px;font-size:0.78rem;}
.plan-banner-pro{background:rgba(0,245,160,0.06);border:1px solid rgba(0,245,160,0.15);color:#00f5a0;}
.plan-banner-teams{background:rgba(0,212,255,0.06);border:1px solid rgba(0,212,255,0.15);color:#00d4ff;}
.form-group{margin-bottom:16px;}
.form-group label{display:block;font-size:0.8rem;color:var(--muted);margin-bottom:7px;}
.form-group input{width:100%;background:rgba(255,255,255,0.04);border:1px solid var(--border);border-radius:10px;padding:12px 16px;color:var(--text);font-size:0.9rem;outline:none;}
.form-group input::placeholder{color:var(--muted);}
.btn-submit{width:100%;padding:13px;background:#00f5a0;border:none;border-radius:10px;color:#04060a;font-family:Clash Display,sans-serif;font-weight:700;font-size:0.95rem;cursor:pointer;margin-top:8px;margin-bottom:20px;}
.divider{display:flex;align-items:center;gap:12px;margin-bottom:16px;}
.divider::before,.divider::after{content:'';flex:1;height:1px;background:var(--border);}
.divider span{font-size:0.78rem;color:var(--muted);}
.social-btns{display:flex;gap:12px;margin-bottom:24px;}
.btn-social{flex:1;display:flex;align-items:center;justify-content:center;gap:8px;background:rgba(255,255,255,0.04);border:1px solid var(--border);border-radius:10px;padding:11px;color:var(--text);font-size:0.85rem;cursor:pointer;}
.privacy-promise{text-align:center;font-size:0.8rem;color:var(--muted);margin-bottom:20px;}
.privacy-promise span{color:#00f5a0;}
.auth-switch{text-align:center;font-size:0.85rem;color:var(--muted);}
.auth-switch a{color:#00f5a0;text-decoration:none;}
.top-nav{position:fixed;top:0;left:0;right:0;padding:16px 40px;display:flex;align-items:center;gap:12px;background:rgba(4,6,10,0.85);backdrop-filter:blur(20px);border-bottom:1px solid rgba(255,255,255,0.07);z-index:100;text-decoration:none;}
.top-nav img{width:38px;height:38px;border-radius:10px;object-fit:contain;background:#fff;padding:3px;}
.top-nav-text{font-family:Clash Display,sans-serif;font-weight:700;font-size:1.2rem;color:#eef0f6;}
.top-nav-text em{color:#00f5a0;font-style:normal;}
</style>
</head>
<body>
@php
  $plan = isset($plan) ? $plan : request('plan', 'free');
  if (!in_array($plan, ['free','pro','teams'])) $plan = 'free';
  $planMeta = [
    'free'  => ['storage'=>'5 GB free storage',    'badgeClass'=>'badge-free',  'btn'=>'Create Free Account',  'banner'=>null],
    'pro'   => ['storage'=>'100 GB Pro storage',   'badgeClass'=>'badge-pro',   'btn'=>'Start Pro — $3/mo',    'banner'=>['class'=>'plan-banner-pro',   'text'=>'⚡ You\'re signing up for the <strong>Pro plan</strong> — $3/mo. No card needed now.']],
    'teams' => ['storage'=>'500 GB Teams storage', 'badgeClass'=>'badge-teams', 'btn'=>'Start Teams — $8/mo', 'banner'=>['class'=>'plan-banner-teams', 'text'=>'👥 You\'re signing up for the <strong>Teams plan</strong> — $8/mo per user. No card needed now.']],
  ];
  $meta = $planMeta[$plan];
@endphp
<a href="/" class="top-nav"><img src="/favicon.png" alt="logo"/><span class="top-nav-text">Keeption<em> Vault</em></span></a>
<div class="auth-wrap" style="margin-top:80px;">
  <div class="auth-card">
    <div class="step-indicator"><div class="step-dot"></div> Step 1 of 2</div>
    <h1>Create your account</h1>
    <p class="auth-sub">Your private vault is one minute away.</p>
    <div class="badges-row">
      <span class="badge-pill {{ $meta['badgeClass'] }}">{{ $meta['storage'] }}</span>
      <span class="badge-pill badge-nocc">No credit card required</span>
    </div>
    @if($meta['banner'])
    <div class="plan-banner {{ $meta['banner']['class'] }}">{!! $meta['banner']['text'] !!}</div>
    @endif
    <form action="/register" method="POST">
      @csrf
      <input type="hidden" name="plan" value="{{ $plan }}"/>
      @if($errors->any())
      <div style="background:rgba(255,80,80,0.1);border:1px solid rgba(255,80,80,0.3);border-radius:10px;padding:12px 16px;margin-bottom:18px;font-size:0.85rem;color:#ff6b6b;">{{ $errors->first() }}</div>
      @endif
      <div class="form-group">
        <label for="name">Full name</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Your name"/>
      </div>
      <div class="form-group">
        <label for="email">Email address</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="you@example.com"/>
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <div style="position:relative;">
          <input type="password" id="password" name="password" placeholder="Min. 12 characters" style="padding-right:40px;"/>
          <button type="button" onclick="togglePassword(this, 'password')" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--muted);cursor:pointer;padding:4px;display:flex;outline:none;">
            <i data-lucide="eye" style="width:18px;height:18px;"></i>
          </button>
        </div>
      </div>
      <button type="submit" class="btn-submit">{{ $meta['btn'] }}</button>
    </form>
    <div class="divider"><span>or continue with</span></div>
    <div class="social-btns">
      <button class="btn-social">Store info</button>
      <button class="btn-social">The safest way</button>
    </div>
    <div class="privacy-promise">&#128274; <span>We do not sell your data. Ever.</span></div>
    <div class="auth-switch">Already have an account? <a href="/login">Sign in</a></div>
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
