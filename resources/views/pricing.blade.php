<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Pricing — Keeption Vault</title>
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
.btn-ghost{background:none;border:1px solid var(--border);color:var(--muted);border-radius:8px;padding:8px 20px;font-family:'Epilogue',sans-serif;font-size:0.85rem;cursor:pointer;transition:all 0.2s;}
.btn-ghost:hover{border-color:var(--accent);color:var(--accent);}
.btn-glow{background:var(--accent);border:none;color:#04060a;border-radius:8px;padding:9px 22px;font-family:'Clash Display',sans-serif;font-weight:600;font-size:0.85rem;cursor:pointer;transition:all 0.25s;box-shadow:0 0 20px rgba(0,245,160,0.3);}
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
</style>
</head>
<style>
/* Page styles */
.hero{padding:160px 60px 80px;text-align:center;position:relative;overflow:hidden;}
.hero::before{content:'';position:absolute;top:-200px;left:50%;transform:translateX(-50%);width:800px;height:800px;background:radial-gradient(circle,rgba(0,245,160,0.06) 0%,transparent 70%);pointer-events:none;}
.hero-label{display:inline-block;font-size:0.75rem;letter-spacing:3px;text-transform:uppercase;color:var(--accent);margin-bottom:20px;border:1px solid rgba(0,245,160,0.2);padding:6px 16px;border-radius:99px;}
.hero h1{font-family:'Clash Display',sans-serif;font-size:clamp(2.4rem,5vw,4rem);font-weight:700;letter-spacing:-2px;line-height:1.1;margin-bottom:20px;}
.hero p{color:var(--muted);font-size:1.05rem;max-width:520px;margin:0 auto;line-height:1.7;}
section{padding:80px 60px;}
/* Toggle */
.toggle-wrap{display:flex;align-items:center;justify-content:center;gap:14px;margin-bottom:56px;}
.toggle-label{font-size:0.88rem;color:var(--muted);}
.toggle-label.active{color:var(--text);}
.toggle-switch{position:relative;width:48px;height:26px;background:var(--surface);border:1px solid var(--border);border-radius:99px;cursor:pointer;}
.toggle-switch::after{content:'';position:absolute;top:3px;left:3px;width:18px;height:18px;background:var(--accent);border-radius:50%;transition:transform 0.3s;}
.toggle-switch.annual::after{transform:translateX(22px);}
.save-badge{font-size:0.7rem;background:rgba(0,245,160,0.12);color:var(--accent);border-radius:99px;padding:3px 10px;}
/* Plans */
.plans-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:24px;max-width:1000px;margin:0 auto;}
.plan-card{background:var(--surface);border:1px solid var(--border);border-radius:20px;padding:36px;position:relative;transition:transform 0.3s;display:flex;flex-direction:column;}
        .plan-card > a{margin-top:auto;display:block;}
