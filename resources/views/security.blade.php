<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Security — Keeption Vault</title>
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
/* Page */
.hero{padding:160px 60px 100px;text-align:center;position:relative;overflow:hidden;}
.hero::before{content:'';position:absolute;top:-200px;left:50%;transform:translateX(-50%);width:800px;height:800px;background:radial-gradient(circle,rgba(0,245,160,0.07) 0%,transparent 70%);pointer-events:none;}
.hero-label{display:inline-block;font-size:0.75rem;letter-spacing:3px;text-transform:uppercase;color:var(--accent);margin-bottom:20px;border:1px solid rgba(0,245,160,0.2);padding:6px 16px;border-radius:99px;}
.hero h1{font-family:'Clash Display',sans-serif;font-size:clamp(2rem,4.5vw,3.6rem);font-weight:700;letter-spacing:-2px;line-height:1.1;margin-bottom:20px;}
.hero p{color:var(--muted);font-size:1.05rem;max-width:520px;margin:0 auto;line-height:1.7;}
section{padding:100px 60px;}
/* How it works */
.flow{display:flex;align-items:center;justify-content:center;gap:0;max-width:900px;margin:0 auto;flex-wrap:wrap;}
.flow-step{background:var(--surface);border:1px solid var(--border);border-radius:16px;padding:32px 28px;text-align:center;flex:1;min-width:220px;}
.flow-icon{font-size:2.4rem;margin-bottom:14px;}
.flow-step h3{font-family:'Clash Display',sans-serif;font-size:1rem;font-weight:600;margin-bottom:8px;}
.flow-step p{color:var(--muted);font-size:0.85rem;line-height:1.6;}
.flow-arrow{font-size:1.5rem;color:var(--accent);padding:0 16px;flex-shrink:0;}
/* Certs */
.certs-row{display:flex;justify-content:center;gap:20px;flex-wrap:wrap;max-width:900px;margin:0 auto;}
.cert-badge{background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:24px 32px;text-align:center;min-width:160px;}
.cert-badge .cert-icon{font-size:2rem;margin-bottom:10px;}
.cert-badge h4{font-family:'Clash Display',sans-serif;font-size:0.95rem;font-weight:600;margin-bottom:4px;}
.cert-badge p{font-size:0.78rem;color:var(--muted);}
/* Encryption details */
.enc-grid{display:grid;grid-template-columns:1fr 1fr;gap:24px;max-width:900px;margin:0 auto;}
.enc-card{background:var(--surface);border:1px solid var(--border);border-radius:16px;padding:28px;}
.enc-card h3{font-family:'Clash Display',sans-serif;font-size:1rem;font-weight:600;margin-bottom:10px;color:var(--accent);}
.enc-card p{color:var(--muted);font-size:0.88rem;line-height:1.7;}
/* Data center */
.dc-row{display:flex;justify-content:center;gap:32px;flex-wrap:wrap;max-width:800px;margin:0 auto 48px;}
.dc-stat{text-align:center;}
.dc-stat .stat-num{font-family:'Clash Display',sans-serif;font-size:2.4rem;font-weight:700;color:var(--accent);letter-spacing:-1px;}
.dc-stat .stat-label{font-size:0.82rem;color:var(--muted);margin-top:4px;}
/* Access logs callout */
.callout{background:linear-gradient(135deg,rgba(0,245,160,0.06),rgba(0,212,255,0.06));border:1px solid rgba(0,245,160,0.15);border-radius:20px;padding:48px;display:flex;align-items:center;gap:40px;max-width:900px;margin:0 auto;}
.callout-icon{font-size:3rem;flex-shrink:0;}
.callout h3{font-family:'Clash Display',sans-serif;font-size:1.4rem;font-weight:700;margin-bottom:8px;}
.callout p{color:var(--muted);font-size:0.9rem;line-height:1.7;}
/* Bug bounty */
.bounty{background:var(--surface);border:1px solid var(--border);border-radius:20px;padding:48px;text-align:center;max-width:700px;margin:0 auto;}
.bounty h3{font-family:'Clash Display',sans-serif;font-size:1.6rem;font-weight:700;margin-bottom:12px;}
.bounty p{color:var(--muted);font-size:0.9rem;line-height:1.7;margin-bottom:24px;}
/* Tagline */
.tagline-section{padding:100px 60px;text-align:center;}
.tagline-section h2{font-family:'Clash Display',sans-serif;font-size:clamp(2.4rem,6vw,5rem);font-weight:700;letter-spacing:-3px;background:linear-gradient(135deg,var(--accent),var(--accent2));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;line-height:1.1;}
@media(max-width:900px){nav{padding:18px 24px;}.nav-links,.nav-cta{display:none;}.nav-hamburger{display:flex;}footer{padding:32px 24px;flex-direction:column;gap:20px;text-align:center;}.footer-links{flex-wrap:wrap;justify-content:center;}section{padding:80px 24px;}.hero{padding:130px 24px 80px;}.enc-grid{grid-template-columns:1fr;}.callout{flex-direction:column;text-align:center;padding:32px 24px;}.flow-arrow{transform:rotate(90deg);padding:8px 0;}.tagline-section{padding:80px 24px;}}
@media(max-width:600px){nav{padding:16px 20px;}footer{padding:28px 20px;}.flow{flex-direction:column;}.flow-arrow{transform:rotate(90deg);}}
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
  <a href="/features">Features</a><a href="/file-types">File Types</a><a href="/pricing">Pricing</a><a href="/security">Security</a><a href="/community">Community</a>
  <div class="nav-mobile-cta">
    <a href="/login" style="flex:1"><button class="btn-ghost" style="width:100%">Sign In</button></a>
    <a href="/register" style="flex:1"><button class="btn-glow" style="width:100%">Start Free →</button></a>
  </div>
