<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Keeption Vault — Your Files. Your Rules.</title>
<link rel="icon" type="image/png" href="/favicon.png"/>
<link href="https://fonts.googleapis.com/css2?family=Clash+Display:wght@400;500;600;700&family=Epilogue:wght@300;400;500&display=swap" rel="stylesheet"/>
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
<style>
:root {
  --bg: #04060a; --surface: #0c0f16; --border: rgba(255,255,255,0.07);
  --accent: #00f5a0; --accent2: #00d4ff; --accent3: #ff6b6b; --gold: #ffd700;
  --text: #eef0f6; --muted: #636b7d;
  --glow-green: rgba(0,245,160,0.18); --glow-blue: rgba(0,212,255,0.12);
}
*, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
html { scroll-behavior: smooth; }
body { font-family: 'Epilogue', sans-serif; background: var(--bg); color: var(--text); overflow-x: hidden; cursor: none; }
.cursor { position:fixed; width:10px; height:10px; background:var(--accent); border-radius:50%; pointer-events:none; z-index:9999; transform:translate(-50%,-50%); transition:transform 0.1s ease,width 0.3s,height 0.3s,background 0.3s; mix-blend-mode:difference; }
.cursor-ring { position:fixed; width:36px; height:36px; border:1.5px solid rgba(0,245,160,0.5); border-radius:50%; pointer-events:none; z-index:9998; transform:translate(-50%,-50%); transition:transform 0.12s ease,width 0.3s,height 0.3s; }
body::before { content:''; position:fixed; inset:0; background-image:url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E"); pointer-events:none; z-index:0; opacity:0.4; }
nav { position:fixed; top:0; left:0; right:0; z-index:1000; display:flex; align-items:center; justify-content:space-between; padding:22px 60px; background:rgba(4,6,10,0.6); backdrop-filter:blur(24px); border-bottom:1px solid var(--border); animation:slideDown 0.6s ease both; }
@keyframes slideDown { from{opacity:0;transform:translateY(-20px)} to{opacity:1;transform:translateY(0)} }
.nav-logo { font-family:'Clash Display',sans-serif; font-weight:700; font-size:1.3rem; letter-spacing:-0.5px; display:flex; align-items:center; gap:10px; }
.nav-logo-icon { width:32px; height:32px; background:linear-gradient(135deg,var(--accent),var(--accent2)); border-radius:9px; display:flex; align-items:center; justify-content:center; font-size:16px; }
.nav-logo em { color:var(--accent); font-style:normal; }
.nav-links { display:flex; gap:24px; list-style:none; }
.nav-links a { text-decoration:none; color:var(--muted); font-size:0.88rem; font-wei
ght:400; transition:color 0.2s; position:relative; }
.nav-links a::after { content:''; position:absolute; bottom:-3px; left:0; right:100%; height:1px; background:var(--accent); transition:right 0.25s ease; }
.nav-links a:hover { color:var(--text); }
.nav-links a:hover::after { right:0; }
.nav-cta { display:flex; gap:12px; align-items:center; }
.btn-ghost { background:none; border:1px solid var(--border); color:var(--muted); border-radius:8px; padding:8px 20px; font-family:'Epilogue',sans-serif; font-size:0.85rem; cursor:none; transition:all 0.2s; }
.btn-ghost:hover { border-color:var(--accent); color:var(--accent); }
.btn-glow { background:var(--accent); border:none; color:#04060a; border-radius:8px; padding:9px 22px; font-family:'Clash Display',sans-serif; font-weight:600; font-size:0.85rem; cursor:none; transition:all 0.25s; box-shadow:0 0 20px rgba(0,245,160,0.3); }
.btn-glow:hover { transform:translateY(-2px); box-shadow:0 0 35px rgba(0,245,160,0.5); }
.hero { position:relative; min-height:100vh; display:flex; flex-direction:column; align-items:center; justify-content:center; text-align:center; padding:120px 40px 80px; overflow:hidden; z-index:1; }
.hero-mesh { position:absolute; inset:0; background:radial-gradient(ellipse 80% 60% at 20% 40%,rgba(0,245,160,0.07) 0%,transparent 60%),radial-gradient(ellipse 60% 80% at 80% 60%,rgba(0,212,255,0.06) 0%,transparent 60%),radial-gradient(ellipse 40% 40% at 50% 80%,rgba(255,107,107,0.04) 0%,transparent 60%); animation:meshMove 10s ease-in-out infinite alternate; }
@keyframes meshMove { 0%{transform:scale(1) rotate(0deg)} 100%{transform:scale(1.05) rotate(1deg)} }
.hero-grid { position:absolute; inset:0; background-image:linear-gradient(rgba(255,255,255,0.02) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,0.02) 1px,transparent 1px); background-size:60px 60px; mask-image:radial-gradient(ellipse 80% 80% at 50% 50%,black 0%,transparent 100%); }
.particle { position:absolute; border-radius:50%; animation:float linear infinite; pointer-events:none; }
@keyframes float { 0%{transform:translateY(0px) translateX(0px) rotate(0deg);opacity:0} 10%{opacity:1} 90%{opacity:1} 100%{transform:translateY(-100vh) translateX(30px) rotate(360deg);opacity:0} }
.badge { display:inline-flex; align-items:center; gap:8px; background:rgba(0,245,160,0.08); border:1px solid rgba(0,245,160,0.2); border-radius:99px; padding:6px 16px; font-size:0.78rem; color:var(--accent); margin-bottom:32px; animation:fadeUp 0.7s 0.2s ease both; }
.badge-dot { width:6px; height:6px; border-radius:50%; background:var(--accent); animation:pulse 2s infinite; }
@keyframes pulse { 0%,100%{opacity:1} 50%{opacity:0.3} }
.hero-headline { font-family:'Clash Display',sans-serif; font-size:clamp(3rem,8vw,7rem); font-weight:700; line-height:1.0; letter-spacing:-3px; margin-bottom:28px; animation:fadeUp 0.7s 0.3s ease both; }
.hero-headline .line2 { display:block; background:linear-gradient(90deg,var(--accent),var(--accent2)); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.hero-headline .line3 { display:block; color:var(--muted); font-size:0.55em; letter-spacing:-1px; }
.hero-sub { max-width:560px; margin:0 auto 44px; font-size:1.05rem; line-height:1.7; color:var(--muted); font-weight:300; animation:fadeUp 0.7s 0.4s ease both; }
.hero-sub strong { color:var(--text); font-weight:500; }
.hero-actions { display:flex; gap:14px; justify-content:center; flex-wrap:wrap; margin-bottom:60px; animation:fadeUp 0.7s 0.5s ease both; }
.btn-hero-primary { display:flex; align-items:center; gap:10px; background:var(--accent); color:#04060a; border:none; border-radius:12px; padding:14px 32px; font-family:'Clash Display',sans-serif; font-weight:600; font-size:1rem; cursor:none; transition:all 0.3s; box-shadow:0 0 40px rgba(0,245,160,0.25); }
.btn-hero-primary:hover { transform:translateY(-3px); box-shadow:0 0 60px rgba(0,245,160,0.4); }
.btn-hero-secondary { display:flex; align-items:center; gap:10px; background:none; border:1px solid var(--border); color:var(--text); border-radius:12px; padding:14px 32px; font-family:'Epilogue',sans-serif; font-size:1rem; cursor:none; transition:all 0.3s; }
.btn-hero-secondary:hover { border-color:rgba(255,255,255,0.2); transform:translateY(-2px); }
.free-storage-pill { display:inline-flex; align-items:center; gap:6px; background:rgba(255,215,0,0.08); border:1px solid rgba(255,215,0,0.2); border-radius:99px; padding:5px 14px; font-size:0.78rem; color:var(--gold); margin-bottom:60px; animation:fadeUp 0.7s 0.6s ease both; }
.hero-preview { position:relative; max-width:1000px; width:100%; animation:fadeUp 0.9s 0.7s ease both; }
.preview-glow { position:absolute; top:-60px; left:50%; transform:translateX(-50%); width:700px; height:200px; background:radial-gradient(ellipse,rgba(0,245,160,0.15) 0%,transparent 70%); pointer-events:none; }
.preview-frame { background:var(--surface); border:1px solid var(--border); border-radius:20px; overflow:hidden; box-shadow:0 40px 120px rgba(0,0,0,0.7),0 0 0 1px rgba(255,255,255,0.04); }
.preview-bar { background:rgba(255,255,255,0.03); border-bottom:1px solid var(--border); padding:12px 20px; display:flex; align-items:center; gap:10px; }
.preview-dots { display:flex; gap:6px; }
.preview-dots span { width:10px; height:10px; border-radius:50%; }
.preview-url { flex:1; background:rgba(255,255,255,0.05); border-radius:6px; padding:5px 14px; font-size:0.75rem; color:var(--muted); text-align:center; }
.preview-body { display:grid; grid-template-columns:200px 1fr; height:380px; }
.preview-sidebar { background:rgba(255,255,255,0.02); border-right:1px solid var(--border); padding:16px 12px; display:flex; flex-direction:column; gap:4px; }
.ps-logo { font-family:'Clash Display',sans-serif; font-weight:700; font-size:0.95rem; color:var(--accent); margin-bottom:16px; padding:0 8px; display:flex; align-items:center; gap:8px; }
.ps-item { padding:8px 10px; border-radius:8px; font-size:0.75rem; color:var(--muted); display:flex; align-items:center; gap:8px; transition:all 0.15s; }
.ps-item.active { background:rgba(0,245,160,0.08); color:var(--accent); }
.ps-storage { margin-top:auto; background:rgba(255,255,255,0.03); border-radius:10px; padding:10px; }
.ps-bar { height:4px; background:rgba(255,255,255,0.07); border-radius:99px; margin:6px 0; }
.ps-fill { height:100%; width:40%; background:linear-gradient(90deg,var(--accent),var(--accent2)); border-radius:99px; }
.ps-storage-text { font-size:0.65rem; color:var(--muted); }
.preview-main { padding:20px; overflow:hidden; }
.pm-header { font-family:'Clash Display',sans-serif; font-size:1.1rem; font-weight:600; margin-bottom:16px; color:var(--text); }
.pm-tabs { display:flex; gap:6px; margin-bottom:18px; }
.pm-tab { padding:5px 12px; border-radius:6px; font-size:0.72rem; cursor:default; border:1px solid var(--border); color:var(--muted); }
.pm-tab.active { background:rgba(0,245,160,0.1); border-color:rgba(0,245,160,0.3); color:var(--accent); }
.pm-grid { display:grid; grid-template-columns:repeat(5,1fr); gap:10px; margin-bottom:16px; }
.pm-file { background:rgba(255,255,255,0.03); border:1px solid var(--border); border-radius:10px; padding:10px 8px; text-align:center; transition:all 0.2s; }
.pm-file:hover { border-color:rgba(0,245,160,0.3); transform:translateY(-2px); }
.pm-file-icon { font-size:22px; margin-bottom:6px; }
.pm-file-name { font-size:0.62rem; color:var(--muted); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.pm-file-size { font-size:0.58rem; color:rgba(255,255,255,0.2); margin-top:2px; }
.pm-upload-zone { border:1.5px dashed rgba(0,245,160,0.2); border-radius:10px; padding:14px; text-align:center; background:rgba(0,245,160,0.02); }
.pm-upload-zone p { font-size:0.7rem; color:var(--muted); }
.pm-upload-zone span { color:var(--accent); }
.stats-bar { position:relative; z-index:1; background:var(--surface); border-top:1px solid var(--border); border-bottom:1px solid var(--border); padding:40px 60px; display:grid; grid-template-columns:repeat(4,1fr); gap:0; }
.stat-item { text-align:center; padding:0 40px; border-right:1px solid var(--border); }
.stat-item:last-child { border-right:none; }
.stat-num { font-family:'Clash Display',sans-serif; font-size:2.4rem; font-weight:700; background:linear-gradient(90deg,var(--accent),var(--accent2)); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; margin-bottom:4px; }
.stat-desc { font-size:0.85rem; color:var(--muted); }
.features { position:relative; z-index:1; padding:100px 60px; }
.section-label { text-align:center; font-size:0.75rem; letter-spacing:3px; text-transform:uppercase; color:var(--accent); margin-bottom:16px; }
.section-title { font-family:'Clash Display',sans-serif; font-size:clamp(2rem,4vw,3.2rem); font-weight:700; text-align:center; letter-spacing:-1.5px; margin-bottom:16px; line-height:1.1; }
.section-sub { text-align:center; color:var(--muted); max-width:500px; margin:0 auto 64px; font-size:0.95rem; line-height:1.7; }
.features-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:20px; max-width:1100px; margin:0 auto; }
.feat-card { background:var(--surface); border:1px solid var(--border); border-radius:20px; padding:32px; position:relative; overflow:hidden; transition:all 0.3s; }
.feat-card:hover { border-color:rgba(255,255,255,0.12); transform:translateY(-4px); box-shadow:0 20px 60px rgba(0,0,0,0.4); }
.feat-card::after { content:''; position:absolute; top:0; left:0; right:0; height:1px; }
.feat-card:nth-child(1)::after{background:linear-gradient(90deg,var(--accent),transparent)}
.feat-card:nth-child(2)::after{background:linear-gradient(90deg,var(--accent2),transparent)}
.feat-card:nth-child(3)::after{background:linear-gradient(90deg,var(--accent3),transparent)}
.feat-card:nth-child(4)::after{background:linear-gradient(90deg,var(--gold),transparent)}
.feat-card:nth-child(5)::after{background:linear-gradient(90deg,#a78bfa,transparent)}
.feat-card:nth-child(6)::after{background:linear-gradient(90deg,#fb923c,transparent)}
.feat-icon { width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:22px; margin-bottom:20px; }
.feat-title { font-family:'Clash Display',sans-serif; font-size:1.1rem; font-weight:600; margin-bottom:10px; }
.feat-desc { font-size:0.85rem; color:var(--muted); line-height:1.65; }
.feat-tag { display:inline-block; margin-top:16px; font-size:0.7rem; padding:3px 10px; border-radius:99px; border:1px solid; }
.showcase { position:relative; z-index:1; padding:80px 60px; background:var(--surface); border-top:1px solid var(--border); border-bottom:1px solid var(--border); overflow:hidden; }
.showcase-grid { display:grid; grid-template-columns:1fr 1fr; gap:40px; max-width:1100px; margin:0 auto; align-items:center; }
.showcase-text { padding-right:40px; }
.showcase-title { font-family:'Clash Display',sans-serif; font-size:2.5rem; font-weight:700; letter-spacing:-1.5px; line-height:1.1; margin-bottom:20px; }
.showcase-title span { color:var(--accent); }
.showcase-desc { color:var(--muted); line-height:1.7; margin-bottom:28px; font-size:0.95rem; }
.type-list { display:flex; flex-direction:column; gap:12px; }
.type-row { display:flex; align-items:center; gap:14px; padding:12px 16px; border-radius:12px; background:rgba(255,255,255,0.02); border:1px solid var(--border); transition:all 0.2s; }
.type-row:hover { border-color:rgba(0,245,160,0.2); background:rgba(0,245,160,0.03); }
.type-icon { font-size:20px; }
.type-info { flex:1; }
.type-name { font-size:0.88rem; font-weight:500; }
.type-formats { font-size:0.73rem; color:var(--muted); margin-top:1px; }
.type-enc { font-size:0.7rem; color:var(--accent); }
.showcase-visual { position:relative; height:400px; }
.file-float { position:absolute; background:var(--bg); border:1px solid var(--border); border-radius:16px; padding:16px 18px; display:flex; align-items:center; gap:12px; box-shadow:0 20px 60px rgba(0,0,0,0.5); transition:transform 0.3s ease; animation:floatCard ease-in-out infinite alternate; }
.file-float:hover { transform:scale(1.03) !important; }
@keyframes floatCard { from{transform:translateY(0px)} to{transform:translateY(-10px)} }
.ff-icon { font-size:28px; }
.ff-name { font-size:0.82rem; font-weight:500; }
.ff-size { font-size:0.7rem; color:var(--muted); }
.ff-enc { font-size:0.65rem; color:var(--accent); margin-top:3px; display:flex; align-items:center; gap:4px; }
.pricing { position:relative; z-index:1; padding:100px 60px; }
.pricing-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:20px; max-width:900px; margin:0 auto; }
.price-card { background:var(--surface); border:1px solid var(--border); border-radius:22px; padding:36px; position:relative; transition:all 0.3s; }
.price-card:hover { transform:translateY(-4px); box-shadow:0 20px 60px rgba(0,0,0,0.4); }
.price-card.featured { border-color:rgba(0,245,160,0.3); background:linear-gradient(160deg,rgba(0,245,160,0.05),var(--surface)); }
.price-badge { position:absolute; top:-12px; left:50%; transform:translateX(-50%); background:var(--accent); color:#04060a; font-family:'Clash Display',sans-serif; font-weight:700; font-size:0.72rem; padding:4px 14px; border-radius:99px; }
.price-plan { font-family:'Clash Display',sans-serif; font-weight:600; font-size:0.9rem; text-transform:uppercase; letter-spacing:1px; color:var(--muted); margin-bottom:16px; }
.price-amount { font-family:'Clash Display',sans-serif; font-size:3rem; font-weight:700; letter-spacing:-2px; margin-bottom:4px; }
.price-amount span { font-size:1.2rem; color:var(--muted); }
.price-period { font-size:0.8rem; color:var(--muted); margin-bottom:28px; }
.price-features { list-style:none; display:flex; flex-direction:column; gap:10px; margin-bottom:28px; }
.price-features li { display:flex; align-items:center; gap:10px; font-size:0.85rem; color:var(--muted); }
.price-features li::before { content:'✓'; color:var(--accent); font-weight:700; flex-shrink:0; }
.price-btn { width:100%; padding:12px; border-radius:10px; font-family:'Clash Display',sans-serif; font-weight:600; font-size:0.9rem; cursor:none; transition:all 0.25s; }
.price-btn-outline { background:none; border:1px solid var(--border); color:var(--text); }
.price-btn-outline:hover { border-color:var(--accent); color:var(--accent); }
.price-btn-filled { background:var(--accent); border:none; color:#04060a; box-shadow:0 0 25px rgba(0,245,160,0.25); }
.price-btn-filled:hover { box-shadow:0 0 40px rgba(0,245,160,0.4); transform:translateY(-2px); }
.cta-section { position:relative; z-index:1; padding:100px 60px; text-align:center; overflow:hidden; }
.cta-glow { position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); width:800px; height:400px; background:radial-gradient(ellipse,rgba(0,245,160,0.08) 0%,transparent 70%); pointer-events:none; }
.cta-title { font-family:'Clash Display',sans-serif; font-size:clamp(2.5rem,5vw,4.5rem); font-weight:700; letter-spacing:-2px; line-height:1.05; margin-bottom:20px; }
.cta-title .highlight { background:linear-gradient(90deg,var(--accent),var(--accent2)); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.cta-sub { color:var(--muted); font-size:1rem; margin-bottom:40px; }
.cta-input-group { display:flex; gap:12px; justify-content:center; max-width:460px; margin:0 auto 20px; }
.cta-input { flex:1; background:var(--surface); border:1px solid var(--border); border-radius:10px; padding:12px 18px; color:var(--text); font-family:'Epilogue',sans-serif; font-size:0.9rem; outline:none; transition:border-color 0.2s; }
.cta-input:focus { border-color:var(--accent); }
.cta-input::placeholder { color:var(--muted); }
.cta-note { font-size:0.78rem; color:var(--muted); }
.cta-note strong { color:var(--accent); }
footer { position:relative; z-index:1; background:var(--surface); border-top:1px solid var(--border); padding:48px 60px; display:flex; align-items:center; justify-content:space-between; }
.footer-logo { font-family:'Clash Display',sans-serif; font-weight:700; font-size:1.1rem; }
.footer-logo em { color:var(--accent); font-style:normal; }
.footer-copy { font-size:0.8rem; color:var(--muted); }
.footer-links { display:flex; gap:24px; }
.footer-links a { font-size:0.8rem; color:var(--muted); text-decoration:none; transition:color 0.2s; }
.footer-links a:hover { color:var(--accent); }
@keyframes fadeUp { from{opacity:0;transform:translateY(24px)} to{opacity:1;transform:translateY(0)} }
::-webkit-scrollbar { width:4px; }
::-webkit-scrollbar-track { background:var(--bg); }
::-webkit-scrollbar-thumb { background:var(--surface); border-radius:99px; }

/* ── HAMBURGER ── */
.nav-hamburger { display:none; flex-direction:column; gap:5px; cursor:pointer; padding:4px; background:none; border:none; }
.nav-hamburger span { display:block; width:22px; height:2px; background:var(--text); border-radius:99px; transition:all 0.3s; }
.nav-hamburger.open span:nth-child(1) { transform:translateY(7px) rotate(45deg); }
.nav-hamburger.open span:nth-child(2) { opacity:0; }
.nav-hamburger.open span:nth-child(3) { transform:translateY(-7px) rotate(-45deg); }
.nav-mobile { display:none; position:fixed; top:70px; left:0; right:0; background:rgba(4,6,10,0.97); backdrop-filter:blur(24px); border-bottom:1px solid var(--border); padding:20px 24px; flex-direction:column; gap:4px; z-index:999; }
.nav-mobile.open { display:flex; }
.nav-mobile a { text-decoration:none; color:var(--muted); font-size:1rem; padding:12px 8px; border-bottom:1px solid var(--border); transition:color 0.2s; }
.nav-mobile a:last-child { border-bottom:none; }
.nav-mobile a:hover { color:var(--accent); }
.nav-mobile-cta { display:flex; gap:10px; margin-top:12px; }
.nav-mobile-cta button { flex:1; }

/* ── TABLET (≤ 1100px) ── */
@media (max-width: 1100px) {
  nav { padding:18px 24px; }
  .nav-links { display:none; }
  .nav-cta { display:none; }
  .nav-hamburger { display:flex; }

  .hero { padding:100px 24px 60px; }
  .hero-headline { letter-spacing:-1.5px; }

  .stats-bar { grid-template-columns:repeat(2,1fr); padding:30px 24px; gap:0; }
  .stat-item { padding:20px; border-right:none; border-bottom:1px solid var(--border); }
  .stat-item:nth-child(odd) { border-right:1px solid var(--border); }
  .stat-item:nth-child(3), .stat-item:nth-child(4) { border-bottom:none; }

  .features { padding:60px 24px; }
  .features-grid { grid-template-columns:repeat(2,1fr); }

  .showcase { padding:60px 24px; }
  .showcase-grid { grid-template-columns:1fr; gap:40px; }
  .showcase-text { padding-right:0; }
  .showcase-visual { height:320px; overflow:hidden; }
  .file-float { max-width:280px; }

  .pricing { padding:60px 24px; }
  .pricing-grid { grid-template-columns:1fr; max-width:420px; }

  .cta-section { padding:60px 24px; }
  .cta-input-group { flex-direction:column; }

  footer { padding:32px 24px; flex-direction:column; gap:20px; text-align:center; }
  .footer-links { flex-wrap:wrap; justify-content:center; }
}

/* ── MOBILE (≤ 600px) ── */
@media (max-width: 600px) {
  nav { padding:16px 20px; }

  .hero { padding:90px 20px 50px; }
  .hero-headline { letter-spacing:-1px; }
  .hero-sub { font-size:0.95rem; }
  .btn-hero-primary { width:100%; justify-content:center; }

  .badge { font-size:0.7rem; padding:5px 12px; text-align:center; }

  .preview-body { grid-template-columns:1fr; height:auto; }
  .preview-sidebar { display:none; }
  .preview-frame { border-radius:14px; }

  .stats-bar { grid-template-columns:1fr 1fr; padding:20px 16px; }
  .stat-item { padding:16px 8px; }
  .stat-num { font-size:1.8rem; }

  .features { padding:50px 16px; }
  .features-grid { grid-template-columns:1fr; }

  .showcase { padding:50px 16px; overflow:hidden; }
  .showcase-visual { height:420px; overflow:hidden; position:relative; }
  .file-float { padding:10px 12px; gap:8px; max-width:260px; }
  .ff-icon { font-size:20px; }
  .ff-name { font-size:0.72rem; }

  .pricing { padding:50px 16px; }
  .pricing-grid { max-width:100%; }
  .price-card { padding:28px 20px; }

  .cta-section { padding:50px 16px; }
  .cta-input-group { flex-direction:column; }
  .cta-input { width:100%; }

  footer { padding:28px 20px; gap:16px; }
  .footer-links { gap:16px; }

  .section-title { letter-spacing:-0.5px; }
  .showcase-title { font-size:2rem; letter-spacing:-1px; }
}
</style>
</head>
<body>
<div class="cursor" id="cursor"></div>
<div class="cursor-ring" id="cursorRing"></div>
<div class="particle" style="left:10%;width:3px;height:3px;background:var(--accent);opacity:0.4;animation-duration:12s;animation-delay:0s;top:100%"></div>
<div class="particle" style="left:25%;width:2px;height:2px;background:var(--accent2);opacity:0.3;animation-duration:18s;animation-delay:3s;top:100%"></div>
<div class="particle" style="left:60%;width:4px;height:4px;background:var(--accent);opacity:0.2;animation-duration:14s;animation-delay:6s;top:100%"></div>
<div class="particle" style="left:80%;width:2px;height:2px;background:var(--accent3);opacity:0.4;animation-duration:16s;animation-delay:2s;top:100%"></div>
<div class="particle" style="left:45%;width:3px;height:3px;background:var(--accent2);opacity:0.3;animation-duration:20s;animation-delay:8s;top:100%"></div>

<nav>
  <a href="/" class="nav-logo" style="text-decoration:none;color:inherit;"><img src="/favicon.png" alt="Keeption Vault" style="width:32px;height:32px;border-radius:9px;object-fit:contain;"/>Keeption<em> Vault</em></a>
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
  <button class="nav-hamburger" id="hamburger" aria-label="Toggle menu">
    <span></span><span></span><span></span>
  </button>
</nav>
<div class="nav-mobile" id="navMobile">
  <a href="/">Home</a>
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

<section class="hero">
  <div class="hero-mesh"></div>
  <div class="hero-grid"></div>
  <div class="badge"><span class="badge-dot"></span>🔐 Zero-knowledge encrypted · No tracking · No ads</div>
  <h1 class="hero-headline">Your Files.<br><span class="line2">Your Rules.</span><span class="line3">Store everything. Share your way. Stay private.</span></h1>
  <p class="hero-sub">The cloud storage platform that <strong>actually respects you</strong>. Upload photos, videos, music, and files — with end-to-end encryption, social sharing, and <strong>5GB free</strong> storage.</p>
  <div class="hero-actions">
    <a href="/register"><button class="btn-hero-primary">☁️ Get Started Free</button></a>
  </div>
  <p style="font-size:0.88rem;color:var(--muted);max-width:480px;margin:0 auto 60px;line-height:1.6;animation:fadeUp 0.7s 0.6s ease both;">Designed to be dead simple — drag, drop, done. No confusing menus, no steep learning curve. If you can use a smartphone, you can use Keeption Vault.</p>
  <div class="hero-preview">
    <div class="preview-glow"></div>
    <div class="preview-frame">
      <div class="preview-bar">
        <div class="preview-dots">
          <span style="background:#ff5f57"></span>
          <span style="background:#febc2e"></span>
          <span style="background:#28c840"></span>
        </div>
        <div class="preview-url">🔒 &nbsp; app.nimbvault.io/dashboard</div>
      </div>
      <div class="preview-body">
        <div class="preview-sidebar">
          <div class="ps-logo">☁ Keeption Vault</div>
          <div class="ps-item active">🏠 &nbsp; Home</div>
          <div class="ps-item">🖼️ &nbsp; Photos</div>
          <div class="ps-item">🎵 &nbsp; Music</div>
          <div class="ps-item">🎬 &nbsp; Videos</div>
          <div class="ps-item">📁 &nbsp; Files</div>
          <div class="ps-item">👥 &nbsp; Shared</div>
          <div class="ps-item">🔗 &nbsp; Links</div>
          <div class="ps-storage">
            <div class="ps-storage-text">4.2 / 5 GB used</div>
            <div class="ps-bar"><div class="ps-fill"></div></div>
            <div class="ps-storage-text" style="color:var(--accent)">⬆ Upgrade</div>
          </div>
        </div>
        <div class="preview-main">
          <div class="pm-header">Good morning, Jordan 👋</div>
          <div class="pm-tabs">
            <div class="pm-tab active">All</div>
            <div class="pm-tab">📷 Photos</div>
            <div class="pm-tab">🎬 Videos</div>
            <div class="pm-tab">🎵 Music</div>
            <div class="pm-tab">📄 Docs</div>
          </div>
          <div class="pm-grid">
            <div class="pm-file"><div class="pm-file-icon">🖼️</div><div class="pm-file-name">vacation.jpg</div><div class="pm-file-size">4.2 MB</div></div>
            <div class="pm-file"><div class="pm-file-icon">🎵</div><div class="pm-file-name">lofi_mix.mp3</div><div class="pm-file-size">8.7 MB</div></div>
            <div class="pm-file"><div class="pm-file-icon">🎬</div><div class="pm-file-name">birthday.mp4</div><div class="pm-file-size">312 MB</div></div>
            <div class="pm-file"><div class="pm-file-icon">📄</div><div class="pm-file-name">brief.pdf</div><div class="pm-file-size">1.1 MB</div></div>
            <div class="pm-file"><div class="pm-file-icon">📊</div><div class="pm-file-name">report.xlsx</div><div class="pm-file-size">540 KB</div></div>
          </div>
          <div class="pm-upload-zone"><p>☁️ &nbsp; <span>Drop files here</span> or click to upload</p></div>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="stats-bar">
  <div class="stat-item"><div class="stat-num">5GB</div><div class="stat-desc">Free Storage — Forever</div></div>
  <div class="stat-item"><div class="stat-num">100%</div><div class="stat-desc">End-to-End Encrypted</div></div>
  <div class="stat-item"><div class="stat-num">0</div><div class="stat-desc">Ads. Zero. None. Ever.</div></div>
  <div class="stat-item"><div class="stat-num">∞</div><div class="stat-desc">File Types Supported</div></div>
</div>

<section class="features">
  <div class="section-label">Why Keeption Vault</div>
  <h2 class="section-title">Built differently.<br>On purpose.</h2>
  <p class="section-sub">Every feature was designed with one question: would we want this if we were the user?</p>
  <div class="features-grid">
    <div class="feat-card"><div class="feat-icon" style="background:rgba(0,245,160,0.1)">🔐</div><div class="feat-title">Zero-Knowledge Encryption</div><div class="feat-desc">Your files are encrypted before they leave your device. Not even we can see what you store.</div><span class="feat-tag" style="border-color:rgba(0,245,160,0.3);color:var(--accent)">🔒 Privacy-first</span></div>
    <div class="feat-card"><div class="feat-icon" style="background:rgba(0,212,255,0.1)">👥</div><div class="feat-title">Social Sharing & Profiles</div><div class="feat-desc">Create a public profile to showcase your creative work. Share albums, playlists, or galleries with followers.</div><span class="feat-tag" style="border-color:rgba(0,212,255,0.3);color:var(--accent2)">🌍 Community</span></div>
    <div class="feat-card"><div class="feat-icon" style="background:rgba(255,107,107,0.1)">💀</div><div class="feat-title">Self-Destructing Links</div><div class="feat-desc">Share files with links that expire automatically — after a set time, number of views, or on demand.</div><span class="feat-tag" style="border-color:rgba(255,107,107,0.3);color:var(--accent3)">⏳ Auto-expire</span></div>
    <div class="feat-card"><div class="feat-icon" style="background:rgba(255,215,0,0.1)">🤖</div><div class="feat-title">AI Smart Search</div><div class="feat-desc">Search inside photos, documents, and audio transcripts. Find "beach sunset 2023" and it just works.</div><span class="feat-tag" style="border-color:rgba(255,215,0,0.3);color:var(--gold)">✨ AI-powered</span></div>
    <div class="feat-card"><div class="feat-icon" style="background:rgba(167,139,250,0.1)">🔄</div><div class="feat-title">Import from Anywhere</div><div class="feat-desc">One-click migration from Google Drive, Dropbox, or iCloud. Bring everything you already have.</div><span class="feat-tag" style="border-color:rgba(167,139,250,0.3);color:#a78bfa">📦 Easy switch</span></div>
    <div class="feat-card"><div class="feat-icon" style="background:rgba(251,146,60,0.1)">📱</div><div class="feat-title">Auto Camera Backup</div><div class="feat-desc">Every photo and video you take gets silently backed up in the background. Private. Always-on.</div><span class="feat-tag" style="border-color:rgba(251,146,60,0.3);color:#fb923c">📷 Mobile-first</span></div>
  </div>
</section>

<section class="showcase">
  <div class="showcase-grid">
    <div class="showcase-text">
      <div class="section-label" style="text-align:left;margin-bottom:12px">File Types</div>
      <div class="showcase-title">Everything you<br>own. <span>Protected.</span></div>
      <p class="showcase-desc">From 4K videos to raw audio stems, from design files to spreadsheets — Keeption Vault handles every format with full encryption and instant streaming.</p>
      <div class="type-list">
        <div class="type-row"><div class="type-icon">🖼️</div><div class="type-info"><div class="type-name">Images & Photos</div><div class="type-formats">JPG, PNG, RAW, HEIC, GIF, WebP, SVG</div></div><div class="type-enc">🔐 Encrypted</div></div>
        <div class="type-row"><div class="type-icon">🎬</div><div class="type-info"><div class="type-name">Videos</div><div class="type-formats">MP4, MOV, AVI, MKV, 4K, 8K supported</div></div><div class="type-enc">🔐 Encrypted</div></div>
        <div class="type-row"><div class="type-icon">🎵</div><div class="type-info"><div class="type-name">Music & Audio</div><div class="type-formats">MP3, FLAC, WAV, AAC, OGG — stream in-app</div></div><div class="type-enc">🔐 Encrypted</div></div>
        <div class="type-row"><div class="type-icon">📄</div><div class="type-info"><div class="type-name">Documents & Files</div><div class="type-formats">PDF, DOCX, XLSX, ZIP, any format</div></div><div class="type-enc">🔐 Encrypted</div></div>
      </div>
    </div>
    <div class="showcase-visual">
      <div class="file-float" style="top:30px;left:60px;animation-duration:4s;z-index:4"><div class="ff-icon">🖼️</div><div><div class="ff-name">sunset_maldives.jpg</div><div class="ff-size">8.4 MB · 2 hrs ago</div><div class="ff-enc">🔐 Encrypted</div></div></div>
      <div class="file-float" style="top:140px;left:20px;animation-duration:5s;animation-delay:1s;z-index:3"><div class="ff-icon">🎬</div><div><div class="ff-name">wedding_highlight.mp4</div><div class="ff-size">1.2 GB · Shared</div><div class="ff-enc">🔐 Encrypted</div></div></div>
      <div class="file-float" style="top:250px;left:80px;animation-duration:6s;animation-delay:2s;z-index:2"><div class="ff-icon">🎵</div><div><div class="ff-name">album_masters.zip</div><div class="ff-size">340 MB · Private</div><div class="ff-enc">🔐 Encrypted</div></div></div>
      <div class="file-float" style="top:340px;left:30px;animation-duration:4.5s;animation-delay:0.5s;z-index:1"><div class="ff-icon">📄</div><div><div class="ff-name">contracts_2025.pdf</div><div class="ff-size">2.1 MB · Self-destruct in 3d</div><div class="ff-enc">🔐 Zero-knowledge</div></div></div>
    </div>
  </div>
</section>

<section class="pricing">
  <div class="section-label">Pricing</div>
  <h2 class="section-title">Simple. Honest. Generous.</h2>
  <p class="section-sub">More free storage than any competitor. Upgrade only when you truly need to.</p>
  <div class="pricing-grid">
    <div class="price-card">
      <div class="price-plan">Free</div>
      <div class="price-amount">$0<span>/mo</span></div>
      <div class="price-period">Forever free — no credit card</div>
      <ul class="price-features">
        <li>5 GB storage</li><li>All file types</li><li>End-to-end encryption</li><li>5 shared links</li><li>Mobile + desktop apps</li>
      </ul>
      <button class="price-btn price-btn-outline">Get Started Free</button>
    </div>
    <div class="price-card featured">
      <div class="price-badge">Most Popular</div>
      <div class="price-plan">Pro</div>
      <div class="price-amount">$3<span>/mo</span></div>
      <div class="price-period">Billed annually · $36/yr</div>
      <ul class="price-features">
        <li>100 GB storage</li><li>Unlimited shared links</li><li>Self-destructing links</li><li>AI smart search</li><li>Public creator profile</li><li>Version history (1 yr)</li>
      </ul>
      <button class="price-btn price-btn-filled">Start Pro Trial</button>
    </div>
    <div class="price-card">
      <div class="price-plan">Teams</div>
      <div class="price-amount">$8<span>/mo</span></div>
      <div class="price-period">Per user · min 3 users</div>
      <ul class="price-features">
        <li>500 GB shared storage</li><li>Collaborative folders</li><li>Admin controls</li><li>Custom domain sharing</li><li>Priority support</li><li>Audit logs</li>
      </ul>
      <button class="price-btn price-btn-outline">Contact Sales</button>
    </div>
  </div>
</section>

<section class="cta-section">
  <div class="cta-glow"></div>
  <h2 class="cta-title">Your files deserve<br><span class="highlight">better than this.</span></h2>
  <p class="cta-sub">Join thousands who already switched. 5GB free, always encrypted, zero ads.</p>
  <div class="cta-input-group">
    <input class="cta-input" type="email" placeholder="Enter your email address" />
    <button class="btn-glow" style="padding:12px 24px;font-size:0.9rem;border-radius:10px;white-space:nowrap">Start Free →</button>
  </div>
  <p class="cta-note">No credit card · <strong>5GB free</strong> · Cancel anytime</p>
</section>

<footer>
  <div class="footer-logo">Keeption<em> Vault</em></div>
  <div class="footer-copy">© 2025 Keeption Vault. Your files. Your rules.</div>
  <div class="footer-links">
    <a href="#">Privacy</a><a href="#">Terms</a><a href="#">Security</a><a href="#">Blog</a><a href="#">Contact</a>
  </div>
</footer>

<script>
const cursor = document.getElementById('cursor');
const ring = document.getElementById('cursorRing');
let mx = 0, my = 0, rx = 0, ry = 0;
document.addEventListener('mousemove', e => {
  mx = e.clientX; my = e.clientY;
  cursor.style.left = mx + 'px';
  cursor.style.top = my + 'px';
});
function animRing() {
  rx += (mx - rx) * 0.12;
  ry += (my - ry) * 0.12;
  ring.style.left = rx + 'px';
  ring.style.top = ry + 'px';
  requestAnimationFrame(animRing);
}
animRing();

// Hamburger toggle
const hamburger = document.getElementById('hamburger');
const navMobile = document.getElementById('navMobile');
hamburger.addEventListener('click', () => {
  hamburger.classList.toggle('open');
  navMobile.classList.toggle('open');
});
// Close on link click
navMobile.querySelectorAll('a').forEach(a => {
  a.addEventListener('click', () => { hamburger.classList.remove('open'); navMobile.classList.remove('open'); });
});
document.querySelectorAll('button, a, .feat-card, .price-card, .type-row, .pm-file, .file-float').forEach(el => {
  el.addEventListener('mouseenter', () => { cursor.style.width='16px'; cursor.style.height='16px'; ring.style.width='50px'; ring.style.height='50px'; });
  el.addEventListener('mouseleave', () => { cursor.style.width='10px'; cursor.style.height='10px'; ring.style.width='36px'; ring.style.height='36px'; });
});
const observer = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) { entry.target.style.opacity='1'; entry.target.style.transform='translateY(0)'; }
  });
}, { threshold: 0.1 });
document.querySelectorAll('.feat-card, .price-card, .type-row, .stat-item').forEach(el => {
  el.style.opacity = '0';
  el.style.transform = 'translateY(30px)';
  el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
  observer.observe(el);
});
</script>
</body>
</html>
