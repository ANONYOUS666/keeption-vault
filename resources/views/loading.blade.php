<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<meta name="theme-color" content="#050810"/>
<title>Keeption Vault</title>
<link href="https://fonts.googleapis.com/css2?family=Clash+Display:wght@600;700&family=Epilogue:wght@300;400&display=swap" rel="stylesheet"/>
<style>
:root{--bg:#050810;--surface:#0c0f18;--green:#00f5a0;--cyan:#00d4ff;--blue:#0055cc;--white:#eef0f6;--muted:#3a4255;--muted2:#5a6380;}
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box;}
html,body{width:100%;height:100%;overflow:hidden;}
body{background:var(--bg);display:flex;flex-direction:column;align-items:center;justify-content:center;font-family:'Epilogue',sans-serif;color:var(--white);}
.bg-layer{position:fixed;inset:0;pointer-events:none;z-index:0;}
.bg-noise{background-image:url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.035'/%3E%3C/svg%3E");opacity:0.5;}
.bg-orb{position:absolute;border-radius:50%;filter:blur(90px);animation:orbFloat ease-in-out infinite alternate;}
.orb1{width:520px;height:520px;background:radial-gradient(circle,rgba(0,245,160,0.055) 0%,transparent 65%);top:-160px;left:-160px;animation-duration:9s;}
.orb2{width:440px;height:440px;background:radial-gradient(circle,rgba(0,212,255,0.045) 0%,transparent 65%);bottom:-120px;right:-120px;animation-duration:11s;animation-delay:2s;}
.orb3{width:280px;height:280px;background:radial-gradient(circle,rgba(0,85,204,0.06) 0%,transparent 65%);top:45%;left:45%;animation-duration:7s;animation-delay:1s;}
@keyframes orbFloat{from{transform:translate(0,0) scale(1);}to{transform:translate(25px,15px) scale(1.07);}}
.bg-grid{background-image:linear-gradient(rgba(0,245,160,0.025) 1px,transparent 1px),linear-gradient(90deg,rgba(0,245,160,0.025) 1px,transparent 1px);background-size:52px 52px;mask-image:radial-gradient(ellipse 65% 65% at 50% 50%,black 0%,transparent 100%);}
.scan{position:fixed;left:0;right:0;height:1.5px;background:linear-gradient(90deg,transparent 0%,rgba(0,245,160,0.25) 30%,rgba(0,212,255,0.35) 50%,rgba(0,245,160,0.25) 70%,transparent 100%);animation:scanMove 4s ease-in-out infinite;pointer-events:none;z-index:1;}
@keyframes scanMove{0%{top:-2px;opacity:0;}8%{opacity:1;}92%{opacity:1;}100%{top:100vh;opacity:0;}}
.corner{position:fixed;width:44px;height:44px;pointer-events:none;z-index:2;opacity:0;animation:cornerIn 0.5s 0.3s ease forwards;}
@keyframes cornerIn{to{opacity:1;}}
.corner::before,.corner::after{content:'';position:absolute;border-radius:1px;}
.corner::before{width:2px;height:100%;background:linear-gradient(to bottom,var(--green),transparent);}
.corner::after{width:100%;height:2px;background:linear-gradient(to right,var(--green),transparent);}
.c-tl{top:18px;left:18px;}.c-tr{top:18px;right:18px;transform:scaleX(-1);}.c-bl{bottom:18px;left:18px;transform:scaleY(-1);}.c-br{bottom:18px;right:18px;transform:scale(-1,-1);}
.stage{position:relative;z-index:10;display:flex;flex-direction:column;align-items:center;gap:0;}
.logo-area{position:relative;width:200px;height:200px;display:flex;align-items:center;justify-content:center;margin-bottom:28px;}
.shield-svg{position:absolute;width:130px;height:140px;opacity:0;animation:shieldAppear 0.6s 1.9s cubic-bezier(0.34,1.56,0.64,1) forwards;filter:drop-shadow(0 0 18px rgba(0,245,160,0.5)) drop-shadow(0 0 40px rgba(0,212,255,0.25));}
@keyframes shieldAppear{from{opacity:0;transform:scale(0.7);}to{opacity:1;transform:scale(1);}}
.shield-path{stroke-dasharray:600;stroke-dashoffset:600;animation:drawShield 0.9s 1.95s ease forwards;}
@keyframes drawShield{to{stroke-dashoffset:0;}}
.shield-fill{opacity:0;animation:fillFlash 0.4s 2.7s ease forwards;}
@keyframes fillFlash{0%{opacity:0;}50%{opacity:0.18;}100%{opacity:0.08;}}
.letter-k{position:absolute;font-family:'Clash Display',sans-serif;font-weight:700;font-size:64px;line-height:1;background:linear-gradient(135deg,var(--green),var(--cyan));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;filter:drop-shadow(0 0 12px rgba(0,245,160,0.6));transform:translateX(-80px) scale(1.3);opacity:0;animation:kSlideIn 0.55s 0.4s cubic-bezier(0.34,1.56,0.64,1) forwards;z-index:3;}
@keyframes kSlideIn{0%{opacity:0;transform:translateX(-80px) scale(1.4);}100%{opacity:1;transform:translateX(-18px) scale(1);}}
.letter-t{position:absolute;font-family:'Clash Display',sans-serif;font-weight:700;font-size:64px;line-height:1;background:linear-gradient(135deg,var(--cyan),#0099ee);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;filter:drop-shadow(0 0 12px rgba(0,212,255,0.6));transform:translateX(80px) scale(1.3);opacity:0;animation:tSlideIn 0.55s 0.4s cubic-bezier(0.34,1.56,0.64,1) forwards;z-index:3;}
@keyframes tSlideIn{0%{opacity:0;transform:translateX(80px) scale(1.4);}100%{opacity:1;transform:translateX(18px) scale(1);}}
.letter-k.merge{animation:kSlideIn 0.55s 0.4s cubic-bezier(0.34,1.56,0.64,1) forwards,kMerge 0.45s 1.35s cubic-bezier(0.4,0,0.2,1) forwards;}
.letter-t.merge{animation:tSlideIn 0.55s 0.4s cubic-bezier(0.34,1.56,0.64,1) forwards,tMerge 0.45s 1.35s cubic-bezier(0.4,0,0.2,1) forwards;}
@keyframes kMerge{0%{transform:translateX(-18px) scale(1);opacity:1;}100%{transform:translateX(-14px) scale(0.78);opacity:0;}}
@keyframes tMerge{0%{transform:translateX(18px) scale(1);opacity:1;}100%{transform:translateX(10px) scale(0.78);opacity:0;}}
.merge-burst{position:absolute;width:120px;height:120px;border-radius:50%;background:radial-gradient(circle,rgba(0,245,160,0.35) 0%,rgba(0,212,255,0.15) 40%,transparent 70%);opacity:0;transform:scale(0);animation:burstPop 0.5s 1.75s cubic-bezier(0.34,1.56,0.64,1) forwards;z-index:2;}
@keyframes burstPop{0%{opacity:0;transform:scale(0);}50%{opacity:1;transform:scale(1.4);}100%{opacity:0;transform:scale(2);}}
.merge-ring{position:absolute;width:90px;height:90px;border-radius:50%;border:1.5px solid var(--green);opacity:0;transform:scale(0);animation:ringExpand 0.6s 1.78s ease forwards;z-index:2;}
@keyframes ringExpand{0%{opacity:0.8;transform:scale(0.3);}100%{opacity:0;transform:scale(2.2);}}
.orbit-ring{position:absolute;inset:14px;border-radius:50%;border:1px solid transparent;background:linear-gradient(var(--bg),var(--bg)) padding-box,linear-gradient(135deg,rgba(0,245,160,0.5),transparent 50%,rgba(0,212,255,0.4)) border-box;opacity:0;animation:orbitReveal 0.5s 2.1s ease forwards,orbitSpin 5s 2.1s linear infinite;}
@keyframes orbitReveal{to{opacity:1;}}
@keyframes orbitSpin{to{transform:rotate(360deg);}}
.pulse-ring{position:absolute;inset:28px;border-radius:50%;border:1px solid rgba(0,245,160,0.15);opacity:0;animation:pulseReveal 0.5s 2.2s ease forwards,pulseAnim 2.5s 2.2s ease-in-out infinite;}
@keyframes pulseReveal{to{opacity:1;}}
@keyframes pulseAnim{0%,100%{border-color:rgba(0,245,160,0.12);transform:scale(1);}50%{border-color:rgba(0,245,160,0.4);transform:scale(1.06);}}
.logo-glow{position:absolute;inset:35px;border-radius:50%;background:radial-gradient(circle,rgba(0,245,160,0.12) 0%,rgba(0,85,204,0.08) 50%,transparent 70%);opacity:0;animation:glowReveal 0.6s 2.0s ease forwards,glowPulse 3s 2.0s ease-in-out infinite;}
@keyframes glowReveal{to{opacity:1;}}
@keyframes glowPulse{0%,100%{transform:scale(0.9);opacity:0.7;}50%{transform:scale(1.15);opacity:1;}}
.orbit-dot{position:absolute;width:5px;height:5px;border-radius:50%;background:var(--green);box-shadow:0 0 6px var(--green),0 0 12px var(--cyan);opacity:0;animation:orbitReveal 0.5s 2.3s ease forwards;}
.od1{top:9px;left:50%;transform:translateX(-50%);}
.od2{bottom:9px;left:50%;transform:translateX(-50%);animation-delay:2.45s!important;}
.od3{left:9px;top:50%;transform:translateY(-50%);animation-delay:2.4s!important;}
.od4{right:9px;top:50%;transform:translateY(-50%);animation-delay:2.5s!important;}
.brand{text-align:center;opacity:0;transform:translateY(14px);animation:fadeUp 0.6s 2.4s ease forwards;margin-bottom:36px;}
.brand-name{font-family:'Clash Display',sans-serif;font-size:1.9rem;font-weight:700;letter-spacing:-0.5px;line-height:1;}
.brand-k{color:var(--white);}
.brand-v{background:linear-gradient(90deg,var(--green),var(--cyan));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;}
.brand-sub{font-size:0.7rem;color:var(--muted2);letter-spacing:3.5px;text-transform:uppercase;margin-top:7px;}
.progress-area{width:210px;opacity:0;transform:translateY(10px);animation:fadeUp 0.5s 2.65s ease forwards;}
.progress-top{display:flex;justify-content:space-between;font-size:0.68rem;color:var(--muted2);margin-bottom:7px;}
.p-status{color:var(--green);transition:opacity 0.3s;}
.progress-track{width:100%;height:2.5px;background:rgba(255,255,255,0.05);border-radius:99px;overflow:hidden;position:relative;}
.progress-fill{height:100%;width:0%;background:linear-gradient(90deg,var(--green),var(--cyan));border-radius:99px;animation:pfill 2.6s cubic-bezier(0.4,0,0.2,1) 2.7s forwards;position:relative;}
.progress-fill::after{content:'';position:absolute;right:0;top:50%;transform:translateY(-50%);width:5px;height:5px;border-radius:50%;background:var(--cyan);box-shadow:0 0 6px var(--cyan),0 0 12px var(--green);}
@keyframes pfill{0%{width:0%;}15%{width:12%;}35%{width:35%;}55%{width:58%;}78%{width:80%;}100%{width:100%;}}
.progress-track::after{content:'';position:absolute;inset:0;background:linear-gradient(90deg,transparent,rgba(0,245,160,0.07),transparent);animation:shimmer 2s ease infinite;}
@keyframes shimmer{from{transform:translateX(-100%);}to{transform:translateX(200%);}}
.dots{display:flex;gap:5px;justify-content:center;margin-top:16px;opacity:0;animation:fadeUp 0.5s 2.9s ease forwards;}
.dots span{width:4px;height:4px;border-radius:50%;background:var(--green);animation:dotB 1.3s ease-in-out infinite;}
.dots span:nth-child(2){animation-delay:0.22s;background:linear-gradient(var(--green),var(--cyan));}
.dots span:nth-child(3){animation-delay:0.44s;background:var(--cyan);}
@keyframes dotB{0%,80%,100%{transform:scale(0.5);opacity:0.3;}40%{transform:scale(1.4);opacity:1;box-shadow:0 0 6px var(--green);}}
.status-line{font-size:0.68rem;color:var(--muted2);letter-spacing:1px;text-transform:uppercase;text-align:center;margin-top:10px;height:14px;opacity:0;animation:fadeUp 0.5s 3s ease forwards;transition:opacity 0.3s;}
.particles{position:fixed;inset:0;pointer-events:none;z-index:1;}
.p{position:absolute;border-radius:50%;animation:pfloat linear infinite;opacity:0;}
@keyframes pfloat{0%{opacity:0;transform:translateY(0) scale(0);}10%{opacity:0.8;transform:translateY(-15px) scale(1);}90%{opacity:0.4;}100%{opacity:0;transform:translateY(-110px) scale(0.2);}}
/* Smooth fade-out before redirect */
body.fade-out{animation:bodyFadeOut 0.6s ease forwards!important;}
.stage.fade-out{animation:stageFadeOut 0.6s ease forwards!important;}
@keyframes bodyFadeOut{to{opacity:0;}}
@keyframes stageFadeOut{to{opacity:0;transform:scale(1.04);}}
@keyframes fadeUp{from{opacity:0;transform:translateY(14px);}to{opacity:1;transform:translateY(0);}}
.version{position:fixed;bottom:20px;left:50%;transform:translateX(-50%);font-size:0.6rem;color:rgba(58,66,85,0.7);letter-spacing:2.5px;text-transform:uppercase;opacity:0;animation:fadeUp 0.6s 3.1s ease forwards;z-index:10;white-space:nowrap;}
</style>
</head>
<body>
<div class="bg-layer bg-noise"></div>
<div class="bg-layer">
  <div class="bg-orb orb1"></div>
  <div class="bg-orb orb2"></div>
  <div class="bg-orb orb3"></div>
</div>
<div class="bg-layer bg-grid"></div>
<div class="scan"></div>
<div class="corner c-tl"></div>
<div class="corner c-tr"></div>
<div class="corner c-bl"></div>
<div class="corner c-br"></div>
<div class="particles" id="pts"></div>

<div class="stage" id="stage">
  <div class="logo-area">
    <div class="orbit-ring"></div>
    <div class="pulse-ring"></div>
    <div class="logo-glow"></div>
    <div class="merge-burst"></div>
    <div class="merge-ring"></div>
    <div class="letter-k merge">K</div>
    <div class="letter-t merge">T</div>
    <svg class="shield-svg" viewBox="0 0 130 145" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path class="shield-fill" d="M65 8 L118 30 L118 72 C118 100 90 128 65 138 C40 128 12 100 12 72 L12 30 Z" fill="url(#shieldGrad)"/>
      <path class="shield-path" d="M65 8 L118 30 L118 72 C118 100 90 128 65 138 C40 128 12 100 12 72 L12 30 Z" stroke="url(#strokeGrad)" stroke-width="3" stroke-linejoin="round" fill="none"/>
      <path class="shield-path" d="M65 22 L106 40 L106 72 C106 94 84 116 65 124 C46 116 24 94 24 72 L24 40 Z" stroke="url(#strokeGrad2)" stroke-width="1.5" stroke-linejoin="round" fill="none" style="animation-delay:2.1s"/>
      <text x="30" y="100" font-family="'Clash Display', sans-serif" font-weight="700" font-size="52" fill="url(#textGrad)" style="animation:none;opacity:1;">K</text>
      <text x="68" y="100" font-family="'Clash Display', sans-serif" font-weight="700" font-size="52" fill="url(#textGrad2)" style="animation:none;opacity:1;">T</text>
      <defs>
        <linearGradient id="shieldGrad" x1="12" y1="8" x2="118" y2="138" gradientUnits="userSpaceOnUse"><stop offset="0%" stop-color="#00f5a0" stop-opacity="0.15"/><stop offset="100%" stop-color="#0055cc" stop-opacity="0.1"/></linearGradient>
        <linearGradient id="strokeGrad" x1="12" y1="8" x2="118" y2="138" gradientUnits="userSpaceOnUse"><stop offset="0%" stop-color="#00f5a0"/><stop offset="50%" stop-color="#00d4ff"/><stop offset="100%" stop-color="#0077ee"/></linearGradient>
        <linearGradient id="strokeGrad2" x1="24" y1="22" x2="106" y2="124" gradientUnits="userSpaceOnUse"><stop offset="0%" stop-color="#00f5a0" stop-opacity="0.5"/><stop offset="100%" stop-color="#00d4ff" stop-opacity="0.2"/></linearGradient>
        <linearGradient id="textGrad" x1="0" y1="50" x2="0" y2="100" gradientUnits="userSpaceOnUse"><stop offset="0%" stop-color="#00f5a0"/><stop offset="100%" stop-color="#00d4ff"/></linearGradient>
        <linearGradient id="textGrad2" x1="0" y1="50" x2="0" y2="100" gradientUnits="userSpaceOnUse"><stop offset="0%" stop-color="#00d4ff"/><stop offset="100%" stop-color="#0077ee"/></linearGradient>
      </defs>
    </svg>
    <div class="orbit-dot od1"></div>
    <div class="orbit-dot od2"></div>
    <div class="orbit-dot od3"></div>
    <div class="orbit-dot od4"></div>
  </div>

  <div class="brand">
    <div class="brand-name"><span class="brand-k">Keeption </span><span class="brand-v">Vault</span></div>
    <div class="brand-sub">Private · Encrypted · Yours</div>
  </div>

  <div class="progress-area">
    <div class="progress-top">
      <span class="p-status" id="pStatus">Initializing vault...</span>
      <span id="pPct">0%</span>
    </div>
    <div class="progress-track">
      <div class="progress-fill" id="pFill"></div>
    </div>
  </div>
  <div class="dots"><span></span><span></span><span></span></div>
  <div class="status-line" id="sLine">Establishing secure connection</div>
</div>

<div class="version">Keeption Vault &nbsp;·&nbsp; v1.0.0</div>

<script>
// Particles
const pts = document.getElementById('pts');
const gc = ['rgba(0,245,160,0.55)','rgba(0,212,255,0.45)','rgba(0,85,204,0.4)'];
for (let i = 0; i < 32; i++) {
  const d = document.createElement('div');
  d.className = 'p';
  const s = Math.random() * 2.5 + 1;
  d.style.cssText = `width:${s}px;height:${s}px;left:${Math.random()*100}%;top:${35+Math.random()*55}%;background:${gc[Math.floor(Math.random()*gc.length)]};box-shadow:0 0 ${s*3}px ${gc[0]};animation-duration:${5+Math.random()*7}s;animation-delay:${Math.random()*5}s;`;
  pts.appendChild(d);
}

// Progress messages
const msgs = [
  { pct:0,  st:'Initializing vault...',    sl:'Establishing secure connection' },
  { pct:12, st:'Loading encryption keys...', sl:'Applying zero-knowledge protocol' },
  { pct:35, st:'Verifying identity...',    sl:'Authenticating session' },
  { pct:58, st:'Mounting file system...',  sl:'Decrypting vault index' },
  { pct:80, st:'Almost ready...',          sl:'Syncing your files' },
  { pct:100,st:'Vault unlocked',           sl:'Welcome back' },
];
const pStatus = document.getElementById('pStatus');
const pPct    = document.getElementById('pPct');
const sLine   = document.getElementById('sLine');
const START = performance.now() + 2700;
const DUR   = 2600;
let lastMsg = null;

function tick() {
  const now = performance.now();
  const t = Math.max(0, Math.min((now - START) / DUR, 1));
  let pct;
  if      (t < 0.15) pct = t/0.15*12;
  else if (t < 0.35) pct = 12+(t-0.15)/0.20*23;
  else if (t < 0.55) pct = 35+(t-0.35)/0.20*23;
  else if (t < 0.78) pct = 58+(t-0.55)/0.23*22;
  else               pct = 80+(t-0.78)/0.22*20;
  pct = Math.round(pct);
  pPct.textContent = pct + '%';
  let active = msgs[0];
  for (const m of msgs) { if (pct >= m.pct) active = m; }
  if (active !== lastMsg) {
    lastMsg = active;
    pStatus.style.opacity = '0';
    sLine.style.opacity   = '0';
    setTimeout(() => {
      pStatus.textContent = active.st;
      sLine.textContent   = active.sl;
      pStatus.style.opacity = '1';
      sLine.style.opacity   = '1';
    }, 280);
  }
  if (t < 1) requestAnimationFrame(tick);
}
setTimeout(() => requestAnimationFrame(tick), 2700);

// Smooth fade-out then redirect
setTimeout(() => {
  document.body.classList.add('fade-out');
  document.getElementById('stage').classList.add('fade-out');
  setTimeout(() => {
    window.location.href = '/';
  }, 600);
}, 5600);
</script>
</body>
</html>