.plan-card:hover{transform:translateY(-4px);}
.plan-card.featured{border-color:rgba(0,245,160,0.4);background:linear-gradient(160deg,rgba(0,245,160,0.06),var(--surface));}
.featured-badge{position:absolute;top:-12px;left:50%;transform:translateX(-50%);background:var(--accent);color:#04060a;font-family:'Clash Display',sans-serif;font-size:0.7rem;font-weight:700;letter-spacing:2px;text-transform:uppercase;padding:4px 14px;border-radius:99px;}
.plan-name{font-family:'Clash Display',sans-serif;font-size:1rem;font-weight:600;margin-bottom:8px;color:var(--muted);}
.plan-price{font-family:'Clash Display',sans-serif;font-size:3rem;font-weight:700;letter-spacing:-2px;line-height:1;margin-bottom:4px;}
.plan-price span{font-size:1rem;font-weight:400;color:var(--muted);}
.plan-storage{font-size:0.85rem;color:var(--muted);margin-bottom:28px;}
.plan-features{list-style:none;display:flex;flex-direction:column;gap:10px;margin-bottom:32px;}
.plan-features li{font-size:0.88rem;color:var(--muted);display:flex;align-items:center;gap:8px;}
.plan-features li::before{content:'✓';color:var(--accent);font-weight:700;flex-shrink:0;}
.plan-btn{width:100%;padding:12px;border-radius:10px;font-family:'Clash Display',sans-serif;font-weight:600;font-size:0.9rem;cursor:pointer;transition:all 0.25s;}
.plan-btn-outline{background:none;border:1px solid var(--border);color:var(--text);}
.plan-btn-outline:hover{border-color:var(--accent);color:var(--accent);}
.plan-btn-accent{background:var(--accent);border:none;color:#04060a;box-shadow:0 0 20px rgba(0,245,160,0.3);}
.plan-btn-accent:hover{box-shadow:0 0 35px rgba(0,245,160,0.5);}
/* Comparison table */
.table-wrap{max-width:900px;margin:0 auto;overflow-x:auto;}
table{width:100%;border-collapse:collapse;}
thead th{font-family:'Clash Display',sans-serif;font-size:0.88rem;padding:16px 20px;text-align:center;border-bottom:1px solid var(--border);}
thead th:first-child{text-align:left;}
thead th.hl{color:var(--accent);}
tbody td{padding:13px 20px;text-align:center;border-bottom:1px solid var(--border);font-size:0.85rem;color:var(--muted);}
tbody td:first-child{text-align:left;color:var(--text);}
.check{color:var(--accent);}
.cross{color:var(--accent3);}
/* FAQ */
.faq-list{max-width:700px;margin:0 auto;display:flex;flex-direction:column;gap:12px;}
.faq-item{background:var(--surface);border:1px solid var(--border);border-radius:12px;overflow:hidden;}
.faq-q{width:100%;background:none;border:none;color:var(--text);font-family:'Epilogue',sans-serif;font-size:0.95rem;padding:20px 24px;text-align:left;cursor:pointer;display:flex;justify-content:space-between;align-items:center;gap:12px;}
.faq-q:hover{color:var(--accent);}
.faq-icon{font-size:1.2rem;transition:transform 0.3s;flex-shrink:0;}
.faq-item.open .faq-icon{transform:rotate(45deg);}
.faq-a{max-height:0;overflow:hidden;transition:max-height 0.35s ease,padding 0.3s;}
.faq-item.open .faq-a{max-height:200px;padding:0 24px 20px;}
.faq-a p{color:var(--muted);font-size:0.88rem;line-height:1.7;}
/* Guarantee */
.guarantee{text-align:center;margin:0 auto 64px;max-width:400px;}
.guarantee-badge{display:inline-flex;align-items:center;gap:10px;background:rgba(255,215,0,0.08);border:1px solid rgba(255,215,0,0.2);border-radius:99px;padding:12px 24px;font-size:0.88rem;color:var(--gold);}
/* Testimonials */
.testimonials-grid{display:grid;grid-template-columns:1fr 1fr;gap:24px;max-width:800px;margin:0 auto 64px;}
.testimonial-card{background:var(--surface);border:1px solid var(--border);border-radius:16px;padding:28px;}
.testimonial-card blockquote{font-size:0.9rem;line-height:1.7;color:var(--text);margin-bottom:18px;font-style:italic;}
.testimonial-author{display:flex;align-items:center;gap:12px;}
.avatar{width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,var(--accent),var(--accent2));display:flex;align-items:center;justify-content:center;font-size:1rem;}
.author-name{font-family:'Clash Display',sans-serif;font-size:0.85rem;font-weight:600;}
.author-role{font-size:0.75rem;color:var(--muted);}
/* Enterprise */
.enterprise-cta{background:var(--surface);border:1px solid var(--border);border-radius:20px;padding:56px;text-align:center;max-width:700px;margin:0 auto;}
.enterprise-cta h2{font-family:'Clash Display',sans-serif;font-size:clamp(1.6rem,3vw,2.2rem);font-weight:700;letter-spacing:-1px;margin-bottom:12px;}
.enterprise-cta p{color:var(--muted);margin-bottom:28px;font-size:0.95rem;}
@media(max-width:900px){nav{padding:18px 24px;}.nav-links,.nav-cta{display:none;}.nav-hamburger{display:flex;}footer{padding:32px 24px;flex-direction:column;gap:20px;text-align:center;}.footer-links{flex-wrap:wrap;justify-content:center;}section{padding:60px 24px;}.hero{padding:130px 24px 60px;}.plans-grid{grid-template-columns:1fr;max-width:420px;}.testimonials-grid{grid-template-columns:1fr;}.enterprise-cta{padding:36px 24px;}}
@media(max-width:600px){nav{padding:16px 20px;}footer{padding:28px 20px;}}
</style>
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
  <div class="hero-label">Pricing</div>
  <h1>Simple pricing.<br>No surprises.</h1>
  <p>One plan for everyone. Upgrade when you're ready. Downgrade whenever you want. No hidden fees, ever.</p>
</div>

<section>
  <!-- Plans -->
  <div class="plans-grid">
    <div class="plan-card reveal">
      <div class="plan-name">Free</div>
      <div class="plan-price">$0<span>/mo</span></div>
      <div class="plan-storage">5 GB storage</div>
      <ul class="plan-features">
        <li>5 GB encrypted storage</li>
        <li>Max 500 MB per file</li>
        <li>5 active shared links</li>
        <li>7-day version history</li>
        <li>In-browser file preview</li>
      </ul>
      <a href="/register?plan=free"><button class="plan-btn plan-btn-outline">Get Started Free</button></a>
    </div>
    <div class="plan-card featured reveal">
      <div class="featured-badge">Most Popular</div>
      <div class="plan-name">Pro</div>
      <div class="plan-price" id="pro-price">$3<span id="pro-period">/mo</span></div>
      <div class="plan-storage">100 GB storage</div>
      <ul class="plan-features">
        <li>100 GB encrypted storage</li>
        <li>Max 10 GB per file</li>
        <li>Unlimited shared links</li>
        <li>90-day version history</li>
        <li>Self-destructing links</li>
        <li>AI Smart Search</li>
        <li>Auto camera backup</li>
      </ul>
      <a href="/register?plan=pro"><button class="plan-btn plan-btn-accent">Start Pro Free →</button></a>
    </div>
    <div class="plan-card reveal">
      <div class="plan-name">Teams</div>
      <div class="plan-price" id="teams-price">$8<span id="teams-period">/mo</span></div>
      <div class="plan-storage">500 GB shared storage</div>
      <ul class="plan-features">
        <li>500 GB shared storage</li>
        <li>Max 50 GB per file</li>
        <li>Unlimited shared links</li>
        <li>180-day version history</li>
        <li>Collaborative folders</li>
        <li>Full audit trail</li>
        <li>Priority support</li>
        <li>Admin dashboard</li>
      </ul>
      <a href="/register?plan=teams"><button class="plan-btn plan-btn-outline">Start Teams Free →</button></a>
    </div>
  </div>
</section>

<!-- Comparison Table -->
<section style="padding-top:0;">
  <div class="section-label">Full comparison</div>
  <h2 class="section-title">Everything side by side</h2>
  <div class="table-wrap reveal">
    <table>
      <thead>
        <tr><th>Feature</th><th>Free</th><th class="hl">Pro</th><th>Teams</th></tr>
      </thead>
      <tbody>
        <tr><td>Storage</td><td>5 GB</td><td class="hl">100 GB</td><td>500 GB</td></tr>
        <tr><td>Max file size</td><td>500 MB</td><td class="hl">10 GB</td><td>50 GB</td></tr>
        <tr><td>End-to-end encryption</td><td class="check">✓</td><td class="hl check">✓</td><td class="check">✓</td></tr>
        <tr><td>Zero-knowledge</td><td class="check">✓</td><td class="hl check">✓</td><td class="check">✓</td></tr>
        <tr><td>Version history</td><td>7 days</td><td class="hl">90 days</td><td>180 days</td></tr>
        <tr><td>Self-destruct links</td><td class="cross">✗</td><td class="hl check">✓</td><td class="check">✓</td></tr>
        <tr><td>AI Smart Search</td><td class="cross">✗</td><td class="hl check">✓</td><td class="check">✓</td></tr>
        <tr><td>Camera backup</td><td class="cross">✗</td><td class="hl check">✓</td><td class="check">✓</td></tr>
        <tr><td>Collaborative folders</td><td class="cross">✗</td><td class="cross hl">✗</td><td class="check">✓</td></tr>
        <tr><td>Audit trail</td><td class="cross">✗</td><td class="cross hl">✗</td><td class="check">✓</td></tr>
        <tr><td>Priority support</td><td class="cross">✗</td><td class="cross hl">✗</td><td class="check">✓</td></tr>
      </tbody>
    </table>
  </div>
</section>

<!-- FAQ -->
<section style="padding-top:0;">
  <div class="section-label">FAQ</div>
  <h2 class="section-title">Common questions</h2>
  <p class="section-sub">Everything you need to know before committing.</p>
  <div class="faq-list">
    <div class="faq-item reveal">
      <button class="faq-q">Can I switch plans?<span class="faq-icon">+</span></button>
      <div class="faq-a"><p>Yes, anytime. Upgrade or downgrade from your account settings. Upgrades take effect immediately. Downgrades apply at the end of your billing cycle, and you keep your current plan's features until then.</p></div>
    </div>
    <div class="faq-item reveal">
      <button class="faq-q">What happens if I exceed storage?<span class="faq-icon">+</span></button>
      <div class="faq-a"><p>We'll notify you when you're at 80% and 95% capacity. You won't lose any files — uploads will simply pause until you free up space or upgrade. No surprise charges, ever.</p></div>
    </div>
    <div class="faq-item reveal">
      <button class="faq-q">Is my data safe if I cancel?<span class="faq-icon">+</span></button>
      <div class="faq-a"><p>Absolutely. You have 30 days after cancellation to download your files. After that, they're permanently deleted from our servers — which is exactly what zero-knowledge means.</p></div>
    </div>
    <div class="faq-item reveal">
      <button class="faq-q">Can I share files with people who don't have an account?<span class="faq-icon">+</span></button>
      <div class="faq-a"><p>Yes. You can generate a secure link to any file and share it with anyone. The recipient doesn't need a Keeption account — just the link. Pro and Teams users can also set expiry dates and password-protect their shared links.</p></div>
    </div>

  </div>
</section>

<!-- Testimonials -->
<section style="padding-top:0;text-align:center;">
  <!-- Testimonials -->
  <div class="testimonials-grid" style="margin:0 auto;">
    <div class="testimonial-card reveal">
      <blockquote>"Switched from Dropbox and cut my bill by 60%. Same storage, way better privacy. The Pro plan is genuinely the best value in cloud storage right now."</blockquote>
      <div class="testimonial-author">
        <div class="avatar">👨</div>
        <div><div class="author-name">Tom Eriksson</div><div class="author-role">Pro user, 8 months</div></div>
      </div>
    </div>
    <div class="testimonial-card reveal">
      <blockquote>"The Teams plan pays for itself. Our whole studio shares one vault, version history has saved us twice, and the audit trail keeps everyone accountable."</blockquote>
      <div class="testimonial-author">
        <div class="avatar">👩</div>
        <div><div class="author-name">Camille Rousseau</div><div class="author-role">Creative Director, Teams user</div></div>
      </div>
    </div>
  </div>
</section>

<!-- Enterprise -->
<section style="padding-top:0;">
  <div class="enterprise-cta reveal">
    <h2>Need more than 500 GB?<br>Let's talk.</h2>
    <p>Custom storage, dedicated infrastructure, SSO, and SLA guarantees for large teams and enterprises.</p>
    <a href="#"><button class="btn-ghost" style="font-size:0.95rem;padding:12px 32px;">Contact Us →</button></a>
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
// FAQ accordion
document.querySelectorAll('.faq-q').forEach(btn=>{
  btn.addEventListener('click',()=>{
    const item=btn.parentElement;
    const wasOpen=item.classList.contains('open');
    document.querySelectorAll('.faq-item').forEach(i=>i.classList.remove('open'));
    if(!wasOpen)item.classList.add('open');
  });
});
</script>
</body>
</html>
