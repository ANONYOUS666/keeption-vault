<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Community — Keeption Vault</title>
<link rel="icon" type="image/png" href="/favicon.png"/>
<link href="https://fonts.googleapis.com/css2?family=Clash+Display:wght@400;500;600;700&family=Epilogue:wght@300;400;500&display=swap" rel="stylesheet"/>
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
<style>
:root{--bg:#04060a;--surface:#0c0f16;--border:rgba(255,255,255,0.07);--accent:#00f5a0;--accent2:#00d4ff;--text:#eef0f6;--muted:#636b7d;}
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box;}
html{scroll-behavior:smooth;}
body{font-family:'Epilogue',sans-serif;background:var(--bg);color:var(--text);overflow-x:hidden;cursor:none;}
.cursor{position:fixed;width:10px;height:10px;background:var(--accent);border-radius:50%;pointer-events:none;z-index:9999;transform:translate(-50%,-50%);mix-blend-mode:difference;}
.cursor-ring{position:fixed;width:36px;height:36px;border:1.5px solid rgba(0,245,160,0.5);border-radius:50%;pointer-events:none;z-index:9998;transform:translate(-50%,-50%);transition:width 0.3s,height 0.3s;}
nav{position:fixed;top:0;left:0;right:0;z-index:1000;display:flex;align-items:center;justify-content:space-between;padding:22px 60px;background:rgba(4,6,10,0.8);backdrop-filter:blur(24px);border-bottom:1px solid var(--border);}
.nav-logo{font-family:'Clash Display',sans-serif;font-weight:700;font-size:1.3rem;display:flex;align-items:center;gap:10px;text-decoration:none;color:var(--text);}
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
.section-sub{text-align:center;color:var(--muted);max-width:520px;margin:0 auto 64px;font-size:0.95rem;line-height:1.7;}
::-webkit-scrollbar{width:4px;}
::-webkit-scrollbar-track{background:var(--bg);}
::-webkit-scrollbar-thumb{background:var(--surface);border-radius:99px;}
.hero{padding:160px 60px 100px;text-align:center;position:relative;overflow:hidden;}
.hero::before{content:'';position:absolute;top:-200px;left:50%;transform:translateX(-50%);width:800px;height:800px;background:radial-gradient(circle,rgba(0,212,255,0.06) 0%,transparent 70%);pointer-events:none;}
.hero-label{display:inline-block;font-size:0.75rem;letter-spacing:3px;text-transform:uppercase;color:var(--accent);margin-bottom:20px;border:1px solid rgba(0,245,160,0.2);padding:6px 16px;border-radius:99px;}
.hero h1{font-family:'Clash Display',sans-serif;font-size:clamp(2.4rem,5vw,4rem);font-weight:700;letter-spacing:-2px;line-height:1.1;margin-bottom:20px;}
.hero p{color:var(--muted);font-size:1.05rem;max-width:540px;margin:0 auto;line-height:1.7;}
section{padding:100px 60px;}
/* How it works */
.steps-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:24px;max-width:1000px;margin:0 auto;}
.step-card{background:var(--surface);border:1px solid var(--border);border-radius:16px;padding:32px;text-align:center;transition:border-color 0.3s,transform 0.3s;}
.step-card:hover{border-color:rgba(0,245,160,0.3);transform:translateY(-4px);}
.step-num{display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:50%;background:rgba(0,245,160,0.1);border:1px solid rgba(0,245,160,0.25);color:var(--accent);font-family:'Clash Display',sans-serif;font-weight:700;font-size:0.9rem;margin-bottom:16px;}
.step-icon{font-size:2.2rem;margin-bottom:14px;}
.step-card h3{font-family:'Clash Display',sans-serif;font-size:1.05rem;font-weight:600;margin-bottom:8px;}
.step-card p{color:var(--muted);font-size:0.85rem;line-height:1.6;}
/* File types showcase */
.file-types-row{display:flex;flex-wrap:wrap;gap:12px;justify-content:center;max-width:800px;margin:0 auto 48px;}
.file-pill{display:inline-flex;align-items:center;gap:8px;background:var(--surface);border:1px solid var(--border);border-radius:99px;padding:8px 18px;font-size:0.85rem;color:var(--text);}
.file-pill span{font-size:1.1rem;}
/* Share features */
.share-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:24px;max-width:900px;margin:0 auto;}
.share-card{background:var(--surface);border:1px solid var(--border);border-radius:16px;padding:28px;display:flex;gap:20px;align-items:flex-start;transition:border-color 0.3s;}
.share-card:hover{border-color:rgba(0,245,160,0.3);}
.share-icon{font-size:1.8rem;flex-shrink:0;margin-top:2px;}
.share-card h3{font-family:'Clash Display',sans-serif;font-size:1rem;font-weight:600;margin-bottom:6px;}
.share-card p{color:var(--muted);font-size:0.85rem;line-height:1.6;}
/* Guidelines */
.guidelines-list{max-width:700px;margin:0 auto;display:flex;flex-direction:column;gap:16px;}
.guideline-row{display:flex;align-items:flex-start;gap:16px;background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:20px 24px;}
.guideline-icon{font-size:1.5rem;flex-shrink:0;}
.guideline-row h4{font-family:'Clash Display',sans-serif;font-size:0.95rem;font-weight:600;margin-bottom:4px;}
.guideline-row p{font-size:0.85rem;color:var(--muted);line-height:1.6;}
/* CTA */
.join-cta{background:var(--surface);border:1px solid var(--border);border-radius:24px;padding:64px;text-align:center;max-width:700px;margin:0 auto;}
.join-cta h2{font-family:'Clash Display',sans-serif;font-size:clamp(1.8rem,3vw,2.6rem);font-weight:700;letter-spacing:-1px;margin-bottom:12px;}
.join-cta p{color:var(--muted);margin-bottom:32px;font-size:0.95rem;}
@media(max-width:900px){nav{padding:18px 24px;}.nav-links,.nav-cta{display:none;}.nav-hamburger{display:flex;}footer{padding:32px 24px;flex-direction:column;gap:20px;text-align:center;}.footer-links{flex-wrap:wrap;justify-content:center;}section{padding:80px 24px;}.hero{padding:130px 24px 80px;}.steps-grid,.share-grid{grid-template-columns:1fr 1fr;}.join-cta{padding:40px 24px;}}
@media(max-width:600px){nav{padding:16px 20px;}footer{padding:28px 20px;}.steps-grid,.share-grid{grid-template-columns:1fr;}}
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

