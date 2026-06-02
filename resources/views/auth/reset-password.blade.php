<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Set New Password — Keeption Vault</title>
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
.form-group{margin-bottom:18px;position:relative;}
.form-group label{display:block;font-size:.78rem;color:var(--muted);margin-bottom:7px;text-transform:uppercase;letter-spacing:.04em;}
.form-group input{width:100%;background:rgba(255,255,255,.04);border:1px solid var(--border);border-radius:10px;padding:13px 44px 13px 16px;color:var(--text);font-size:.9rem;outline:none;font-family:'Epilogue',sans-serif;transition:border-color .2s;}
.form-group input:focus{border-color:rgba(0,245,160,.4);background:rgba(0,245,160,.02);}
.form-group input::placeholder{color:var(--muted);}
.pw-toggle{position:absolute;right:14px;bottom:13px;background:none;border:none;color:var(--muted);cursor:pointer;font-size:16px;line-height:1;}
.hint{font-size:.72rem;color:var(--muted);margin-top:5px;}
/* Strength bar */
.strength-bar{height:4px;border-radius:99px;background:rgba(255,255,255,.06);margin-top:8px;overflow:hidden;}
.strength-fill{height:100%;border-radius:99px;transition:width .3s,background .3s;}
.strength-label{font-size:.7rem;margin-top:4px;}
.btn{width:100%;padding:13px;background:var(--accent);border:none;border-radius:10px;color:#04060a;font-family:'Clash Display',sans-serif;font-weight:700;font-size:.95rem;cursor:pointer;margin-bottom:16px;transition:opacity .2s;}
.btn:hover{opacity:.88;}
.alert-error{background:rgba(255,68,68,.1);border:1px solid rgba(255,68,68,.3);border-radius:10px;padding:12px 16px;margin-bottom:18px;font-size:.85rem;color:var(--red);}
@media(max-width:480px){.card{padding:28px 20px;}}
</style>
</head>
<body>
<a href="/" class="top-nav">
  <img src="/favicon.png" alt="logo"/>
  <span class="top-nav-text">Keeption<em> Vault</em></span>
</a>

<div class="card">
  <div class="icon">🔒</div>
  <h1>Set new password</h1>
  <p class="sub">Choose a strong password for your vault. Minimum 12 characters.</p>

  @if($errors->any())
  <div class="alert-error">{{ $errors->first() }}</div>
  @endif

  <form method="POST" action="/reset-password">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}"/>
    <input type="hidden" name="email" value="{{ $email }}"/>

    <div class="form-group">
      <label>New password</label>
      <input type="password" name="password" id="pwInput" placeholder="Min. 12 characters" required autocomplete="new-password"/>
      <button type="button" class="pw-toggle" onclick="togglePw('pwInput',this)">👁</button>
      <div class="strength-bar"><div class="strength-fill" id="strengthFill" style="width:0%"></div></div>
      <div class="strength-label" id="strengthLabel" style="color:var(--muted)"></div>
    </div>

    <div class="form-group">
      <label>Confirm new password</label>
      <input type="password" name="password_confirmation" id="pwConfirm" placeholder="Repeat your password" required autocomplete="new-password"/>
      <button type="button" class="pw-toggle" onclick="togglePw('pwConfirm',this)">👁</button>
      <div class="hint" id="matchHint"></div>
    </div>

    <button type="submit" class="btn">Reset Password</button>
  </form>
</div>

<script>
function togglePw(id, btn) {
  const inp = document.getElementById(id);
  inp.type = inp.type === 'password' ? 'text' : 'password';
  btn.textContent = inp.type === 'password' ? '👁' : '🙈';
}

const pwInput   = document.getElementById('pwInput');
const pwConfirm = document.getElementById('pwConfirm');
const fill      = document.getElementById('strengthFill');
const label     = document.getElementById('strengthLabel');
const matchHint = document.getElementById('matchHint');

pwInput.addEventListener('input', () => {
  const v = pwInput.value;
  let score = 0;
  if (v.length >= 12) score++;
  if (/[A-Z]/.test(v)) score++;
  if (/[0-9]/.test(v)) score++;
  if (/[^A-Za-z0-9]/.test(v)) score++;

  const levels = [
    { w:'0%',   bg:'transparent', txt:'' },
    { w:'25%',  bg:'#ff4444',     txt:'Weak' },
    { w:'50%',  bg:'#f59e0b',     txt:'Fair' },
    { w:'75%',  bg:'#00d4ff',     txt:'Strong' },
    { w:'100%', bg:'#00f5a0',     txt:'Very Strong' },
  ];
  const l = levels[score] || levels[0];
  fill.style.width = l.w;
  fill.style.background = l.bg;
  label.textContent = l.txt;
  label.style.color = l.bg;
  checkMatch();
});

pwConfirm.addEventListener('input', checkMatch);

function checkMatch() {
  if (!pwConfirm.value) { matchHint.textContent = ''; return; }
  if (pwInput.value === pwConfirm.value) {
    matchHint.textContent = '✓ Passwords match';
    matchHint.style.color = '#00f5a0';
  } else {
    matchHint.textContent = '✗ Passwords do not match';
    matchHint.style.color = '#ff4444';
  }
}
</script>
</body>
</html>
