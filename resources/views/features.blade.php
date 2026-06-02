<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Features — Keeption Vault</title>
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
.hero::before{content:'';position:absolute;top:-200px;left:50%;transform:translateX(-50%);width:800px;height:800px;background:radial-gradient(circle,rgba(0,245,160,0.06) 0%,transparent 70%);pointer-events:none;}
.hero-label{display:inline-block;font-size:0.75rem;letter-spacing:3px;text-transform:uppercase;color:var(--accent);margin-bottom:20px;border:1px solid rgba(0,245,160,0.2);padding:6px 16px;border-radius:99px;}
.hero h1{font-family:'Clash Display',sans-serif;font-size:clamp(2.4rem,5vw,4rem);font-weight:700;letter-spacing:-2px;line-height:1.1;margin-bottom:20px;}
.hero p{color:var(--muted);font-size:1.05rem;max-width:520px;margin:0 auto;line-height:1.7;}
section{padding:100px 60px;}
.features-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:24px;max-width:1100px;margin:0 auto;}
.feature-card{background:var(--surface);border:1px solid var(--border);border-radius:16px;padding:32px;transition:border-color 0.3s,transform 0.3s;}
.feature-card:hover{border-color:rgba(0,245,160,0.3);transform:translateY(-4px);}
.feature-icon{font-size:2rem;margin-bottom:16px;}
.feature-card h3{font-family:'Clash Display',sans-serif;font-size:1.1rem;font-weight:600;margin-bottom:10px;}
.feature-card p{color:var(--muted);font-size:0.88rem;line-height:1.6;margin-bottom:16px;}
.feature-tag{display:inline-block;font-size:0.7rem;letter-spacing:2px;text-transform:uppercase;color:var(--accent);border:1px solid rgba(0,245,160,0.25);padding:4px 10px;border-radius:99px;}
/* Comparison table */
.table-wrap{max-width:900px;margin:0 auto;overflow-x:auto;}
table{width:100%;border-collapse:collapse;}
thead th{font-family:'Clash Display',sans-serif;font-size:0.9rem;padding:16px 20px;text-align:center;border-bottom:1px solid var(--border);}
thead th:first-child{text-align:left;}
thead th.highlight{background:rgba(0,245,160,0.08);color:var(--accent);border-radius:12px 12px 0 0;}
tbody td{padding:14px 20px;text-align:center;border-bottom:1px solid var(--border);font-size:0.88rem;color:var(--muted);}
tbody td:first-child{text-align:left;color:var(--text);}
tbody td.highlight{background:rgba(0,245,160,0.05);color:var(--accent);font-weight:500;}
.check{color:var(--accent);}
.cross{color:var(--accent3);}
/* Testimonials */
.testimonials-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:24px;max-width:1000px;margin:0 auto;}
.testimonial-card{background:var(--surface);border:1px solid var(--border);border-radius:16px;padding:28px;}
.testimonial-card blockquote{font-size:0.92rem;line-height:1.7;color:var(--text);margin-bottom:20px;font-style:italic;}
.testimonial-author{display:flex;align-items:center;gap:12px;}
.avatar{width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,var(--accent),var(--accent2));display:flex;align-items:center;justify-content:center;font-size:1rem;}
.author-name{font-family:'Clash Display',sans-serif;font-size:0.88rem;font-weight:600;}
.author-role{font-size:0.78rem;color:var(--muted);}
/* CTA banner */
.cta-banner{background:linear-gradient(135deg,rgba(0,245,160,0.08),rgba(0,212,255,0.08));border:1px solid rgba(0,245,160,0.2);border-radius:24px;padding:64px;text-align:center;max-width:800px;margin:0 auto;}
.cta-banner h2{font-family:'Clash Display',sans-serif;font-size:clamp(1.8rem,3vw,2.6rem);font-weight:700;letter-spacing:-1px;margin-bottom:12px;}
.cta-banner p{color:var(--muted);margin-bottom:32px;font-size:0.95rem;}
@media(max-width:900px){nav{padding:18px 24px;}.nav-links,.nav-cta{display:none;}.nav-hamburger{display:flex;}footer{padding:32px 24px;flex-direction:column;gap:20px;text-align:center;}.footer-links{flex-wrap:wrap;justify-content:center;}section{padding:80px 24px;}.hero{padding:130px 24px 80px;}.features-grid{grid-template-columns:1fr 1fr;}.testimonials-grid{grid-template-columns:1fr;}.cta-banner{padding:40px 24px;}}
@media(max-width:600px){nav{padding:16px 20px;}footer{padding:28px 20px;}.features-grid{grid-template-columns:1fr;}}
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
  <div class="hero-label">Features</div>
  <h1>Everything you need.<br>Nothing you don't.</h1>
  <p>Keeption Vault is the most complete private cloud storage built for people who actually care about their files — and their privacy.</p>
</div>

