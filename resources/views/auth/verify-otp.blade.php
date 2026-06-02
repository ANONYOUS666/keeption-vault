<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Verify Code — Keeption Vault</title>
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
.sub{color:var(--muted);font-size:.88rem;margin-bottom:8px;text-align:center;line-height:1.6;}
.email-badge{display:inline-block;background:rgba(0,245,160,.08);border:1px solid rgba(0,245,160,.2);border-radius:99px;padding:4px 14px;font-size:.8rem;color:var(--accent);margin-bottom:28px;text-align:center;width:100%;}
/* OTP boxes */
.otp-row{display:flex;gap:10px;justify-content:center;margin-bottom:24px;}
.otp-box{width:52px;height:60px;background:rgba(255,255,255,.04);border:2px solid var(--border);border-radius:12px;font-family:'Clash Display',sans-serif;font-size:1.8rem;font-weight:700;text-align:center;color:var(--text);outline:none;transition:border-color .2s;}
.otp-box:focus{border-color:var(--accent);background:rgba(0,245,160,.04);}
.otp-box.filled{border-color:rgba(0,245,160,.4);}
.otp-box.error{border-color:var(--red);}
/* Hidden input for form submission */
.otp-hidden{display:none;}
.btn{width:100%;padding:13px;background:var(--accent);border:none;border-radius:10px;color:#04060a;font-family:'Clash Display',sans-serif;font-weight:700;font-size:.95rem;cursor:pointer;margin-bottom:16px;transition:opacity .2s;}
.btn:hover{opacity:.88;}
.btn:disabled{opacity:.4;cursor:not-allowed;}
.alert-error{background:rgba(255,68,68,.1);border:1px solid rgba(255,68,68,.3);border-radius:10px;padding:12px 16px;margin-bottom:18px;font-size:.85rem;color:var(--red);text-align:center;}
.alert-success{background:rgba(0,245,160,.1);border:1px solid rgba(0,245,160,.3);border-radius:10px;padding:12px 16px;margin-bottom:18px;font-size:.85rem;color:var(--accent);text-align:center;}
.resend-row{text-align:center;font-size:.83rem;color:var(--muted);}
.resend-row a{color:var(--accent);text-decoration:none;}
.timer{color:var(--accent);font-weight:600;}
@media(max-width:480px){
  .card{padding:28px 20px;}
  .otp-box{width:44px;height:54px;font-size:1.5rem;}
  .otp-row{gap:8px;}
}
</style>
</head>
<body>
<a href="/" class="top-nav">
  <img src="/favicon.png" alt="logo"/>
  <span class="top-nav-text">Keeption<em> Vault</em></span>
</a>

<div class="card">
  <div class="icon">📬</div>
  <h1>Check your email</h1>
  <p class="sub">We sent a 6-digit verification code to</p>
  <div class="email-badge">{{ $email }}</div>

  @if($errors->any())
  <div class="alert-error">{{ $errors->first() }}</div>
  @endif

  @if(session('success'))
  <div class="alert-success">{{ session('success') }}</div>
  @endif

  <form method="POST" action="/verify-otp" id="otpForm">
    @csrf
    <input type="hidden" name="email" value="{{ $email }}"/>
    <input type="hidden" name="otp" id="otpHidden"/>

    <div class="otp-row">
      <input class="otp-box" maxlength="1" id="o0" autocomplete="off" inputmode="numeric"/>
      <input class="otp-box" maxlength="1" id="o1" autocomplete="off" inputmode="numeric"/>
      <input class="otp-box" maxlength="1" id="o2" autocomplete="off" inputmode="numeric"/>
      <input class="otp-box" maxlength="1" id="o3" autocomplete="off" inputmode="numeric"/>
      <input class="otp-box" maxlength="1" id="o4" autocomplete="off" inputmode="numeric"/>
      <input class="otp-box" maxlength="1" id="o5" autocomplete="off" inputmode="numeric"/>
    </div>

    <button type="submit" class="btn" id="verifyBtn" disabled>Verify Code</button>
  </form>

  <div class="resend-row">
    Didn't receive it? &nbsp;
    <span id="timerWrap">Resend in <span class="timer" id="timer">60</span>s</span>
    <a href="/forgot-password?email={{ urlencode($email) }}" id="resendLink" style="display:none;">Resend code</a>
  </div>
</div>

<script>
const boxes = [0,1,2,3,4,5].map(i => document.getElementById('o' + i));
const hidden = document.getElementById('otpHidden');
const btn    = document.getElementById('verifyBtn');

// Auto-focus first box
boxes[0].focus();

boxes.forEach((box, i) => {
  box.addEventListener('input', () => {
    // Only allow digits
    box.value = box.value.replace(/\D/g, '').slice(-1);
    box.classList.toggle('filled', box.value.length > 0);
    if (box.value && i < 5) boxes[i+1].focus();
    updateHidden();
  });
  box.addEventListener('keydown', e => {
    if (e.key === 'Backspace' && !box.value && i > 0) {
      boxes[i-1].value = '';
      boxes[i-1].classList.remove('filled');
      boxes[i-1].focus();
      updateHidden();
    }
  });
  box.addEventListener('paste', e => {
    e.preventDefault();
    const text = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g,'').substring(0,6);
    text.split('').forEach((ch, j) => {
      if (boxes[j]) { boxes[j].value = ch; boxes[j].classList.add('filled'); }
    });
    if (boxes[Math.min(text.length, 5)]) boxes[Math.min(text.length, 5)].focus();
    updateHidden();
  });
});

function updateHidden() {
  const code = boxes.map(b => b.value).join('');
  hidden.value = code;
  btn.disabled = code.length < 6;
}

// Countdown timer
let secs = 60;
const timerEl   = document.getElementById('timer');
const timerWrap = document.getElementById('timerWrap');
const resendLink= document.getElementById('resendLink');
const countdown = setInterval(() => {
  secs--;
  timerEl.textContent = secs;
  if (secs <= 0) {
    clearInterval(countdown);
    timerWrap.style.display = 'none';
    resendLink.style.display = 'inline';
  }
}, 1000);

// Pre-fill if error (keep boxes filled)
@if(old('otp'))
const prev = '{{ old("otp") }}';
prev.split('').forEach((ch, i) => {
  if (boxes[i]) { boxes[i].value = ch; boxes[i].classList.add('filled'); if(ch) boxes[i].classList.add('error'); }
});
updateHidden();
@endif
</script>
</body>
</html>
