<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>File Types — Keeption Vault</title>
<link rel="icon" type="image/png" href="/favicon.png"/>
<link href="https://fonts.googleapis.com/css2?family=Clash+Display:wght@400;500;600;700&family=Epilogue:wght@300;400;500&display=swap" rel="stylesheet"/>
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
<style>
:root{--bg:#04060a;--surface:#0c0f16;--border:rgba(255,255,255,0.07);--accent:#00f5a0;--accent2:#00d4ff;--accent3:#ff6b6b;--gold:#ffd700;--text:#eef0f6;--muted:#636b7d;}
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box;}
html{scroll-behavior:smooth;}
body{font-family:'Epilogue',sans-serif;background:var(--bg);color:var(--text);overflow-x:hidden;cursor:none;}
.cursor{position:fixed;width:10px;height:10px;background:var(--accent);border-radius:50%;pointer-events:none;z-index:9999;transform:translate(-50%,-50%);mix-blend-mode:difference;}
.cursor-ring{position:fixed;width:36px;height:36px;border:1.5px solid rgba(0,245,160,0.5);border-radius:50%;pointer-events:none;z-index:9998;transform:translate(-50%,-50%);transition:width 0.3s,height 0.3s;}
nav{position:fixed;top:0;left:0;right:0;z-index:1000;display:flex;align-items:center;justify-content:space-between;padding:22px 60px;background:rgba(4,6,10,0.8);backdrop-filter:blur(24px);border-bottom:1px solid var(--border);}
.nav-logo{font-family:'Clash Display',sans-serif;font-weight:700;font-size:1.3rem;display:flex;align-items:center;gap:10px;text-decoration:none;color:var(--text);}
.nav-logo-icon{width:32px;height:32px;background:linear-gradient(135deg,var(--accent),var(--accent2));border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:16px;}
.nav-logo em{color:var(--accent);font-style:normal;}
.nav-links{display:flex;gap:36px;list-style:none;}
.nav-links a{text-decoration:none;color:var(--muted);font-size:0.88rem;transition:color 0.2s;}
.nav-links a:hover{color:var(--text);}
.nav-cta{display:flex;gap:12px;align-items:center;}
.btn-ghost{background:none;border:1px solid var(--border);color:var(--muted);border-radius:8px;padding:8px 20px;font-family:'Epilogue',sans-serif;font-size:0.85rem;cursor:none;transition:all 0.2s;}
.btn-ghost:hover{border-color:var(--accent);color:var(--accent);}
.btn-glow{background:var(--accent);border:none;color:#04060a;border-radius:8px;padding:9px 22px;font-family:'Clash Display',sans-serif;font-weight:600;font-size:0.85rem;cursor:none;transition:all 0.25s;box-shadow:0 0 20px rgba(0,245,160,0.3);}
.btn-glow:hover{transform:translateY(-2px);box-shadow:0 0 35px rgba(0,245,160,0.5);}
.nav-hamburger{display:none;flex-direction:column;gap:5px;background:none;border:none;cursor:pointer;padding:4px;}
.nav-hamburger span{display:block;width:22px;height:2px;background:var(--text);border-radius:99px;transition:all 0.3s;}
.nav-hamburger.open span:nth-child(1){transform:translateY(7px) rotate(45deg);}
.nav-hamburger.open span:nth-child(2){opacity:0;}
.nav-hamburger.open span:nth-child(3){transform:translateY(-7px) rotate(-45deg);}
.nav-mobile{display:none;position:fixed;top:70px;left:0;right:0;background:rgba(4,6,10,0.97);backdrop-filter:blur(24px);border-bottom:1px solid var(--border);padding:20px 24px;flex-direction:column;gap:4px;z-index:999;}
.nav-mobile.open{display:flex;}
.nav-mobile a{text-decoration:none;color:var(--muted);font-size:1rem;padding:12px 8px;border-bottom:1px solid var(--border);transition:color 0.2s;}
.nav-mobile a:hover{color:var(--accent);}
.nav-mobile-cta{display:flex;gap:10px;margin-top:12px;}
footer{position:relative;z-index:1;background:var(--surface);border-top:1px solid var(--border);padding:48px 60px;display:flex;align-items:center;justify-content:space-between;}
.footer-logo{font-family:'Clash Display',sans-serif;font-weight:700;font-size:1.1rem;}
.footer-logo em{color:var(--accent);font-style:normal;}
.footer-copy{font-size:0.8rem;color:var(--muted);}
.footer-links{display:flex;gap:24px;}
.footer-links a{font-size:0.8rem;color:var(--muted);text-decoration:none;transition:color 0.2s;}
.footer-links a:hover{color:var(--accent);}
.section-label{text-align:center;font-size:0.75rem;letter-spacing:3px;text-transform:uppercase;color:var(--accent);margin-bottom:16px;}
.section-title{font-family:'Clash Display',sans-serif;font-size:clamp(2rem,4vw,3.2rem);font-weight:700;text-align:center;letter-spacing:-1.5px;margin-bottom:16px;line-height:1.1;}
.section-sub{text-align:center;color:var(--muted);max-width:500px;margin:0 auto 64px;font-size:0.95rem;line-height:1.7;}
::-webkit-scrollbar{width:4px;}
::-webkit-scrollbar-track{background:var(--bg);}
::-webkit-scrollbar-thumb{background:var(--surface);border-radius:99px;}
/* Page styles */
.hero{padding:160px 60px 100px;text-align:center;position:relative;overflow:hidden;}
.hero::before{content:'';position:absolute;top:-200px;left:50%;transform:translateX(-50%);width:800px;height:800px;background:radial-gradient(circle,rgba(0,212,255,0.06) 0%,transparent 70%);pointer-events:none;}
.hero-label{display:inline-block;font-size:0.75rem;letter-spacing:3px;text-transform:uppercase;color:var(--accent);margin-bottom:20px;border:1px solid rgba(0,245,160,0.2);padding:6px 16px;border-radius:99px;}
.hero h1{font-family:'Clash Display',sans-serif;font-size:clamp(2.4rem,5vw,4rem);font-weight:700;letter-spacing:-2px;line-height:1.1;margin-bottom:20px;}
.hero p{color:var(--muted);font-size:1.05rem;max-width:520px;margin:0 auto;line-height:1.7;}
section{padding:100px 60px;}
.categories-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:24px;max-width:1100px;margin:0 auto;}
.cat-card{background:var(--surface);border:1px solid var(--border);border-radius:16px;padding:32px;transition:border-color 0.3s,transform 0.3s;}
.cat-card:hover{border-color:rgba(0,245,160,0.3);transform:translateY(-4px);}
.cat-icon{font-size:2.4rem;margin-bottom:16px;}
.cat-card h3{font-family:'Clash Display',sans-serif;font-size:1.1rem;font-weight:600;margin-bottom:10px;}
.cat-card p{color:var(--muted);font-size:0.85rem;line-height:1.6;margin-bottom:16px;}
.format-pills{display:flex;flex-wrap:wrap;gap:6px;}
.pill{font-size:0.7rem;background:rgba(255,255,255,0.05);border:1px solid var(--border);border-radius:6px;padding:3px 8px;color:var(--muted);}
/* Limits table */
.table-wrap{max-width:800px;margin:0 auto;overflow-x:auto;}
table{width:100%;border-collapse:collapse;}
thead th{font-family:'Clash Display',sans-serif;font-size:0.9rem;padding:16px 20px;text-align:center;border-bottom:1px solid var(--border);}
thead th:first-child{text-align:left;}
tbody td{padding:14px 20px;text-align:center;border-bottom:1px solid var(--border);font-size:0.88rem;color:var(--muted);}
tbody td:first-child{text-align:left;color:var(--text);}
.plan-badge{display:inline-block;font-size:0.7rem;letter-spacing:2px;text-transform:uppercase;padding:4px 10px;border-radius:99px;}
.badge-free{background:rgba(255,255,255,0.06);color:var(--muted);}
.badge-pro{background:rgba(0,245,160,0.12);color:var(--accent);}
.badge-teams{background:rgba(0,212,255,0.12);color:var(--accent2);}
/* AI section */
.ai-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:24px;max-width:1000px;margin:0 auto;}
.ai-card{background:var(--surface);border:1px solid var(--border);border-radius:16px;padding:28px;text-align:center;}
.ai-card .ai-icon{font-size:2rem;margin-bottom:14px;}
.ai-card h3{font-family:'Clash Display',sans-serif;font-size:1rem;font-weight:600;margin-bottom:8px;}
.ai-card p{color:var(--muted);font-size:0.85rem;line-height:1.6;}
/* Viewer callout */
.viewer-callout{background:linear-gradient(135deg,rgba(0,245,160,0.06),rgba(0,212,255,0.06));border:1px solid rgba(0,245,160,0.15);border-radius:24px;padding:56px;text-align:center;max-width:900px;margin:0 auto;}
.viewer-callout h2{font-family:'Clash Display',sans-serif;font-size:clamp(1.6rem,3vw,2.4rem);font-weight:700;letter-spacing:-1px;margin-bottom:12px;}
.viewer-callout p{color:var(--muted);margin-bottom:36px;font-size:0.95rem;}
.viewer-icons{display:flex;justify-content:center;gap:32px;flex-wrap:wrap;}
.viewer-item{display:flex;flex-direction:column;align-items:center;gap:8px;}
.viewer-item span:first-child{font-size:2rem;width:56px;height:56px;background:var(--surface);border:1px solid var(--border);border-radius:14px;display:flex;align-items:center;justify-content:center;}
.viewer-item span:last-child{font-size:0.78rem;color:var(--muted);}
@media(max-width:900px){nav{padding:18px 24px;}.nav-links,.nav-cta{display:none;}.nav-hamburger{display:flex;}footer{padding:32px 24px;flex-direction:column;gap:20px;text-align:center;}.footer-links{flex-wrap:wrap;justify-content:center;}section{padding:80px 24px;}.hero{padding:130px 24px 80px;}.categories-grid,.ai-grid{grid-template-columns:1fr 1fr;}.viewer-callout{padding:36px 24px;}}
@media(max-width:600px){nav{padding:16px 20px;}footer{padding:28px 20px;}.categories-grid,.ai-grid{grid-template-columns:1fr;}}
</style>
</head>
<body>
<div class="cursor" id="cursor"></div>
<div class="cursor-ring" id="cursorRing"></div>
<nav>
  <a href="/" class="nav-logo"><img src="/favicon.png" alt="Keeption Vault" style="width:32px;height:32px;border-radius:9px;object-fit:contain;"/>Keeption<em> Vault</em></a>
  <ul class="nav-links">
    <li><a href="/">Home</a></li>
    <li><a href="/features">Features</a></li>
    <li><a href="/file-types">File Types</a></li>
    <li><a href="/pricing">Pricing</a></li>
    <li><a href="/security">Security</a></li>
    <li><a href="/community">Community</a></li>
  </ul>
  <div class="nav-cta">
    <a href="/login"><button class="btn-ghost">Sign In</button></a>
    <a href="/register"><button class="btn-glow">Start Free →</button></a>
  </div>
  <button class="nav-hamburger" id="hamburger"><span></span><span></span><span></span></button>
