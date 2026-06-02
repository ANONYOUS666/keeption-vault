<!DOCTYPE html>
<html lang="en" prefix="og: https://ogp.me/ns#">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<meta name="csrf-token" content="{{ csrf_token() }}"/>

{{-- ── Dynamic page title ──────────────────────────────────────────────── --}}
@if(!empty($og['title']))
  <title>{{ $og['title'] }} — Keeption Vault</title>
@else
  <title>Secure File Share — Keeption Vault</title>
@endif

{{-- ── Open Graph (WhatsApp, Facebook, LinkedIn, iMessage, Slack …) ─────── --}}
<meta property="og:site_name"   content="Keeption Vault"/>
<meta property="og:type"        content="website"/>
@if(!empty($og['url']))
  <meta property="og:url"       content="{{ $og['url'] }}"/>
@else
  <meta property="og:url"       content="{{ url()->current() }}"/>
@endif
@if(!empty($og['title']))
  <meta property="og:title"     content="{{ $og['title'] }}"/>
  <meta property="og:description" content="{{ $og['description'] }}"/>
  <meta property="og:image"     content="{{ $og['image'] }}"/>
  <meta property="og:image:width"  content="1200"/>
  <meta property="og:image:height" content="630"/>
  <meta property="og:image:alt" content="{{ $og['title'] }}"/>
@else
  <meta property="og:title"     content="Secure File Share — Keeption Vault"/>
  <meta property="og:description" content="Someone shared files with you securely via Keeption Vault. No account required."/>
  <meta property="og:image"     content="{{ url('/og-file.png') }}"/>
  <meta property="og:image:width"  content="1200"/>
  <meta property="og:image:height" content="630"/>
@endif

{{-- ── Twitter / X Card ────────────────────────────────────────────────── --}}
<meta name="twitter:card"        content="summary_large_image"/>
<meta name="twitter:site"        content="@keeptionvault"/>
@if(!empty($og['title']))
  <meta name="twitter:title"     content="{{ $og['title'] }}"/>
  <meta name="twitter:description" content="{{ $og['description'] }}"/>
  <meta name="twitter:image"     content="{{ $og['image'] }}"/>
@else
  <meta name="twitter:title"     content="Secure File Share — Keeption Vault"/>
  <meta name="twitter:description" content="Files shared securely via Keeption Vault."/>
  <meta name="twitter:image"     content="{{ url('/og-file.png') }}"/>
@endif

{{-- ── Standard SEO ─────────────────────────────────────────────────────── --}}
@if(!empty($og['description']))
  <meta name="description" content="{{ $og['description'] }}"/>
@else
  <meta name="description" content="View and download files shared securely via Keeption Vault."/>
@endif
<meta name="robots" content="noindex, nofollow"/>