<!-- Hero -->
<div class="hero">
  <div class="hero-label">Sharing</div>
  <h1>Share anything.<br>With anyone.</h1>
  <p>Videos, photos, music, documents — send any file to anyone in seconds. No app required on their end. Just a link.</p>
</div>

<!-- How it works -->
<section>
  <div class="section-label">How it works</div>
  <h2 class="section-title">Three steps to share</h2>
  <p class="section-sub">Sharing from your vault is as simple as it gets.</p>
  <div class="steps-grid">
    <div class="step-card reveal">
      <div class="step-num">1</div>
      <div class="step-icon">📁</div>
      <h3>Upload your file</h3>
      <p>Drop any photo, video, audio file, or document into your vault. Any format, any size — up to your plan limit.</p>
    </div>
    <div class="step-card reveal">
      <div class="step-num">2</div>
      <div class="step-icon">🔗</div>
      <h3>Generate a share link</h3>
      <p>Hit share and get a secure link instantly. Set an expiry date or password if you want extra control over who sees it.</p>
    </div>
    <div class="step-card reveal">
      <div class="step-num">3</div>
      <div class="step-icon">📨</div>
      <h3>Send it anywhere</h3>
      <p>Send the link via WhatsApp, email, iMessage — anywhere. The recipient opens it in their browser, no account needed.</p>
    </div>
  </div>