</nav>
<div class="nav-mobile" id="navMobile">
  <a href="/features">Features</a>
  <a href="/file-types">File Types</a>
  <a href="/pricing">Pricing</a>
  <a href="/security">Security</a>
  <a href="/community">Community</a>
  <div class="nav-mobile-cta">
    <a href="/login" style="flex:1"><button class="btn-ghost" style="width:100%">Sign In</button></a>
    <a href="/register" style="flex:1"><button class="btn-glow" style="width:100%">Start Free →</button></a>
  </div>
</div>

<!-- Hero -->
<div class="hero">
  <div class="hero-label">File Types</div>
  <h1>Every file you own.<br>Protected.</h1>
  <p>From RAW photos to 4K video, lossless audio to complex design files — Keeption Vault handles it all with zero compromise on privacy.</p>
</div>

<!-- Category Cards -->
<section>
  <div class="section-label">Supported formats</div>
  <h2 class="section-title">Store anything</h2>
  <p class="section-sub">Every major file format supported out of the box. No conversions, no compression, no surprises.</p>
  <div class="categories-grid">
    <div class="cat-card reveal">
      <div class="cat-icon">🖼️</div>
      <h3>Photos</h3>
      <p>From phone snapshots to professional RAW files. Full resolution, always.</p>
      <div class="format-pills">
        <span class="pill">JPG</span><span class="pill">PNG</span><span class="pill">HEIC</span><span class="pill">RAW</span><span class="pill">CR2</span><span class="pill">NEF</span><span class="pill">WEBP</span><span class="pill">TIFF</span>
      </div>
    </div>
    <div class="cat-card reveal">
      <div class="cat-icon">🎬</div>
      <h3>Videos</h3>
      <p>4K, 8K, slow-mo, time-lapse — every frame preserved exactly as shot.</p>
      <div class="format-pills">
        <span class="pill">MP4</span><span class="pill">MOV</span><span class="pill">MKV</span><span class="pill">AVI</span><span class="pill">WEBM</span><span class="pill">M4V</span><span class="pill">FLV</span>
      </div>
    </div>
    <div class="cat-card reveal">
      <div class="cat-icon">🎵</div>
      <h3>Music</h3>
      <p>Lossless audio, podcasts, voice memos — your entire library in one place.</p>
      <div class="format-pills">
        <span class="pill">MP3</span><span class="pill">FLAC</span><span class="pill">WAV</span><span class="pill">AAC</span><span class="pill">OGG</span><span class="pill">AIFF</span><span class="pill">M4A</span>
      </div>
    </div>
    <div class="cat-card reveal">
      <div class="cat-icon">📄</div>
      <h3>Documents</h3>
      <p>PDFs, spreadsheets, presentations — work files stay private and accessible.</p>
      <div class="format-pills">
        <span class="pill">PDF</span><span class="pill">DOCX</span><span class="pill">XLSX</span><span class="pill">PPTX</span><span class="pill">TXT</span><span class="pill">MD</span><span class="pill">CSV</span>
      </div>
    </div>
    <div class="cat-card reveal">
      <div class="cat-icon">🎨</div>
      <h3>Design Files</h3>
      <p>Figma exports, Photoshop layers, Illustrator vectors — creative work protected.</p>
      <div class="format-pills">
        <span class="pill">PSD</span><span class="pill">AI</span><span class="pill">SKETCH</span><span class="pill">FIG</span><span class="pill">XD</span><span class="pill">SVG</span><span class="pill">EPS</span>
      </div>
    </div>
    <div class="cat-card reveal">
      <div class="cat-icon">📦</div>
      <h3>Archives</h3>
      <p>Compressed backups, project bundles, disk images — stored intact, always.</p>
      <div class="format-pills">
        <span class="pill">ZIP</span><span class="pill">RAR</span><span class="pill">7Z</span><span class="pill">TAR</span><span class="pill">GZ</span><span class="pill">DMG</span><span class="pill">ISO</span>
      </div>
    </div>
  </div>