<!-- Feature Cards -->
<section>
  <div class="section-label">What's inside</div>
  <h2 class="section-title">Built different, by design</h2>
  <p class="section-sub">Six core features that make Keeption Vault the last storage app you'll ever need.</p>
  <div class="features-grid">
    <div class="feature-card reveal">
      <div class="feature-icon">🔐</div>
      <h3>Zero-Knowledge Encryption</h3>
      <p>Your files are encrypted on your device before they ever leave it. We store ciphertext — never your keys, never your content.</p>
      <span class="feature-tag">Privacy</span>
    </div>
    <div class="feature-card reveal">
      <div class="feature-icon">🤖</div>
      <h3>AI Smart Search</h3>
      <p>Find any file in seconds. Our on-device AI indexes your content without ever sending it to our servers.</p>
      <span class="feature-tag">AI-Powered</span>
    </div>
    <div class="feature-card reveal">
      <div class="feature-icon">💣</div>
      <h3>Self-Destructing Links</h3>
      <p>Share files with expiry dates, view limits, or password protection. Links vanish automatically — no cleanup needed.</p>
      <span class="feature-tag">Sharing</span>
    </div>
    <div class="feature-card reveal">
      <div class="feature-icon">📸</div>
      <h3>Auto Camera Backup</h3>
      <p>Every photo and video you take is silently backed up in the background. Encrypted, compressed, and always there.</p>
      <span class="feature-tag">Backup</span>
    </div>
    <div class="feature-card reveal">
      <div class="feature-icon">🤝</div>
      <h3>Collaborative Folders</h3>
      <p>Invite teammates to shared vaults with granular permissions — view, comment, or edit. Full audit trail included.</p>
      <span class="feature-tag">Teams</span>
    </div>
    <div class="feature-card reveal">
      <div class="feature-icon">🕰️</div>
      <h3>Version History</h3>
      <p>Every change is saved. Roll back any file to any previous version up to 180 days. Accidental deletes? Recovered instantly.</p>
      <span class="feature-tag">Recovery</span>
    </div>
  </div>
</section>

<!-- Comparison Table -->
<section style="padding-top:0;">
  <div class="section-label">Comparison</div>
  <h2 class="section-title">How we stack up</h2>
  <p class="section-sub">We're not just another cloud drive. Here's the honest breakdown.</p>
  <div class="table-wrap reveal">
    <table>
      <thead>
        <tr>
          <th>Feature</th>
          <th class="highlight">Keeption Vault</th>
          <th>Google Drive</th>
          <th>Dropbox</th>
          <th>iCloud</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>End-to-end encryption</td>
          <td class="highlight check">✓</td>
          <td class="cross">✗</td>
          <td class="cross">✗</td>
          <td class="cross">✗</td>
        </tr>
        <tr>
          <td>Zero-knowledge</td>
          <td class="highlight check">✓</td>
          <td class="cross">✗</td>
          <td class="cross">✗</td>
          <td class="cross">✗</td>
        </tr>
        <tr>
          <td>AI search</td>
          <td class="highlight check">✓ On-device</td>
          <td>✓ Server-side</td>
          <td class="cross">✗</td>
          <td class="cross">✗</td>
        </tr>
        <tr>
          <td>Self-destruct links</td>
          <td class="highlight check">✓</td>
          <td class="cross">✗</td>
          <td>Paid only</td>
          <td class="cross">✗</td>
        </tr>
        <tr>
          <td>Camera backup</td>
          <td class="highlight check">✓</td>
          <td>✓</td>
          <td>Paid only</td>
          <td>✓</td>
        </tr>
        <tr>
          <td>Free storage</td>
          <td class="highlight">5 GB</td>
          <td>15 GB</td>
          <td>2 GB</td>
          <td>5 GB</td>
        </tr>
        <tr>
          <td>Price / month</td>
          <td class="highlight">From $3</td>
          <td>From $2.99</td>
          <td>From $9.99</td>
          <td>From $0.99</td>
        </tr>
      </tbody>
    </table>
  </div>
</section>

<!-- Testimonials -->
<section style="padding-top:0;">
  <div class="section-label">Early users</div>
  <h2 class="section-title">People who switched</h2>
  <p class="section-sub">Real feedback from our beta community.</p>
  <div class="testimonials-grid">
    <div class="testimonial-card reveal">
      <blockquote>"I finally have a place where my files are truly mine. No one can access them, no one can sell them. Keeption Vault gave me peace of mind I didn't know I was missing."</blockquote>
      <div class="testimonial-author">
        <div class="avatar">👩</div>
        <div>
          <div class="author-name">Mara Lindqvist</div>
          <div class="author-role">Freelance Photographer</div>
        </div>
      </div>
    </div>
    <div class="testimonial-card reveal">
      <blockquote>"The self-destructing links alone are worth it. I send client contracts that expire after one view. No more worrying about who has what floating around in their inbox."</blockquote>
      <div class="testimonial-author">
        <div class="avatar">👨</div>
        <div>
          <div class="author-name">Darius Okafor</div>
          <div class="author-role">Contract Lawyer</div>
        </div>
      </div>
    </div>
    <div class="testimonial-card reveal">
      <blockquote>"Version history saved my entire project. I accidentally overwrote a week of work and had it back in 30 seconds. That's when I knew I wasn't going back to Dropbox."</blockquote>
      <div class="testimonial-author">
        <div class="avatar">🧑</div>
        <div>
          <div class="author-name">Priya Nair</div>
          <div class="author-role">UX Designer</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CTA Banner -->
<section style="padding-top:0;">
  <div class="cta-banner reveal">
    <h2>Try all features free for 30 days</h2>
    <p>No credit card required. Full Pro access. Cancel anytime.</p>
    <a href="/register"><button class="btn-glow" style="font-size:1rem;padding:14px 36px;">Start Free →</button></a>
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