</section>

<!-- File types you can share -->
<section style="padding-top:0;">
  <div class="section-label">What you can share</div>
  <h2 class="section-title">Every file type, covered</h2>
  <p class="section-sub">From a quick selfie to a full 4K film — if it's in your vault, you can share it.</p>
  <div class="file-types-row">
    <div class="file-pill"><span>📸</span> Photos</div>
    <div class="file-pill"><span>🎥</span> Videos</div>
    <div class="file-pill"><span>🎵</span> Music & Audio</div>
    <div class="file-pill"><span>📄</span> Documents</div>
    <div class="file-pill"><span>🗂️</span> Folders</div>
    <div class="file-pill"><span>🎨</span> Design Files</div>
    <div class="file-pill"><span>📦</span> Archives</div>
    <div class="file-pill"><span>💾</span> Any format</div>
  </div>
</section>

<!-- Share features -->
<section style="padding-top:0;">
  <div class="section-label">Share features</div>
  <h2 class="section-title">Sharing, done right</h2>
  <p class="section-sub">More control than any other cloud storage. More privacy too.</p>
  <div class="share-grid">
    <div class="share-card reveal">
      <div class="share-icon">⏱️</div>
      <div>
        <h3>Expiring links</h3>
        <p>Set your link to expire after 1 hour, 24 hours, 7 days, or a custom date. After that, the link stops working automatically.</p>
      </div>
    </div>
    <div class="share-card reveal">
      <div class="share-icon">🔐</div>
      <div>
        <h3>Password protection</h3>
        <p>Add a password to any shared link. Only people with the password can open it — perfect for sensitive files.</p>
      </div>
    </div>
    <div class="share-card reveal">
      <div class="share-icon">👁️</div>
      <div>
        <h3>View-only mode</h3>
        <p>Let people preview your files without being able to download them. Great for sharing work-in-progress with clients.</p>
      </div>
    </div>
    <div class="share-card reveal">
      <div class="share-icon">📊</div>
      <div>
        <h3>See who opened it</h3>
        <p>Track how many times your link was opened and from where. Know exactly when your file was received and viewed.</p>
      </div>
    </div>
  </div>
</section>

<!-- Guidelines -->
<section style="padding-top:0;">
  <div class="section-label">Guidelines</div>
  <h2 class="section-title">How we keep it good</h2>
  <p class="section-sub">A few simple rules that keep Keeption Vault a great place for everyone.</p>
  <div class="guidelines-list">
    <div class="guideline-row reveal">
      <div class="guideline-icon">✅</div>
      <div><h4>Share original content only</h4><p>Only share files you own or have rights to. Respect other people's intellectual property.</p></div>
    </div>
    <div class="guideline-row reveal">
      <div class="guideline-icon">🔒</div>
      <div><h4>Protect others' privacy</h4><p>Don't share files containing other people's personal information without their consent.</p></div>
    </div>
    <div class="guideline-row reveal">
      <div class="guideline-icon">🚫</div>
      <div><h4>No harmful content</h4><p>Content that promotes violence, illegal activity, or exploitation is strictly prohibited and will be reported.</p></div>
    </div>
  </div>
</section>

<!-- CTA -->
<section style="padding-top:0;">
  <div class="join-cta reveal">
    <h2>Start sharing today</h2>
    <p>Upload your first file and share it in under a minute. Free to start, private by default.</p>
    <a href="/register"><button class="btn-glow" style="font-size:1rem;padding:14px 36px;">Create Free Account →</button></a>
  </div>
</section>

<footer>
  <div class="footer-logo">Keeption<em> Vault</em></div>
  <div class="footer-copy">© 2026 Keeption Vault. Your files. Your rules.</div>
  <div class="footer-links">
    <a href="#">Privacy</a><a href="#">Terms</a><a href="/security">Security</a><a href="#">Contact</a>
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