<link rel="icon" type="image/png" href="/favicon.png"/>
<link href="https://fonts.googleapis.com/css2?family=Clash+Display:wght@700&family=Epilogue:wght@300;400;500&display=swap" rel="stylesheet"/>
<style>
:root{--bg:#04060a;--surface:#0c0f16;--border:rgba(255,255,255,0.08);--accent:#00f5a0;--text:#eef0f6;--muted:#636b7d;--red:#ff4444;}
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box;}
body{font-family:'Epilogue',sans-serif;background:var(--bg);color:var(--text);min-height:100vh;display:flex;flex-direction:column;align-items:center;padding:0 20px 60px;}
.watermark-overlay{position:fixed;top:0;left:0;right:0;bottom:0;pointer-events:none;z-index:9999;display:none;opacity:0.04;overflow:hidden;user-select:none;}
.watermark-text{font-size:1.5rem;font-weight:700;font-family:'Clash Display',sans-serif;white-space:nowrap;transform:rotate(-35deg);display:flex;flex-wrap:wrap;gap:40px;justify-content:center;align-items:center;width:200vw;height:200vh;margin-left:-50vw;margin-top:-50vh;color:#fff;}
.top-nav{width:100%;max-width:560px;padding:20px 0;display:flex;align-items:center;gap:10px;margin-bottom:20px;}
.top-nav img{width:32px;height:32px;border-radius:8px;background:#fff;padding:2px;object-fit:contain;}
.top-nav-text{font-family:'Clash Display',sans-serif;font-weight:700;font-size:1.1rem;}
.top-nav-text em{color:var(--accent);font-style:normal;}
.card{background:var(--surface);border:1px solid var(--border);border-radius:20px;padding:36px;width:100%;max-width:480px;}
.headline{font-family:'Clash Display',sans-serif;font-size:1.5rem;font-weight:700;text-align:center;margin-bottom:8px;}
.subline{color:var(--muted);font-size:.88rem;text-align:center;margin-bottom:32px;line-height:1.6;}
.code-row{display:flex;gap:8px;justify-content:center;margin-bottom:24px;}
.code-box{width:52px;height:60px;background:rgba(255,255,255,.04);border:2px solid var(--border);border-radius:12px;font-family:'Clash Display',sans-serif;font-size:1.6rem;font-weight:700;text-align:center;color:var(--text);outline:none;text-transform:uppercase;transition:border-color .2s;}
.code-box:focus{border-color:var(--accent);}
.code-box.filled{border-color:rgba(0,245,160,.4);}
.pw-wrap{margin-bottom:20px;display:none;}
.pw-label{font-size:.78rem;color:var(--muted);margin-bottom:7px;display:block;}
.pw-input-wrap{position:relative;}
.pw-input{width:100%;background:rgba(255,255,255,.04);border:1px solid var(--border);border-radius:10px;padding:12px 44px 12px 16px;color:var(--text);font-size:.9rem;outline:none;font-family:'Epilogue',sans-serif;}
.pw-input:focus{border-color:rgba(0,245,160,.4);}
.pw-toggle{position:absolute;right:14px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--muted);cursor:pointer;font-size:16px;}
.btn-access{width:100%;padding:14px;background:var(--accent);border:none;border-radius:12px;color:#04060a;font-family:'Clash Display',sans-serif;font-weight:700;font-size:1rem;cursor:pointer;transition:opacity .2s;margin-bottom:16px;}
.btn-access:hover{opacity:.88;}
.btn-access:disabled{opacity:.4;cursor:not-allowed;}
.alert{border-radius:10px;padding:12px 16px;margin-bottom:16px;font-size:.85rem;display:none;}
.alert-error{background:rgba(255,68,68,.1);border:1px solid rgba(255,68,68,.3);color:var(--red);}
.files-section{display:none;margin-top:8px;}
.sender-line{text-align:center;font-size:.88rem;color:var(--muted);margin-bottom:16px;}
.sender-line strong{color:var(--text);}
.expiry-bar{display:flex;align-items:center;justify-content:center;gap:6px;font-size:.75rem;color:var(--muted);margin-bottom:20px;}
.expiry-dot{width:6px;height:6px;border-radius:50%;background:var(--accent);animation:pulse 2s infinite;}
@keyframes pulse{0%,100%{opacity:1}50%{opacity:.3}}
.files-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:12px;margin-bottom:20px;}
.file-tile{background:rgba(255,255,255,.03);border:1px solid var(--border);border-radius:12px;padding:16px 12px;text-align:center;transition:border-color .2s;}
.file-tile:hover{border-color:rgba(0,245,160,.3);}
.file-ico{font-size:32px;margin-bottom:8px;}
.file-name{font-size:.75rem;font-weight:500;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;margin-bottom:4px;}
.file-size{font-size:.68rem;color:var(--muted);margin-bottom:10px;}
.btn-dl{width:100%;padding:7px;background:var(--accent);border:none;border-radius:7px;color:#04060a;font-family:'Clash Display',sans-serif;font-weight:700;font-size:.72rem;cursor:pointer;}
.btn-dl:hover{opacity:.85;}
.btn-dl-disabled{width:100%;padding:7px;background:var(--surface);border:1px solid var(--border);border-radius:7px;color:var(--muted);font-size:.72rem;cursor:not-allowed;}
.conversion-banner{margin-top:28px;background:rgba(0,245,160,.05);border:1px solid rgba(0,245,160,.15);border-radius:14px;padding:20px;text-align:center;width:100%;max-width:480px;}
.cb-title{font-family:'Clash Display',sans-serif;font-size:.95rem;font-weight:700;margin-bottom:6px;}
.cb-sub{font-size:.8rem;color:var(--muted);margin-bottom:14px;}
.cb-btn{display:inline-block;padding:9px 24px;background:var(--accent);border-radius:8px;color:#04060a;font-family:'Clash Display',sans-serif;font-weight:700;font-size:.82rem;text-decoration:none;}
</style>
</head>
<body>
<div class="top-nav">
  <a href="/" style="display:flex;align-items:center;gap:10px;text-decoration:none;color:inherit;">
    <img src="/favicon.png" alt="logo"/>
    <span class="top-nav-text">Keeption<em> Vault</em></span>
  </a>
</div>

<div class="card" id="accessCard">
  <div class="headline">Secure File Share</div>
  <p class="subline">Someone shared files with you securely.<br>Enter the 6-character share code to access them.</p>

  <div class="alert alert-error" id="alertBox"></div>

  <div class="code-row" id="codeRow">
    <input class="code-box" maxlength="1" id="c0" autocomplete="off"/>
    <input class="code-box" maxlength="1" id="c1" autocomplete="off"/>
    <input class="code-box" maxlength="1" id="c2" autocomplete="off"/>
    <input class="code-box" maxlength="1" id="c3" autocomplete="off"/>
    <input class="code-box" maxlength="1" id="c4" autocomplete="off"/>
    <input class="code-box" maxlength="1" id="c5" autocomplete="off"/>
  </div>

  <div class="pw-wrap" id="pwWrap">
    <label class="pw-label">Password (required by sender)</label>
    <div class="pw-input-wrap">
      <input class="pw-input" type="password" id="pwInput" placeholder="Enter password"/>
      <button class="pw-toggle" type="button" onclick="togglePw()">👁</button>
    </div>
  </div>

  <button class="btn-access" id="btnAccess" onclick="accessCode()">Access Files</button>

  <div class="files-section" id="filesSection">
    <div class="sender-line" id="senderLine"></div>
    <div class="expiry-bar"><span class="expiry-dot"></span><span id="expiryText"></span></div>
    <div class="files-grid" id="filesGrid"></div>
  </div>
</div>

<div class="conversion-banner">
  <div class="cb-title">Store and share your own files securely</div>
  <div class="cb-sub">Get 5 GB free on Keeption Vault — no credit card required</div>
  <a href="/register" class="cb-btn">Sign Up Free</a>
</div>

<script>
const PREFILL = '{{ $prefill ?? "" }}';
function escHtml(s) {
  return (s || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
const boxes = [0,1,2,3,4,5].map(i => document.getElementById('c' + i));
let needsPassword = false;

// Prefill from URL
if (PREFILL.length >= 6) {
  PREFILL.substring(0,6).split('').forEach((ch, i) => {
    if (boxes[i]) { boxes[i].value = ch.toUpperCase(); boxes[i].classList.add('filled'); }
  });
}

// Code box navigation
boxes.forEach((box, i) => {
  box.addEventListener('input', () => {
    box.value = box.value.toUpperCase();
    box.classList.toggle('filled', box.value.length > 0);
    if (box.value && i < 5) boxes[i+1].focus();
  });
  box.addEventListener('keydown', e => {
    if (e.key === 'Backspace' && !box.value && i > 0) boxes[i-1].focus();
  });
  box.addEventListener('paste', e => {
    e.preventDefault();
    const text = (e.clipboardData || window.clipboardData).getData('text')
      .replace(/[^A-Za-z0-9]/g,'').toUpperCase().substring(0,6);
    text.split('').forEach((ch, j) => {
      if (boxes[j]) { boxes[j].value = ch; boxes[j].classList.add('filled'); }
    });
    if (boxes[Math.min(text.length, 5)]) boxes[Math.min(text.length, 5)].focus();
  });
});

function getCode() {
  return boxes.map(b => b.value).join('').toUpperCase();
}

function showAlert(msg) {
  const el = document.getElementById('alertBox');
  el.textContent = msg;
  el.style.display = 'block';
}

function hideAlert() {
  document.getElementById('alertBox').style.display = 'none';
}

function togglePw() {
  const inp = document.getElementById('pwInput');
  inp.type = inp.type === 'password' ? 'text' : 'password';
}

function getFileIco(name) {
  const ext = name.split('.').pop().toLowerCase();
  if (['jpg','jpeg','png','gif','webp'].includes(ext)) return '\uD83D\uDDBC\uFE0F';
  if (['mp4','mov','avi','mkv'].includes(ext)) return '\uD83C\uDFAC';
  if (['mp3','wav','flac','aac'].includes(ext)) return '\uD83C\uDFB5';
  if (['pdf'].includes(ext)) return '\uD83D\uDCC4';
  if (['doc','docx'].includes(ext)) return '\uD83D\uDCDD';
  if (['xls','xlsx'].includes(ext)) return '\uD83D\uDCCA';
  if (['zip','rar','7z'].includes(ext)) return '\uD83D\uDDDC\uFE0F';
  return '\uD83D\uDCC1';
}

function formatBytes(b) {
  if (b < 1024) return b + ' B';
  if (b < 1024*1024) return (b/1024).toFixed(1) + ' KB';
  if (b < 1024*1024*1024) return (b/1024/1024).toFixed(1) + ' MB';
  return (b/1024/1024/1024).toFixed(2) + ' GB';
}

function formatExpiry(iso) {
  const d = new Date(iso);
  const diff = d - Date.now();
  if (diff <= 0) return 'Expired';
  const h = Math.floor(diff / 3600000);
  const m = Math.floor((diff % 3600000) / 60000);
  if (h > 24) return 'Expires in ' + Math.floor(h/24) + ' days';
  if (h > 0) return 'Expires in ' + h + 'h ' + m + 'm';
  return 'Expires in ' + m + ' minutes';
}

async function accessCode() {
  const code = getCode();
  if (code.length < 6) { showAlert('Please enter all 6 characters of the share code.'); return; }

  hideAlert();
  const btn = document.getElementById('btnAccess');
  btn.disabled = true;
  btn.textContent = 'Checking...';

  const pw = document.getElementById('pwInput').value;

  try {
    const res = await fetch('/share/access', {
      method: 'POST',
      headers: {'Content-Type':'application/json','X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]') ? document.querySelector('meta[name=csrf-token]').content : ''},
      body: JSON.stringify({ code: 'KV-' + code, password: pw || null })
    });
    const data = await res.json();

    if (!res.ok) {
      showAlert(data.error || 'Invalid code or password.');
      btn.disabled = false;
      btn.textContent = 'Access Files';
      if (data.error && data.error.includes('password')) {
        document.getElementById('pwWrap').style.display = 'block';
      }
      return;
    }

    // Show files
    document.getElementById('senderLine').innerHTML = '<strong>' + escHtml(data.sender) + '</strong> shared these files with you';
    document.getElementById('expiryText').textContent = formatExpiry(data.expires_at);

    const grid = document.getElementById('filesGrid');
    grid.innerHTML = (data.files || []).map(f => {
      const dlBtn = data.allow_download
        ? '<a href="/share/KV-' + code + '/download/' + f.id + '" style="text-decoration:none;display:block;"><button class="btn-dl">Download</button></a>'
        : '<div class="btn-dl-disabled">Download disabled</div>';
      return '<div class="file-tile">'
        + '<div class="file-ico">' + getFileIco(f.name) + '</div>'
        + '<div class="file-name" title="' + escHtml(f.name) + '">' + escHtml(f.name) + '</div>'
        + '<div class="file-size">' + (f.size ? formatBytes(f.size) : '') + '</div>'
        + dlBtn
        + '</div>';
    }).join('');

    document.getElementById('filesSection').style.display = 'block';
    if (data.watermarked) {
      document.getElementById('watermarkOverlay').style.display = 'block';
    }
    document.getElementById('codeRow').style.display = 'none';
    document.getElementById('pwWrap').style.display = 'none';
    btn.style.display = 'none';

  } catch(e) {
    showAlert('Something went wrong. Please try again.');
    btn.disabled = false;
    btn.textContent = 'Access Files';
  }
}

// Auto-focus first empty box
const firstEmpty = boxes.find(b => !b.value);
if (firstEmpty) firstEmpty.focus();
</script>
<div id="watermarkOverlay" class="watermark-overlay">
  <div class="watermark-text">
    {{ str_repeat(request()->ip() . ' • ' . (session('email') ?? request()->ip()) . ' • ', 100) }}
  </div>
</div>
</body>
</html>