</div>

<div class="hero">
  <div class="hero-label">Security</div>
  <h1>We literally cannot<br>read your files.<br>That's the point.</h1>
  <p>Zero-knowledge architecture means your encryption keys never leave your device. Not even our engineers can access your data.</p>
</div>

<!-- How it works -->
<section>
  <div class="section-label">Zero-knowledge architecture</div>
  <h2 class="section-title">How it works</h2>
  <p class="section-sub">Three steps. One guarantee: only you can ever see your files.</p>
  <div class="flow">
    <div class="flow-step reveal">
      <div class="flow-icon">🔑</div>
      <h3>You Encrypt</h3>
      <p>Your files are encrypted on your device using your unique key before any data is transmitted. The key never leaves your device.</p>
    </div>
    <div class="flow-arrow">→</div>
    <div class="flow-step reveal">
      <div class="flow-icon">☁</div>
      <h3>We Store</h3>
      <p>We receive and store only encrypted ciphertext. It's mathematically impossible for us to read, sell, or hand over your files.</p>
    </div>
    <div class="flow-arrow">→</div>
    <div class="flow-step reveal">
      <div class="flow-icon">🔓</div>
      <h3>Only You Decrypt</h3>
      <p>When you access your files, decryption happens locally on your device. Your key, your data, your control — always.</p>
    </div>
  </div>
</section>

<!-- Certifications -->
<section style="padding-top:0;">
  <div class="section-label">Certifications</div>
  <h2 class="section-title">Industry-standard security</h2>
  <p class="section-sub">Audited, certified, and compliant with the standards that matter.</p>
  <div class="certs-row">
    <div class="cert-badge reveal"><div class="cert-icon">🔒</div><h4>SSL / TLS</h4><p>All data in transit encrypted with TLS 1.3</p></div>
    <div class="cert-badge reveal"><div class="cert-icon">🛡️</div><h4>AES-256</h4><p>Military-grade encryption at rest</p></div>
    <div class="cert-badge reveal"><div class="cert-icon">🇪🇺</div><h4>GDPR</h4><p>Fully compliant with EU data regulations</p></div>
    <div class="cert-badge reveal"><div class="cert-icon">✅</div><h4>SOC 2</h4><p>Type II certified security controls</p></div>
  </div>
</section>

<!-- Encryption Details -->
<section style="padding-top:0;">
  <div class="section-label">Technical details</div>
  <h2 class="section-title">Under the hood</h2>
  <p class="section-sub">For the technically curious — here's exactly how we protect your data.</p>
  <div class="enc-grid">
    <div class="enc-card reveal">
      <h3>AES-256 Encryption</h3>
      <p>Every file is encrypted using AES-256-GCM, the same standard used by governments and financial institutions worldwide. Brute-forcing it would take longer than the age of the universe.</p>
    </div>
    <div class="enc-card reveal">
      <h3>End-to-End Encryption</h3>
      <p>Encryption and decryption happen exclusively on your device. Data is encrypted before it leaves your browser or app, and decrypted only when you open it — never on our servers.</p>
    </div>
    <div class="enc-card reveal">
      <h3>Client-Side Encryption</h3>
      <p>Your encryption keys are derived from your password using PBKDF2 with 600,000 iterations. We never see your password or your keys — only a salted hash for authentication.</p>
    </div>
    <div class="enc-card reveal">
      <h3>Zero-Knowledge Proof</h3>
      <p>We can verify you're authorized to access your vault without ever knowing your password or decryption key. Cryptographic proofs replace trust — you don't have to take our word for it.</p>
    </div>
  </div>
</section>

<!-- Data Centers -->
<section style="padding-top:0;">
  <div class="section-label">Infrastructure</div>
  <h2 class="section-title">Built to stay up</h2>
  <p class="section-sub">Redundant infrastructure across multiple regions so your files are always there when you need them.</p>
  <div class="dc-row reveal">
    <div class="dc-stat"><div class="stat-num">99.9%</div><div class="stat-label">Uptime SLA</div></div>
    <div class="dc-stat"><div class="stat-num">EU + US</div><div class="stat-label">Server regions</div></div>
    <div class="dc-stat"><div class="stat-num">ISO 27001</div><div class="stat-label">Certified data centers</div></div>
    <div class="dc-stat"><div class="stat-num">3×</div><div class="stat-label">Redundant backups</div></div>
  </div>
</section>

<!-- Access Logs -->
<section style="padding-top:0;">
  <div class="callout reveal">
    <div class="callout-icon">📋</div>
    <div>
      <h3>See exactly who accessed your files and when</h3>
      <p>Every view, download, and share is logged with timestamp, device, and location. If something looks off, you'll know immediately — and you can revoke access with one click.</p>
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