</section>

<!-- File Size Limits -->
<section style="padding-top:0;">
  <div class="section-label">Storage limits</div>
  <h2 class="section-title">Pick your plan</h2>
  <p class="section-sub">Generous limits across every tier. Upgrade anytime without losing a single file.</p>
  <div class="table-wrap reveal">
    <table>
      <thead>
        <tr>
          <th>Limit</th>
          <th><span class="plan-badge badge-free">Free</span></th>
          <th><span class="plan-badge badge-pro">Pro</span></th>
          <th><span class="plan-badge badge-teams">Teams</span></th>
        </tr>
      </thead>
      <tbody>
        <tr><td>Max file size</td><td>500 MB</td><td>10 GB</td><td>50 GB</td></tr>
        <tr><td>Total storage</td><td>5 GB</td><td>100 GB</td><td>500 GB</td></tr>
        <tr><td>Daily uploads</td><td>Unlimited</td><td>Unlimited</td><td>Unlimited</td></tr>
        <tr><td>Version history</td><td>7 days</td><td>90 days</td><td>180 days</td></tr>
        <tr><td>Shared links</td><td>5 active</td><td>Unlimited</td><td>Unlimited</td></tr>
      </tbody>
    </table>
  </div>
</section>

<!-- AI Features -->
<section style="padding-top:0;">
  <div class="section-label">AI-powered</div>
  <h2 class="section-title">Your files, smarter</h2>
  <p class="section-sub">On-device AI that understands your content — without ever sending it to our servers.</p>
  <div style="max-width:480px;margin:0 auto;">
    <div class="ai-card reveal">
      <div class="ai-icon">📄</div>
      <h3>Document Scanner</h3>
      <p>Take a photo of any document — receipts, contracts, notes, IDs — and Keeption instantly transforms it into a clean, structured, readable version saved right to your vault.</p>
    </div>
  </div>
</section>

<!-- Built-in Viewer -->
<section style="padding-top:0;">
  <div class="viewer-callout reveal">
    <h2>No download needed.<br>Preview everything in-browser.</h2>
    <p>Open any file instantly without leaving your vault. Fast, encrypted, and works on any device.</p>
    <div class="viewer-icons">
      <div class="viewer-item"><span>🎬</span><span>Video Player</span></div>
      <div class="viewer-item"><span>🎵</span><span>Audio Player</span></div>
      <div class="viewer-item"><span>🖼️</span><span>Photo Viewer</span></div>
      <div class="viewer-item"><span>📄</span><span>PDF Viewer</span></div>
      <div class="viewer-item"><span>📝</span><span>Doc Viewer</span></div>
    </div>
  </div>
</section>

<footer>
  <div class="footer-logo">Keeption<em> Vault</em></div>
  <div class="footer-copy">© 2025 Keeption Vault. Your files. Your rules.</div>
  <div class="footer-links">
    <a href="#">Privacy</a><a href="#">Terms</a><a href="/security">Security</a><a href="#">Blog</a><a href="#">Contact</a>
  </div>
</footer>

<script>
const cursor=document.getElementById('cursor');
const ring=document.getElementById('cursorRing');
let mx=0,my=0,rx=0,ry=0;
document.addEventListener('mousemove',e=>{mx=e.clientX;my=e.clientY;cursor.style.left=mx+'px';cursor.style.top=my+'px';});
function animRing(){rx+=(mx-rx)*0.12;ry+=(my-ry)*0.12;ring.style.left=rx+'px';ring.style.top=ry+'px';requestAnimationFrame(animRing);}
animRing();
const hamburger=document.getElementById('hamburger');
const navMobile=document.getElementById('navMobile');
hamburger.addEventListener('click',()=>{hamburger.classList.toggle('open');navMobile.classList.toggle('open');});
const observer=new IntersectionObserver(entries=>{entries.forEach(e=>{if(e.isIntersecting){e.target.style.opacity='1';e.target.style.transform='translateY(0)';}});},{threshold:0.1});
document.querySelectorAll('.reveal').forEach(el=>{el.style.opacity='0';el.style.transform='translateY(30px)';el.style.transition='opacity 0.6s ease,transform 0.6s ease';observer.observe(el);});
</script>
</body>
</html>
