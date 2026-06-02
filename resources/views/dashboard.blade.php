<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<meta name="theme-color" content="#050810"/>
<meta name="csrf-token" content="{{ csrf_token() }}"/>
<title>Dashboard — Keeption Vault</title>
<link rel="icon" type="image/png" href="/favicon.png"/>
<link href="https://fonts.googleapis.com/css2?family=Clash+Display:wght@400;500;600;700&family=Epilogue:wght@300;400;500&display=swap" rel="stylesheet"/>
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
<style>
.lucide{width:16px;height:16px;stroke:currentColor;stroke-width:1.8;fill:none;stroke-linecap:round;stroke-linejoin:round;vertical-align:middle;flex-shrink:0;}
.lucide-lg{width:18px;height:18px;}
.lucide-xl{width:22px;height:22px;}
.lucide-2xl{width:28px;height:28px;}
/* Nav icons get a tinted background when active */
.nav-item .nav-ico .lucide{transition:color .18s,filter .18s;}
.nav-item:hover .nav-ico .lucide{color:var(--text);filter:drop-shadow(0 0 4px rgba(255,255,255,.15));}
.nav-item.active .nav-ico .lucide{color:var(--accent);filter:drop-shadow(0 0 6px rgba(0,245,160,.5));}
/* Topbar buttons */
.t-btn .lucide{color:var(--muted);transition:color .2s,filter .2s;}
.t-btn:hover .lucide{color:var(--accent);filter:drop-shadow(0 0 5px rgba(0,245,160,.6));}
/* Details panel actions */
.act-btn .lucide{width:14px;height:14px;}
.act-btn:hover .lucide{color:var(--accent);}
.act-btn.danger:hover .lucide{color:var(--red);}
/* Bulk bar */
.bulk-btn .lucide{width:13px;height:13px;}
/* Mobile nav */
.mob-nav-btn .lucide{transition:color .2s,filter .2s;}
.mob-nav-btn.active .lucide{color:var(--accent);filter:drop-shadow(0 0 5px rgba(0,245,160,.5));}
/* Mobile more sheet */
.mob-more-grid button .lucide{color:var(--muted);transition:color .2s;}
.mob-more-grid button:hover .lucide,.mob-more-grid button:active .lucide{color:var(--accent);}
/* Avatar dropdown */
.av-drop .lucide{width:14px;height:14px;color:var(--muted);}
.av-drop a:hover .lucide,.av-drop button:hover .lucide{color:var(--accent);}
</style>
<style>
:root{
  --bg:#050810;--surface:#0a0d18;--surface2:#0f1422;--surface3:#161c2e;
  --border:rgba(255,255,255,0.08);--border-glow:rgba(0,245,160,0.2);
  --accent:#00f5a0;--accent2:#00d4ff;--accent3:#ff6b9d;--gold:#ffd166;--red:#ff4444;
  --text:#eef0f6;--muted:#6b7280;
  --glass:rgba(255,255,255,0.04);--glass-border:rgba(255,255,255,0.09);
  --sidebar-w:240px;--details-w:300px;--topbar-h:60px;
}
html.light-mode{
  --bg:#f0f2f7;--surface:#ffffff;--surface2:#f5f6fa;--surface3:#e8eaf0;
  --border:rgba(0,0,0,0.09);--text:#0d0f14;--muted:#8a92a6;
  --glass:rgba(255,255,255,0.7);--glass-border:rgba(0,0,0,0.08);
}
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box;}
html,body{height:100%;overflow:hidden;}
body{font-family:'Epilogue',sans-serif;background:var(--bg);color:var(--text);}
::-webkit-scrollbar{width:4px;height:4px;}
::-webkit-scrollbar-track{background:transparent;}
::-webkit-scrollbar-thumb{background:var(--surface3);border-radius:99px;}
/* ── ANIMATED MESH BACKGROUND ─────────────────────────────────────────── */
body::before{
  content:'';position:fixed;inset:0;z-index:0;pointer-events:none;
  background:
    radial-gradient(ellipse 60% 50% at 20% 30%,rgba(0,212,255,0.07) 0%,transparent 70%),
    radial-gradient(ellipse 50% 60% at 80% 70%,rgba(0,245,160,0.06) 0%,transparent 70%),
    radial-gradient(ellipse 40% 40% at 60% 20%,rgba(167,139,250,0.04) 0%,transparent 60%);
  animation:meshDrift 18s ease-in-out infinite alternate;
}
@keyframes meshDrift{
  0%{transform:scale(1) translate(0,0);}
  50%{transform:scale(1.04) translate(-1%,1%);}
  100%{transform:scale(1.06) translate(1%,-1%);}
}
</style>
</head>
@php
    $plan       = $plan ?? 'free';
    $planConfig = $planConfig ?? ['storage_gb'=>5,'file_limit_mb'=>500,'links'=>5,'version_days'=>7,'label'=>'Free'];
    $isFree     = $plan === 'free';
    $isPro      = $plan === 'pro';
    $isTeams    = $plan === 'teams';
    $storageGB  = $planConfig['storage_gb'];
    $fileLimitMB= $planConfig['file_limit_mb'];
    $maxLinks   = $planConfig['links'];
    $versionDays= $planConfig['version_days'];
    $planLabel  = $planConfig['label'];
@endphp
<body>
<style>
/* TOPBAR */
.topbar{position:fixed;top:0;left:0;right:0;height:var(--topbar-h);z-index:300;display:flex;align-items:center;gap:16px;padding:0 20px;background:rgba(5,8,16,0.75);backdrop-filter:blur(32px);-webkit-backdrop-filter:blur(32px);border-bottom:1px solid rgba(0,245,160,0.12);box-shadow:0 1px 0 rgba(0,245,160,0.06),0 4px 24px rgba(0,0,0,0.4);}
.t-logo{display:flex;align-items:center;gap:9px;text-decoration:none;color:var(--text);font-family:'Clash Display',sans-serif;font-weight:700;font-size:1.1rem;white-space:nowrap;width:var(--sidebar-w);flex-shrink:0;}
.t-logo img{width:28px;height:28px;border-radius:7px;}
.t-logo em{color:var(--accent);font-style:normal;}
.t-search{flex:1;position:relative;max-width:520px;}
.t-search input{width:100%;background:var(--surface2);border:1px solid var(--border);border-radius:10px;padding:9px 16px 9px 38px;color:var(--text);font-family:'Epilogue',sans-serif;font-size:0.875rem;outline:none;transition:border-color .2s;}
.t-search input:focus{border-color:rgba(0,245,160,.4);}
.t-search input::placeholder{color:var(--muted);}
.t-search-ico{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted);font-size:13px;pointer-events:none;}
.t-right{display:flex;align-items:center;gap:8px;margin-left:auto;}
.t-btn{background:none;border:1px solid var(--border);color:var(--muted);border-radius:8px;width:34px;height:34px;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:15px;transition:all .2s;position:relative;flex-shrink:0;}
.t-btn:hover{border-color:var(--accent);color:var(--accent);}
.t-badge{position:absolute;top:-4px;right:-4px;width:16px;height:16px;border-radius:50%;background:var(--accent3);color:#fff;font-size:9px;font-weight:700;display:flex;align-items:center;justify-content:center;border:2px solid var(--bg);}
.storage-pill{display:flex;align-items:center;gap:8px;background:var(--surface2);border:1px solid var(--border);border-radius:8px;padding:5px 12px;font-size:.75rem;color:var(--muted);cursor:pointer;white-space:nowrap;}
.sp-bar{width:44px;height:4px;background:var(--surface3);border-radius:99px;overflow:hidden;}
.sp-fill{height:100%;background:linear-gradient(90deg,var(--accent),var(--accent2));border-radius:99px;}
.av-wrap{position:relative;}
.av-btn{width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,var(--accent2),var(--accent3));display:flex;align-items:center;justify-content:center;font-family:'Clash Display',sans-serif;font-weight:700;font-size:12px;cursor:pointer;border:2px solid transparent;transition:border-color .2s;flex-shrink:0;}
.av-btn:hover{border-color:var(--accent);}
.av-drop{display:none;position:absolute;top:calc(100% + 8px);right:0;width:200px;background:var(--surface);border:1px solid var(--border);border-radius:12px;overflow:hidden;z-index:400;box-shadow:0 16px 48px rgba(0,0,0,.6);}
.av-wrap:hover .av-drop{display:block;}
.av-email{padding:12px 14px;font-size:.75rem;color:var(--muted);border-bottom:1px solid var(--border);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.av-drop a,.av-drop button{display:flex;align-items:center;gap:9px;width:100%;padding:10px 14px;font-size:.83rem;color:var(--muted);text-decoration:none;background:none;border:none;cursor:pointer;font-family:'Epilogue',sans-serif;transition:background .15s,color .15s;}
.av-drop a:hover,.av-drop button:hover{background:var(--surface2);color:var(--text);}
.av-drop hr{border:none;border-top:1px solid var(--border);}
.notif-wrap{position:relative;}
.notif-drop{display:none;position:absolute;top:calc(100% + 8px);right:0;width:280px;background:var(--surface);border:1px solid var(--border);border-radius:14px;overflow:hidden;z-index:400;box-shadow:0 16px 48px rgba(0,0,0,.6);}
.notif-wrap:hover .notif-drop{display:block;}
.notif-hdr{padding:13px 14px;border-bottom:1px solid var(--border);font-family:'Clash Display',sans-serif;font-size:.9rem;font-weight:600;}
.notif-empty{padding:24px 14px;text-align:center;color:var(--muted);font-size:.82rem;}
/* LAYOUT */
.app-body{display:flex;height:100vh;padding-top:var(--topbar-h);background:transparent;}
/* SIDEBAR */
.sidebar{width:var(--sidebar-w);flex-shrink:0;background:rgba(10,13,24,0.85);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border-right:1px solid var(--border);display:flex;flex-direction:column;overflow-y:auto;overflow-x:hidden;transition:width .25s ease;position:relative;z-index:100;}
.sidebar.collapsed{width:56px;}
.sb-toggle{position:absolute;top:14px;right:-12px;width:24px;height:24px;border-radius:50%;background:var(--surface2);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:10px;color:var(--muted);z-index:10;transition:all .2s;}
.sb-toggle:hover{border-color:var(--accent);color:var(--accent);}
.sidebar.collapsed .sb-toggle{transform:rotate(180deg);}
.sb-sec{padding:14px 10px 4px;}
.sb-lbl{font-size:.65rem;font-weight:600;text-transform:uppercase;letter-spacing:1.5px;color:var(--muted);padding:0 8px;margin-bottom:4px;white-space:nowrap;overflow:hidden;transition:opacity .2s;}
.sidebar.collapsed .sb-lbl{opacity:0;}
.nav-item{display:flex;align-items:center;gap:10px;padding:9px 10px;border-radius:9px;cursor:pointer;font-size:.84rem;color:var(--muted);transition:all .18s;position:relative;white-space:nowrap;overflow:hidden;text-decoration:none;border:none;background:none;width:100%;font-family:'Epilogue',sans-serif;}
.nav-item:hover{background:var(--surface2);color:var(--text);}
.nav-item.active{background:rgba(0,245,160,.08);color:var(--accent);}
.nav-item.active::before{content:'';position:absolute;left:0;top:20%;bottom:20%;width:3px;border-radius:2px;background:var(--accent);}
.nav-ico{font-size:16px;width:20px;text-align:center;flex-shrink:0;}
.nav-txt{flex:1;overflow:hidden;text-overflow:ellipsis;}
.sidebar.collapsed .nav-txt,.sidebar.collapsed .nav-bdg{display:none;}
.nav-bdg{margin-left:auto;background:var(--accent2);color:#fff;font-size:.62rem;font-weight:700;border-radius:20px;padding:1px 7px;flex-shrink:0;}
.sb-storage{margin-top:auto;padding:14px 12px;border-top:1px solid var(--border);}
.sidebar.collapsed .sb-storage-inner{display:none;}
.sb-stor-row{display:flex;justify-content:space-between;font-size:.73rem;color:var(--muted);margin-bottom:7px;}
.sb-stor-row span{color:var(--text);font-weight:500;}
.sb-stor-bar{height:5px;background:var(--surface2);border-radius:99px;overflow:hidden;margin-bottom:10px;}
.sb-stor-fill{height:100%;background:linear-gradient(90deg,var(--accent),var(--accent2));border-radius:99px;transition:width .5s;}
.upg-btn{width:100%;padding:8px;background:linear-gradient(135deg,var(--accent),var(--accent2));border:none;border-radius:8px;color:#04060a;font-family:'Clash Display',sans-serif;font-weight:700;font-size:.78rem;cursor:pointer;transition:opacity .2s;white-space:nowrap;}
.upg-btn:hover{opacity:.85;}
/* MAIN */
.main{flex:1;overflow-y:auto;background:transparent;min-width:0;position:relative;}
.page{display:none;padding:28px 32px;animation:fadeUp .3s ease both;position:relative;z-index:1;}
.page.active{display:block;}
@keyframes fadeUp{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:translateY(0)}}
.main-watermark{
  position:fixed;
  top:calc(var(--topbar-h) + (100vh - var(--topbar-h)) / 2);
  left:calc(var(--sidebar-w) + (100vw - var(--sidebar-w)) / 2);
  transform:translate(-50%,-50%);
  width:80vmin;height:80vmin;
  background:url('/favicon.png') center/contain no-repeat;
  opacity:0.06;
  pointer-events:none;
  z-index:0;
  filter:grayscale(1) brightness(3);
  animation:watermarkPulse 6s ease-in-out infinite alternate;
}
@media(max-width:768px){
  .main-watermark{
    left:50%;
    top:50%;
    width:70vmin;height:70vmin;
  }
}
@keyframes watermarkPulse{
  0%{opacity:0.04;transform:translate(-50%,-50%) scale(1);}
  100%{opacity:0.07;transform:translate(-50%,-50%) scale(1.06);}
}
/* DETAILS PANEL */
.details{width:var(--details-w);flex-shrink:0;background:var(--surface);border-left:1px solid var(--border);display:flex;flex-direction:column;overflow-y:auto;transform:translateX(100%);transition:transform .25s ease;position:fixed;right:0;top:var(--topbar-h);bottom:0;z-index:200;}
.details.open{transform:translateX(0);}
.det-hdr{display:flex;align-items:center;justify-content:space-between;padding:16px 16px 12px;border-bottom:1px solid var(--border);}
.det-title{font-family:'Clash Display',sans-serif;font-size:.9rem;font-weight:600;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:220px;}
.det-close{background:none;border:none;color:var(--muted);cursor:pointer;font-size:17px;transition:color .2s;}
.det-close:hover{color:var(--text);}
.det-preview{padding:14px;border-bottom:1px solid var(--border);}
.det-prev-box{width:100%;aspect-ratio:1;min-height:180px;border-radius:12px;background:var(--surface2);display:flex;align-items:center;justify-content:center;font-size:52px;overflow:hidden;}
.det-prev-box img{width:100%;height:100%;object-fit:cover;border-radius:12px;}
.det-prev-box video{width:100%;height:100%;object-fit:cover;border-radius:12px;}
.det-meta{padding:14px;border-bottom:1px solid var(--border);}
.meta-row{display:flex;justify-content:space-between;padding:6px 0;font-size:.78rem;border-bottom:1px solid rgba(255,255,255,.03);}
.meta-row:last-child{border-bottom:none;}
.meta-k{color:var(--muted);}
.meta-v{color:var(--text);font-weight:500;text-align:right;max-width:55%;overflow:hidden;text-overflow:ellipsis;}
.det-actions{padding:14px;display:grid;grid-template-columns:1fr 1fr;gap:8px;}
.act-btn{padding:9px 6px;border-radius:9px;border:1px solid var(--border);background:none;color:var(--text);font-size:.76rem;cursor:pointer;transition:all .2s;display:flex;align-items:center;justify-content:center;gap:5px;font-family:'Epilogue',sans-serif;}
.act-btn:hover{border-color:var(--accent);color:var(--accent);}
.act-btn.danger:hover{border-color:var(--red);color:var(--red);}
.act-btn.full{grid-column:1/-1;background:var(--accent);border-color:var(--accent);color:#04060a;font-family:'Clash Display',sans-serif;font-weight:700;}
.act-btn.full:hover{opacity:.85;color:#04060a;}
</style>
<style>
/* OVERLAYS */
.drop-overlay{display:none;position:fixed;inset:0;z-index:500;background:rgba(4,6,10,.88);backdrop-filter:blur(8px);align-items:center;justify-content:center;flex-direction:column;}
.drop-overlay.active{display:flex;}
.drop-box{border:2px dashed var(--accent);border-radius:24px;padding:60px 80px;text-align:center;animation:pulseBorder 1.5s ease infinite;}
@keyframes pulseBorder{0%,100%{border-color:rgba(0,245,160,.4);}50%{border-color:var(--accent);}}
.drop-box-ico{font-size:56px;margin-bottom:14px;}
.drop-box-title{font-family:'Clash Display',sans-serif;font-size:1.8rem;font-weight:700;color:var(--accent);margin-bottom:8px;}
.drop-box-sub{color:var(--muted);font-size:.95rem;}
.upload-bar{position:fixed;bottom:20px;left:50%;transform:translateX(-50%);background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:14px 20px;min-width:320px;z-index:400;display:none;box-shadow:0 16px 48px rgba(0,0,0,.5);}
.upload-bar.active{display:block;}
.ub-title{font-size:.8rem;font-weight:600;margin-bottom:8px;display:flex;justify-content:space-between;}
.ub-track{height:5px;background:var(--surface2);border-radius:99px;overflow:hidden;}
.ub-fill{height:100%;background:linear-gradient(90deg,var(--accent),var(--accent2));border-radius:99px;transition:width .3s;}
.bulk-bar{position:fixed;bottom:20px;left:50%;transform:translateX(-50%);background:var(--surface2);border:1px solid var(--border);border-radius:14px;padding:11px 18px;display:none;align-items:center;gap:10px;z-index:400;box-shadow:0 16px 48px rgba(0,0,0,.5);white-space:nowrap;}
.bulk-bar.active{display:flex;}
.bulk-count{font-size:.83rem;font-weight:600;color:var(--accent);margin-right:6px;}
.bulk-btn{padding:6px 13px;border-radius:8px;border:1px solid var(--border);background:none;color:var(--text);font-size:.78rem;cursor:pointer;transition:all .2s;font-family:'Epilogue',sans-serif;}
.bulk-btn:hover{border-color:var(--accent);color:var(--accent);}
.bulk-btn.d:hover{border-color:var(--red);color:var(--red);}
.shortcut-sheet{display:none;position:fixed;inset:0;z-index:700;background:rgba(4,6,10,.88);backdrop-filter:blur(8px);align-items:center;justify-content:center;}
.shortcut-sheet.active{display:flex;}
.sc-box{background:var(--surface);border:1px solid var(--border);border-radius:20px;padding:30px;max-width:440px;width:90%;}
.sc-box h2{font-family:'Clash Display',sans-serif;font-size:1.1rem;margin-bottom:18px;}
.sc-row{display:flex;justify-content:space-between;align-items:center;padding:7px 0;border-bottom:1px solid var(--border);font-size:.83rem;}
.sc-row:last-child{border-bottom:none;}
.sc-key{background:var(--surface2);border:1px solid var(--border);border-radius:6px;padding:2px 9px;font-family:monospace;font-size:.78rem;color:var(--accent);}
/* EMPTY STATES */
.empty-state{display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;padding:60px 20px;min-height:340px;width:100%;}
.es-illo{font-size:64px;margin-bottom:20px;opacity:.7;}
.es-title{font-family:'Clash Display',sans-serif;font-size:1.2rem;font-weight:700;margin-bottom:8px;}
.es-sub{color:var(--muted);font-size:.87rem;max-width:380px;line-height:1.6;margin-bottom:24px;}
.es-actions{display:flex;gap:10px;flex-wrap:wrap;justify-content:center;align-items:center;}
.es-btn{padding:10px 22px;border-radius:10px;border:1px solid var(--border);background:none;color:var(--text);font-size:.83rem;cursor:pointer;transition:all .2s;font-family:'Epilogue',sans-serif;white-space:nowrap;width:auto;display:inline-flex;align-items:center;gap:6px;}
.es-btn:hover{border-color:var(--accent);color:var(--accent);}
.es-btn.primary{background:var(--accent);border-color:var(--accent);color:#04060a;font-family:'Clash Display',sans-serif;font-weight:700;}
.es-btn.primary:hover{opacity:.85;color:#04060a;}
/* ONBOARDING */
.onboard-bar{background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:18px 20px;margin-bottom:24px;}
.ob-top{display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;}
.ob-title{font-family:'Clash Display',sans-serif;font-size:.95rem;font-weight:600;}
.ob-pct{font-size:.78rem;color:var(--accent);}
.ob-track{height:5px;background:var(--surface2);border-radius:99px;overflow:hidden;margin-bottom:16px;}
.ob-fill{height:100%;background:linear-gradient(90deg,var(--accent),var(--accent2));border-radius:99px;transition:width .5s;}
.ob-steps{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:10px;}
.ob-step{background:var(--surface2);border:1px solid var(--border);border-radius:10px;padding:14px;display:flex;flex-direction:column;gap:6px;transition:border-color .2s;}
.ob-step.done{border-color:rgba(0,245,160,.3);opacity:.6;}
.ob-step-ico{font-size:20px;}
.ob-step-title{font-size:.82rem;font-weight:600;}
.ob-step-sub{font-size:.73rem;color:var(--muted);line-height:1.4;}
.ob-step-btn{margin-top:6px;padding:6px 12px;border-radius:7px;border:1px solid var(--border);background:none;color:var(--accent);font-size:.73rem;cursor:pointer;font-family:'Epilogue',sans-serif;transition:all .2s;align-self:flex-start;}
.ob-step-btn:hover{background:rgba(0,245,160,.08);}
.ob-step.done .ob-step-btn{color:var(--muted);pointer-events:none;}
/* PAGE HEADER */
.page-hdr{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:22px;flex-wrap:wrap;gap:12px;}
.page-title{font-family:'Clash Display',sans-serif;font-size:1.4rem;font-weight:700;}
.page-sub{color:var(--muted);font-size:.82rem;margin-top:4px;}
.page-actions{display:flex;align-items:center;gap:8px;flex-wrap:wrap;}
/* FILE GRID */
.file-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:12px;}
.file-grid.list-view{grid-template-columns:1fr;}
.file-card{background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:14px;cursor:pointer;transition:all .2s;position:relative;user-select:none;min-width:0;overflow:hidden;}
.file-card:hover{border-color:rgba(0,245,160,.3);transform:translateY(-2px);}
.file-card.selected{border-color:var(--accent);background:rgba(0,245,160,.05);}
.fc-ico{font-size:32px;margin-bottom:10px;display:block;}
.fc-name{font-size:.8rem;font-weight:500;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;width:100%;max-width:100%;display:block;}
.fc-meta{font-size:.7rem;color:var(--muted);margin-top:4px;}
.fc-check{position:absolute;top:8px;right:8px;width:18px;height:18px;border-radius:50%;border:2px solid var(--border);background:var(--surface2);display:flex;align-items:center;justify-content:center;font-size:10px;transition:all .2s;}
.file-card.selected .fc-check{background:var(--accent);border-color:var(--accent);color:#04060a;}
.file-grid.list-view .file-card{display:flex;align-items:center;gap:12px;padding:10px 14px;border-radius:9px;}
.file-grid.list-view .fc-ico{font-size:22px;margin-bottom:0;flex-shrink:0;display:flex!important;}
.file-grid.list-view .fc-name{flex:1;}
.file-grid.list-view .fc-meta{margin-top:0;margin-left:auto;white-space:nowrap;}
.file-grid.list-view .fc-thumb-wrap{display:none;}
.file-grid.list-view .fc-ico-wrap{display:flex;align-items:center;justify-content:center;width:36px;height:36px;flex-shrink:0;}
/* PHOTO GRID */
.photo-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:8px;}
.photo-card{aspect-ratio:1;border-radius:10px;background:var(--surface2);overflow:hidden;cursor:pointer;position:relative;transition:transform .2s;}
.photo-card:hover{transform:scale(1.03);}
.photo-card-inner{width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:36px;}
.photo-tile{aspect-ratio:1/1;border-radius:10px;overflow:hidden;cursor:pointer;position:relative;transition:transform .2s;background:var(--surface2);}
.photo-tile:hover{transform:scale(1.03);}
.photo-tile img{width:100%;height:100%;object-fit:cover;display:block;}
.photo-tile-ico{width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:36px;}
.photo-tile-name{position:absolute;bottom:0;left:0;right:0;padding:6px 8px;background:linear-gradient(transparent,rgba(0,0,0,.7));font-size:.68rem;color:#fff;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;opacity:0;transition:opacity .2s;}
.photo-tile:hover .photo-tile-name{opacity:1;}
/* CONTEXT MENU */
.ctx-menu{position:fixed;z-index:900;background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:6px;min-width:170px;box-shadow:0 16px 48px rgba(0,0,0,.6);animation:toastIn .15s ease both;}
.ctx-item{display:flex;align-items:center;gap:9px;padding:8px 12px;border-radius:8px;font-size:.82rem;cursor:pointer;color:var(--muted);transition:background .15s,color .15s;}
.ctx-item:hover{background:var(--surface2);color:var(--text);}
.ctx-item.danger:hover{color:var(--red);}
.ctx-sep{height:1px;background:var(--border);margin:4px 0;}
/* MOVE MODAL */
.folder-pick-list{max-height:220px;overflow-y:auto;margin-bottom:14px;}
.folder-pick-item{display:flex;align-items:center;gap:9px;padding:9px 12px;border-radius:9px;cursor:pointer;font-size:.84rem;transition:background .15s;}
.folder-pick-item:hover{background:var(--surface2);}
.folder-pick-item.selected{background:rgba(0,245,160,.08);color:var(--accent);}
/* SELF-DESTRUCT FORM */
.destruct-form{background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:20px;margin-bottom:16px;}
.destruct-form label{display:block;font-size:.78rem;color:var(--muted);margin-bottom:5px;margin-top:12px;}
.destruct-form label:first-child{margin-top:0;}
/* MUSIC PLAYER */
.music-player{background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:16px;margin-bottom:20px;}
.mp-now{display:flex;align-items:center;gap:14px;flex-wrap:wrap;}
.mp-art{width:48px;height:48px;border-radius:10px;background:var(--surface2);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0;}
.mp-info{flex:1;min-width:100px;}
.mp-title{font-size:.88rem;font-weight:600;}
.mp-controls{display:flex;gap:8px;}
.mp-btn{background:none;border:1px solid var(--border);border-radius:8px;width:34px;height:34px;color:var(--text);cursor:pointer;font-size:14px;transition:all .2s;}
.mp-btn:hover,.mp-play{border-color:var(--accent);color:var(--accent);}
.mp-prog{width:100%;height:4px;background:var(--surface2);border-radius:99px;overflow:hidden;margin-top:10px;}
.mp-prog-fill{height:100%;background:linear-gradient(90deg,var(--accent),var(--accent2));border-radius:99px;transition:width .5s;}
/* LINKS */
.link-row{background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:14px 16px;margin-bottom:10px;display:flex;align-items:center;gap:12px;flex-wrap:wrap;}
.link-ico{font-size:20px;flex-shrink:0;}
.link-info{flex:1;min-width:120px;}
.link-name{font-size:.85rem;font-weight:500;}
.link-meta{font-size:.73rem;color:var(--muted);margin-top:3px;}
.link-badge{padding:3px 9px;border-radius:20px;font-size:.68rem;font-weight:700;}
.badge-active{background:rgba(0,245,160,.15);color:var(--accent);}
.badge-expired{background:rgba(255,68,68,.12);color:var(--red);}
.badge-destruct{background:rgba(255,107,157,.12);color:var(--accent3);}
.link-actions{display:flex;gap:6px;}
/* STORAGE */
.storage-breakdown{display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:12px;margin-bottom:24px;}
.stor-cat{background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:16px;}
.stor-cat-top{display:flex;align-items:center;gap:10px;margin-bottom:10px;}
.stor-cat-ico{font-size:22px;}
.stor-cat-name{font-size:.85rem;font-weight:500;}
.stor-cat-size{font-size:.75rem;color:var(--muted);margin-top:2px;}
.stor-bar{height:5px;background:var(--surface2);border-radius:99px;overflow:hidden;}
.stor-fill{height:100%;border-radius:99px;}
/* DUPES */
.dupe-group{background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:16px;margin-bottom:12px;}
.dupe-hdr{display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;}
.dupe-title{font-size:.85rem;font-weight:600;}
.dupe-save{font-size:.73rem;color:var(--accent);}
.dupe-files{display:flex;flex-direction:column;gap:8px;}
.dupe-file{display:flex;align-items:center;gap:10px;padding:8px 10px;background:var(--surface2);border-radius:8px;}
.dupe-file-ico{font-size:18px;}
.dupe-file-name{flex:1;font-size:.8rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.dupe-file-size{font-size:.72rem;color:var(--muted);}
.dupe-del{background:none;border:1px solid var(--border);border-radius:6px;padding:3px 9px;color:var(--muted);font-size:.72rem;cursor:pointer;transition:all .2s;}
.dupe-del:hover{border-color:var(--red);color:var(--red);}
/* BREADCRUMB */
.breadcrumb{display:flex;align-items:center;gap:5px;font-size:.78rem;color:var(--muted);margin-top:4px;}
.breadcrumb span{cursor:pointer;transition:color .2s;}
.breadcrumb span:hover{color:var(--accent);}
.breadcrumb span:last-child{color:var(--text);}
/* MOBILE MORE SHEET */
.mob-more-bg{display:none;position:fixed;inset:0;z-index:490;background:rgba(4,6,10,.6);backdrop-filter:blur(4px);}
.mob-more-bg.active{display:block;}
.mob-more-sheet{position:fixed;bottom:0;left:0;right:0;z-index:495;background:var(--surface);border-radius:20px 20px 0 0;border-top:1px solid var(--border);padding:12px 16px 24px;transform:translateY(100%);transition:transform .3s cubic-bezier(.4,0,.2,1);}
.mob-more-sheet.active{transform:translateY(0);}
.mob-more-handle{width:36px;height:4px;border-radius:99px;background:var(--surface3);margin:0 auto 16px;}
.mob-more-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:8px;}
.mob-more-grid button{display:flex;flex-direction:column;align-items:center;gap:5px;background:var(--surface2);border:1px solid var(--border);border-radius:12px;padding:12px 6px;font-size:.72rem;color:var(--muted);cursor:pointer;font-family:'Epilogue',sans-serif;transition:all .2s;}
.mob-more-grid button span:first-child{font-size:22px;}
.mob-more-grid button:hover,.mob-more-grid button:active{border-color:var(--accent);color:var(--accent);}
/* MOBILE NAV */
.mob-quick-nav{display:none;overflow-x:auto;white-space:nowrap;padding:0 0 10px;margin-bottom:2px;-webkit-overflow-scrolling:touch;scrollbar-width:none;width:100%;}
.mob-quick-nav::-webkit-scrollbar{display:none;}
.mob-quick-nav button{display:inline-flex;align-items:center;gap:5px;background:var(--surface);border:1px solid var(--border);border-radius:20px;padding:6px 12px;font-size:.76rem;color:var(--muted);cursor:pointer;font-family:'Epilogue',sans-serif;transition:all .2s;margin-right:6px;white-space:nowrap;flex-shrink:0;}
.mob-quick-nav button:hover,.mob-quick-nav button:active{border-color:var(--accent);color:var(--accent);}
/* only show pills on mobile — sidebar handles nav on desktop */
@media(max-width:768px){
  .mob-quick-nav{display:block;}
  .home-upload-btn{display:none;}
}
@media(min-width:769px){
  .home-upload-btn{display:none;}
  .mob-quick-nav{display:none !important;}
}
.mob-nav{display:none;position:fixed;bottom:0;left:0;right:0;background:var(--surface);border-top:1px solid var(--border);z-index:300;padding:8px 0 env(safe-area-inset-bottom,8px);}
.mob-nav-inner{display:flex;}
.mob-nav-btn{flex:1;display:flex;flex-direction:column;align-items:center;gap:3px;background:none;border:none;color:var(--muted);font-size:10px;cursor:pointer;padding:6px 0;transition:color .2s;position:relative;}
.mob-nav-btn.active{color:var(--accent);}
.mob-nav-btn.active::before{content:'';position:absolute;top:0;left:50%;transform:translateX(-50%);width:20px;height:3px;border-radius:0 0 3px 3px;background:var(--accent);}
.mob-nav-btn span:first-child{font-size:20px;}
.mob-upload-wrap{flex:1;display:flex;align-items:center;justify-content:center;}
.mob-upload-btn{width:46px;height:46px;border-radius:50%;background:linear-gradient(135deg,var(--accent),var(--accent2));display:flex;align-items:center;justify-content:center;color:#04060a;font-size:20px;font-weight:700;box-shadow:0 0 18px rgba(0,245,160,.45);cursor:pointer;border:none;margin-top:-10px;}
/* SWIPE PAGES */
.main{overflow-x:hidden;}
.pages-wrap{display:flex;transition:transform .3s cubic-bezier(.4,0,.2,1);}
/* TOAST STACK */
.toast-stack{position:fixed;bottom:20px;right:20px;display:flex;flex-direction:column;gap:8px;z-index:600;pointer-events:none;}
.toast-item{background:var(--surface);border-radius:12px;padding:12px 16px;font-size:.83rem;display:flex;align-items:center;gap:9px;box-shadow:0 8px 32px rgba(0,0,0,.4);max-width:300px;pointer-events:all;position:relative;overflow:hidden;border-left:3px solid var(--accent);animation:toastIn .25s ease both;}
.toast-item.error{border-left-color:var(--red);}
.toast-item.warn{border-left-color:var(--gold);}
.toast-item.info{border-left-color:var(--accent2);}
@keyframes toastIn{from{opacity:0;transform:translateX(20px)}to{opacity:1;transform:translateX(0)}}
.toast-progress{position:absolute;bottom:0;left:0;height:2px;background:currentColor;opacity:.3;transition:width linear;}
/* MODAL */
.modal-bg{display:none;position:fixed;inset:0;z-index:800;background:rgba(4,6,10,.85);backdrop-filter:blur(8px);align-items:center;justify-content:center;}
.modal-bg.active{display:flex;}
.modal{background:var(--surface);border:1px solid var(--border);border-radius:20px;padding:28px;max-width:460px;width:90%;position:relative;}
.modal-close{position:absolute;top:14px;right:14px;background:none;border:none;color:var(--muted);font-size:18px;cursor:pointer;transition:color .2s;}
.modal-close:hover{color:var(--text);}
.modal h2{font-family:'Clash Display',sans-serif;font-size:1.1rem;font-weight:700;margin-bottom:6px;}
.modal p{color:var(--muted);font-size:.83rem;margin-bottom:18px;line-height:1.5;}
.modal-input{width:100%;background:var(--surface2);border:1px solid var(--border);border-radius:9px;padding:10px 14px;color:var(--text);font-family:'Epilogue',sans-serif;font-size:.85rem;outline:none;margin-bottom:10px;}
.modal-input:focus{border-color:rgba(0,245,160,.4);}
.modal-actions{display:flex;gap:8px;flex-wrap:wrap;}
.modal-btn{padding:9px 18px;border-radius:9px;border:1px solid var(--border);background:none;color:var(--text);font-size:.82rem;cursor:pointer;font-family:'Epilogue',sans-serif;transition:all .2s;}
.modal-btn:hover{border-color:var(--accent);color:var(--accent);}
.modal-btn.primary{background:var(--accent);border-color:var(--accent);color:#04060a;font-family:'Clash Display',sans-serif;font-weight:700;}
.modal-btn.primary:hover{opacity:.85;color:#04060a;}
/* QR CODE PLACEHOLDER */
.qr-box{width:160px;height:160px;background:var(--surface2);border:1px solid var(--border);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:64px;margin:0 auto 16px;}
/* SETTINGS PAGE */
.settings-section{background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:18px 20px;margin-bottom:14px;}
.settings-section h3{font-family:'Clash Display',sans-serif;font-size:.88rem;font-weight:600;margin-bottom:14px;color:var(--muted);text-transform:uppercase;letter-spacing:1px;}
.setting-row{display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--border);}
.setting-row:last-child{border-bottom:none;}
.setting-label{font-size:.85rem;}
.setting-sub{font-size:.73rem;color:var(--muted);margin-top:2px;}
.toggle{width:40px;height:22px;border-radius:99px;background:var(--surface3);border:none;cursor:pointer;position:relative;transition:background .2s;flex-shrink:0;}
.toggle.on{background:var(--accent);}
.toggle::after{content:'';position:absolute;top:3px;left:3px;width:16px;height:16px;border-radius:50%;background:#fff;transition:transform .2s;}
.toggle.on::after{transform:translateX(18px);}
.setting-select{background:var(--surface2);border:1px solid var(--border);border-radius:7px;padding:5px 10px;color:var(--text);font-family:'Epilogue',sans-serif;font-size:.82rem;outline:none;}
/* SEARCH DROPDOWN */
.search-drop{display:none;position:absolute;top:calc(100% + 6px);left:0;right:0;background:var(--surface);border:1px solid var(--border);border-radius:12px;z-index:400;box-shadow:0 16px 48px rgba(0,0,0,.6);overflow:hidden;max-height:320px;overflow-y:auto;}
.search-drop.active{display:block;}
.sdrop-label{font-size:.67rem;text-transform:uppercase;letter-spacing:1.5px;color:var(--muted);padding:10px 14px 4px;}
.sdrop-item{display:flex;align-items:center;gap:10px;padding:9px 14px;cursor:pointer;transition:background .15s;font-size:.83rem;}
.sdrop-item:hover{background:var(--surface2);}
.sdrop-cat{font-size:.68rem;color:var(--muted);margin-left:auto;background:var(--surface2);padding:2px 7px;border-radius:20px;}
/* NOTIF ITEMS */
.notif-item{display:flex;align-items:flex-start;gap:10px;padding:11px 14px;cursor:pointer;transition:background .15s;}
.notif-item:hover{background:var(--surface2);}
.notif-item.unread{background:rgba(0,245,160,.03);}
.notif-ico{font-size:17px;flex-shrink:0;margin-top:1px;}
.notif-txt{font-size:.8rem;line-height:1.5;flex:1;}
.notif-time{font-size:.7rem;color:var(--muted);margin-top:2px;}
.notif-mark-all{padding:9px 14px;font-size:.75rem;color:var(--accent);cursor:pointer;border-top:1px solid var(--border);text-align:center;}
.notif-mark-all:hover{background:var(--surface2);}
/* PULSE ANIMATION for profile fields */
@keyframes pulseField{0%,100%{border-color:var(--border);}50%{border-color:var(--accent);box-shadow:0 0 0 3px rgba(0,245,160,.15);}}
.pulse-field{animation:pulseField 1.2s ease 3;}

/* RESPONSIVE UTILITY GRIDS & AUDIT TRAIL CLASSES */
.admin-grid-split {
  display: grid;
  grid-template-columns: 1fr;
  gap: 20px;
}
@media(min-width:769px) {
  .admin-grid-split {
    grid-template-columns: 1fr 1fr;
  }
}

.admin-settings-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 20px;
}
@media(min-width:600px) {
  .admin-settings-grid {
    grid-template-columns: 1fr 1fr;
  }
}
@media(min-width:1024px) {
  .admin-settings-grid {
    grid-template-columns: 1fr 1fr 1fr;
  }
}

.audit-grid-row {
  display: grid;
  grid-template-columns: 32px 1fr 140px 140px 100px;
  gap: 10px;
  align-items: center;
}
@media(max-width:900px) {
  .audit-grid-row {
    grid-template-columns: 32px 1fr 120px 120px;
  }
  .audit-col-device {
    display: none !important;
  }
}
@media(max-width:600px) {
  .audit-grid-row {
    grid-template-columns: 24px 1fr 90px;
    gap: 8px;
  }
  .audit-col-member, .audit-col-device {
    display: none !important;
  }
  .audit-col-date {
    text-align: right;
    font-size: 0.72rem !important;
  }
}

/* ── MOBILE ≤ 768px ─────────────────────────────────────────────────────── */
@media(max-width:768px){
  /* Hide desktop-only elements */
  .sidebar{display:none;}
  .storage-pill{display:none;}
  .t-btn.desktop-only{display:none;}
  /* Topbar: logo + search + avatar + notif only */
  .topbar{padding:0 14px;gap:10px;height:54px;}
  :root{--topbar-h:54px;}
  .t-logo{width:auto;font-size:.95rem;}
  .t-logo img{width:24px;height:24px;}
  /* Hide upload/folder/settings buttons from topbar on mobile — they live in bottom nav */
  .t-btn.mob-hide{display:none;}
  /* Search bar takes remaining space */
  .t-search{max-width:none;}
  .t-search input{font-size:.8rem;padding:7px 12px 7px 32px;}
  /* Main content */
  .app-body{flex-direction:column;}
  .main{padding-bottom:72px;overflow-y:auto;}
  .page{padding:14px 14px 20px;}
  /* Details panel — bottom sheet */
  .details{
    width:100%;right:0;left:0;
    top:auto;bottom:72px;
    height:auto;
    max-height:65vh;
    transform:translateY(100%);
    border-radius:20px 20px 0 0;
    border-left:none;
    border-top:1px solid var(--border);
    overflow-y:auto;
  }
  .details.open{transform:translateY(0);}
  /* Compact preview on mobile */
  .det-prev-box{aspect-ratio:unset;height:120px;min-height:unset;}
  .det-prev-box video{height:120px;width:100%;object-fit:contain;}
  .det-prev-box img{height:120px;width:100%;object-fit:contain;}
  .det-preview{padding:10px;}
  .det-meta{padding:10px;}
  .det-actions{padding:10px;gap:6px;}
  .act-btn{padding:8px 4px;font-size:.72rem;}
  /* Bottom nav */
  .mob-nav{display:block;}
  /* File grids — 3 columns on phones for photos, 2 for files */
  .file-grid{grid-template-columns:repeat(2,1fr);gap:8px;}
  .photo-grid{grid-template-columns:repeat(3,1fr);gap:5px;}
  .photo-tile{border-radius:8px;}
  .file-card{padding:10px 8px;}
  .fc-ico{font-size:24px;margin-bottom:6px;}
  .fc-name{font-size:.72rem;}
  .fc-meta{font-size:.65rem;}
  /* Onboarding steps — single column */
  .ob-steps{grid-template-columns:1fr;}
  /* Page header — stack vertically */
  .page-hdr{flex-direction:column;align-items:flex-start;gap:10px;}
  .page-actions{width:100%;justify-content:flex-start;}
  /* Settings rows */
  .settings-section{padding:14px;}
  /* Stat cards — 2 per row */
  .stat-card{padding:14px 10px;}
  .stat-val{font-size:1.2rem;}
  /* Storage breakdown — 2 per row */
  .storage-breakdown{grid-template-columns:repeat(2,1fr);}
  /* Modals */
  .modal{padding:20px;border-radius:16px 16px 0 0;position:fixed;bottom:0;left:0;right:0;max-width:100%;margin:0;border-bottom-left-radius:0;border-bottom-right-radius:0;}
  .modal-bg.active{align-items:flex-end;}
  /* Bulk bar */
  .bulk-bar{bottom:80px;left:12px;right:12px;transform:none;flex-wrap:wrap;gap:6px;}
  /* Upload bar */
  .upload-bar{left:12px;right:12px;transform:none;min-width:0;}
  /* Toast stack */
  .toast-stack{bottom:80px;right:12px;left:12px;}
  .toast-item{max-width:100%;}
  /* Home welcome section */
  .home-welcome{flex-direction:column;align-items:flex-start;gap:10px;}
  .home-welcome .upg-btn{width:100%;}
  /* Onboarding bar — hidden on mobile */
  .desktop-onboard{display:none !important;}
  .onboard-bar{padding:14px;}
  /* Quick access grid */
  .qa-grid-mobile{grid-template-columns:repeat(3,1fr);}
  /* Link rows */
  .link-row{flex-wrap:wrap;gap:8px;}
  .link-actions{width:100%;justify-content:flex-end;}
}

/* ── TABLET 769–1024px — sidebar collapses to icons ────────────────────── */
@media(min-width:769px) and (max-width:1024px){
  :root{--sidebar-w:56px;}
  .sidebar{width:56px;}
  .sb-lbl,.nav-txt,.nav-bdg,.sb-storage-inner{display:none;}
  .sb-toggle{display:none;}
  .nav-item{justify-content:center;padding:10px 0;}
  .nav-ico{width:100%;text-align:center;font-size:18px;}
  .t-logo{width:auto;}
  .storage-pill{display:none;}
}

/* ── TABLET 600–768px ───────────────────────────────────────────────────── */
@media(min-width:600px) and (max-width:768px){
  .file-grid{grid-template-columns:repeat(3,1fr);gap:8px;}
  .photo-grid{grid-template-columns:repeat(4,1fr);gap:5px;}
  .ob-steps{grid-template-columns:repeat(2,1fr);}
  .storage-breakdown{grid-template-columns:repeat(3,1fr);}
  .det-prev-box{height:140px;}
  .det-prev-box video,.det-prev-box img{height:140px;}
}

/* ── SMALL PHONES ≤ 400px ───────────────────────────────────────────────── */
@media(max-width:400px){
  .file-grid{grid-template-columns:repeat(2,1fr);gap:8px;}
  .photo-grid{grid-template-columns:repeat(2,1fr);}
  .t-search input{font-size:.75rem;}
  .page{padding:10px 10px 20px;}
  .ob-steps{grid-template-columns:1fr;}
}

/* ── DATA STORAGE SYSTEM ENHANCEMENTS ───────────────────────────────────── */
/* List-view column header row */
.file-list-row-hdr{display:flex;align-items:center;gap:0;padding:8px 14px;font-size:.66rem;font-weight:700;text-transform:uppercase;letter-spacing:1.2px;color:var(--muted);background:var(--surface2);border:1px solid var(--border);border-radius:10px 10px 0 0;border-bottom:1px solid rgba(0,245,160,.12);position:sticky;top:0;z-index:2;}
.file-list-row-hdr .flr-chk{width:34px;flex-shrink:0;}
.file-list-row-hdr .flr-ico{width:36px;flex-shrink:0;}
.file-list-row-hdr .flr-name{flex:1;min-width:0;}
.file-list-row-hdr .flr-type{width:76px;text-align:center;flex-shrink:0;}
.file-list-row-hdr .flr-size{width:72px;text-align:right;flex-shrink:0;}
.file-list-row-hdr .flr-date{width:110px;text-align:right;flex-shrink:0;padding-right:4px;}
@media(max-width:600px){
  .file-list-row-hdr .flr-type,.file-list-row-hdr .flr-date{display:none;}
  .file-list-row-hdr .flr-size{width:60px;}
}

/* File type badge */
.fc-type-badge{display:inline-flex;align-items:center;justify-content:center;font-size:.58rem;font-weight:700;letter-spacing:.04em;text-transform:uppercase;padding:2px 7px;border-radius:5px;flex-shrink:0;line-height:1.5;}
.ftb-photo{background:rgba(0,212,255,.13);color:#00d4ff;border:1px solid rgba(0,212,255,.2);}
.ftb-video{background:rgba(255,107,157,.13);color:#ff6b9d;border:1px solid rgba(255,107,157,.2);}
.ftb-music{background:rgba(167,139,250,.13);color:#a78bfa;border:1px solid rgba(167,139,250,.2);}
.ftb-doc{background:rgba(251,146,60,.13);color:#fb923c;border:1px solid rgba(251,146,60,.2);}
.ftb-other{background:rgba(107,114,128,.13);color:#8b929e;border:1px solid rgba(107,114,128,.2);}
.ftb-folder{background:rgba(255,209,102,.12);color:#ffd166;border:1px solid rgba(255,209,102,.2);}
@media(max-width:600px){.fc-type-badge{display:none;}}

/* Enhanced list-view row styling */
.file-grid.list-view{border:1px solid var(--border);border-radius:0 0 10px 10px;overflow:hidden;}
.file-grid.list-view .file-card{display:flex;align-items:center;gap:0;padding:9px 14px;border-radius:0;border:none;border-bottom:1px solid rgba(255,255,255,.04);transition:background .15s;}
.file-grid.list-view .file-card:last-child{border-bottom:none;}
.file-grid.list-view .file-card:hover{border-color:transparent;transform:none;background:rgba(0,245,160,.04);}
.file-grid.list-view .file-card .fc-ico-lv{width:36px;flex-shrink:0;display:flex;align-items:center;}
.file-grid.list-view .file-card .fc-name{flex:1;min-width:0;font-size:.83rem;font-weight:500;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.file-grid.list-view .file-card .fc-lv-type{width:76px;text-align:center;flex-shrink:0;}
.file-grid.list-view .file-card .fc-lv-size{width:72px;text-align:right;flex-shrink:0;font-size:.74rem;color:var(--muted);}
.file-grid.list-view .file-card .fc-lv-date{width:110px;text-align:right;flex-shrink:0;font-size:.74rem;color:var(--muted);padding-right:4px;}
@media(max-width:600px){
  .file-grid.list-view .file-card .fc-lv-type,.file-grid.list-view .file-card .fc-lv-date{display:none;}
  .file-grid.list-view .file-card .fc-lv-size{width:60px;}
}

/* Grid view card polish — premium data storage tile look */
.file-card{transition:border-color .18s,background .18s,transform .18s,box-shadow .18s;}
.file-card:hover{border-color:rgba(0,245,160,.3);box-shadow:0 4px 20px rgba(0,245,160,.08);}
.file-card.selected{border-color:var(--accent);background:rgba(0,245,160,.06);box-shadow:0 0 0 1px rgba(0,245,160,.3);}

/* Section header row used above file grids */
.grid-section-hdr{display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;padding-bottom:8px;border-bottom:1px solid var(--border);}
.gsh-left{display:flex;align-items:center;gap:8px;font-family:'Clash Display',sans-serif;font-size:.82rem;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.08em;}
.gsh-count{background:var(--surface2);border:1px solid var(--border);border-radius:20px;padding:1px 9px;font-size:.66rem;color:var(--muted);font-weight:600;font-family:'Epilogue',sans-serif;}

/* Sidebar nav item with active glow */
.nav-item.active{background:rgba(0,245,160,.09);color:var(--accent);box-shadow:inset 0 0 0 1px rgba(0,245,160,.14);}

/* Topbar search glass style */
.t-search input{background:rgba(255,255,255,.04);backdrop-filter:blur(8px);}

/* Breadcrumb separator */
.breadcrumb span:not(:last-child)::after{content:'/';margin-left:5px;margin-right:0;color:var(--surface3);}

/* Premium Multi-Channel Social Sharing Grid */
.share-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 12px;
  margin-bottom: 20px;
}
.share-grid-btn {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding: 14px 10px;
  background: rgba(255, 255, 255, 0.03);
  border: 1px solid rgba(255, 255, 255, 0.08);
  border-radius: 12px;
  color: var(--text);
  font-family: 'Clash Display', sans-serif;
  font-weight: 600;
  font-size: 0.72rem;
  cursor: pointer;
  transition: all 0.2s ease;
}
.share-grid-btn svg, .share-grid-btn i {
  width: 24px;
  height: 24px;
  transition: transform 0.2s ease;
}
.share-grid-btn:hover {
  background: rgba(0, 245, 160, 0.05);
  border-color: rgba(0, 245, 160, 0.3);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 245, 160, 0.08);
}
.share-grid-btn:hover svg, .share-grid-btn:hover i {
  transform: scale(1.1);
}

/* Platform specific hover accents */
.btn-wa:hover { border-color: #25D366; background: rgba(37, 211, 102, 0.08); color: #25D366; }
.btn-li:hover { border-color: #0077b5; background: rgba(0, 119, 181, 0.08); color: #0077b5; }
.btn-tw:hover { border-color: #1da1f2; background: rgba(29, 161, 242, 0.08); color: #1da1f2; }
.btn-tg:hover { border-color: #0088cc; background: rgba(0, 136, 204, 0.08); color: #0088cc; }
.btn-ml:hover { border-color: var(--accent); background: rgba(0, 245, 160, 0.08); color: var(--accent); }
.btn-cp:hover { border-color: #a78bfa; background: rgba(167, 139, 250, 0.08); color: #a78bfa; }

@media(max-width:480px){
  .share-grid {
    grid-template-columns: repeat(2, 1fr);
    gap: 8px;
  }
}
</style>

<!-- DROP OVERLAY -->
<div class="drop-overlay" id="dropOverlay">
  <div class="drop-box">
    <div class="drop-box-ico"><i data-lucide="upload-cloud" style="width:56px;height:56px;color:var(--accent);stroke-width:1.2;"></i></div>
    <div class="drop-box-title">Drop to Upload</div>
    <div class="drop-box-sub">Release to add files to your vault</div>
  </div>
</div>

<!-- UPLOAD PROGRESS BAR -->
<div class="upload-bar" id="uploadBar">
  <div class="ub-title"><span id="ubFileName">Uploading...</span><span id="ubPct">0%</span></div>
  <div class="ub-track"><div class="ub-fill" id="ubFill" style="width:0%"></div></div>
</div>


<!-- BULK ACTION BAR -->
<div class="bulk-bar" id="bulkBar">
  <span class="bulk-count" id="bulkCount">0 selected</span>
  <button class="bulk-btn" onclick="bulkDownload()"><i data-lucide="download" class="lucide"></i> Download</button>
  <button class="bulk-btn" onclick="bulkMove()"><i data-lucide="folder-input" class="lucide"></i> Move</button>
  <button class="bulk-btn" onclick="bulkShare()"><i data-lucide="share-2" class="lucide"></i> Share</button>
  <button class="bulk-btn d" onclick="bulkDelete()"><i data-lucide="trash-2" class="lucide"></i> Delete</button>
  <button class="bulk-btn" onclick="clearSelection()"><i data-lucide="x" class="lucide"></i> Clear</button>
</div>

<!-- KEYBOARD SHORTCUT SHEET -->
<div class="shortcut-sheet" id="shortcutSheet" onclick="closeShortcuts()">
  <div class="sc-box" onclick="event.stopPropagation()">
    <h2>Keyboard Shortcuts</h2>
    <div class="sc-row"><span>Upload file</span><span class="sc-key">U</span></div>
    <div class="sc-row"><span>New folder</span><span class="sc-key">N</span></div>
    <div class="sc-row"><span>Search</span><span class="sc-key">/</span></div>
    <div class="sc-row"><span>Toggle grid / list</span><span class="sc-key">V</span></div>
    <div class="sc-row"><span>Select all</span><span class="sc-key">Ctrl+A</span></div>
    <div class="sc-row"><span>Delete selected</span><span class="sc-key">Del</span></div>
    <div class="sc-row"><span>Close panel / modal</span><span class="sc-key">Esc</span></div>
    <div class="sc-row"><span>Show shortcuts</span><span class="sc-key">?</span></div>
    <div class="sc-row"><span>Open Profile</span><span class="sc-key">P</span></div>
    <div class="sc-row"><span>Log out (hold)</span><span class="sc-key">L</span></div>
  </div>
</div>

<!-- TOPBAR -->
<header class="topbar">
  <a href="/" class="t-logo">
    <img src="/favicon.png" alt="logo"/>
    Keeption <em>Vault</em>
  </a>
  <div class="t-search">
    <span class="t-search-ico"><i data-lucide="search" class="lucide"></i></span>
    <input type="text" id="globalSearch" placeholder="Search your files…" autocomplete="off" oninput="handleSearch(this.value)" onfocus="showSearchDrop()" onblur="setTimeout(hideSearchDrop,200)"/>
    <div class="search-drop" id="searchDrop"></div>
  </div>
  <div class="t-right">
    <button class="t-btn mob-hide" title="Upload (U)" onclick="triggerUpload()"><i data-lucide="upload" class="lucide"></i></button>
    <button class="t-btn mob-hide" title="New Folder (N)" onclick="newFolder()"><i data-lucide="folder-plus" class="lucide"></i></button>
    <button class="t-btn mob-hide" title="Settings" onclick="navTo('page-settings')"><i data-lucide="settings" class="lucide"></i></button>
    <div class="storage-pill" onclick="navTo('page-storage')" title="Storage" id="storagePill">
      <span id="storageLabel">0 B / 5 GB</span>
      <div class="sp-bar"><div class="sp-fill" id="spFill" style="width:0%"></div></div>
    </div>
    <div class="notif-wrap" id="notifWrap">
      <button class="t-btn" title="Notifications" id="notifBtn" onclick="toggleNotifDrop()">
        <i data-lucide="bell" class="lucide"></i>
        <span class="t-badge" id="notifBadge" style="display:none">0</span>
      </button>
      <div class="notif-drop" id="notifDrop" style="display:none;">
        <div class="notif-hdr" style="display:flex;justify-content:space-between;align-items:center;">
          <span>Notifications</span>
          <span style="font-size:.72rem;color:var(--accent);cursor:pointer;" onclick="markAllRead()">Mark all read</span>
        </div>
        <div id="notifList"><div class="notif-empty">You're all caught up</div></div>
      </div>
    </div>
    <div style="display:flex;align-items:center;gap:6px;background:rgba(0,245,160,0.08);border:1px solid rgba(0,245,160,0.2);border-radius:8px;padding:5px 12px;font-size:.72rem;font-weight:600;color:#00f5a0;white-space:nowrap;">
      @if($isTeams) ?? @elseif($isPro) ? @else ?? @endif
      {{ $planLabel }} Plan
    </div>
    <div class="av-wrap">
      <div class="av-btn" id="avBtn">KV</div>
      <div class="av-drop">
        <div class="av-email" id="avEmail">{{ session('user_email', session('supabase_email','')) }}</div>
        <div id="planBadgeWrap" style="margin:4px 0 8px;"></div>
        <a href="#" onclick="navTo('page-profile')"><i data-lucide="user" class="lucide"></i> Public Profile</a>
        <a href="#" onclick="navTo('page-storage')"><i data-lucide="hard-drive" class="lucide"></i> Storage</a>
        <hr/>
        <form method="POST" action="/logout">@csrf<button type="submit"><i data-lucide="log-out" class="lucide"></i> Sign Out</button></form>
      </div>
    </div>
  </div>
</header>

<!-- APP BODY -->
<div class="app-body">

  <!-- SIDEBAR -->
  <nav class="sidebar" id="sidebar">
    <div class="sb-toggle" id="sbToggle" onclick="toggleSidebar()"><i data-lucide="chevron-left" class="lucide" style="width:12px;height:12px;"></i></div>
    <div class="sb-sec">
      <div class="sb-lbl">Main</div>
      <button class="nav-item active" data-page="page-home" onclick="navTo('page-home')"><span class="nav-ico"><i data-lucide="home" class="lucide lucide-lg"></i></span><span class="nav-txt">Home</span></button>
      <button class="nav-item" data-page="page-files" onclick="navTo('page-files')"><span class="nav-ico"><i data-lucide="folder" class="lucide lucide-lg" style="color:#ffd166"></i></span><span class="nav-txt">My Files</span></button>
      <button class="nav-item" data-page="page-photos" onclick="navTo('page-photos')"><span class="nav-ico"><i data-lucide="image" class="lucide lucide-lg" style="color:#00d4ff"></i></span><span class="nav-txt">Photos</span></button>
      <button class="nav-item" data-page="page-videos" onclick="navTo('page-videos')"><span class="nav-ico"><i data-lucide="video" class="lucide lucide-lg" style="color:#ff6b9d"></i></span><span class="nav-txt">Videos</span></button>
      <button class="nav-item" data-page="page-music" onclick="navTo('page-music')"><span class="nav-ico"><i data-lucide="music" class="lucide lucide-lg" style="color:#a78bfa"></i></span><span class="nav-txt">Music</span></button>
      <button class="nav-item" data-page="page-docs" onclick="navTo('page-docs')"><span class="nav-ico"><i data-lucide="file-text" class="lucide lucide-lg" style="color:#fb923c"></i></span><span class="nav-txt">Documents</span></button>
    </div>

    <div class="sb-sec">
      <div class="sb-lbl">Tools</div>
      <button class="nav-item" onclick="document.getElementById('globalSearch').focus()"><span class="nav-ico"><i data-lucide="search" class="lucide lucide-lg" style="color:#00f5a0"></i></span><span class="nav-txt">Quick Search</span></button>
      <button class="nav-item" id="nav-ai" data-page="page-ai" onclick="{{ $isFree ? 'openLockedModal(\'ai-search\')' : 'navTo(\'page-ai\')' }}"><span class="nav-ico"><i data-lucide="sparkles" class="lucide lucide-lg" style="color:#ffd166"></i></span><span class="nav-txt">AI Smart Search</span>@if($isFree)<span style="margin-left:auto;font-size:.6rem;background:rgba(0,245,160,0.1);color:#00f5a0;border-radius:4px;padding:1px 5px;">PRO</span>@endif</button>
      <button class="nav-item" data-page="page-dupes" onclick="navTo('page-dupes')"><span class="nav-ico"><i data-lucide="copy" class="lucide lucide-lg" style="color:#a78bfa"></i></span><span class="nav-txt">Duplicate Finder</span></button>
      <button class="nav-item" data-page="page-storage" onclick="navTo('page-storage')"><span class="nav-ico"><i data-lucide="pie-chart" class="lucide lucide-lg" style="color:#00d4ff"></i></span><span class="nav-txt">Storage Analyzer</span></button>
      <button class="nav-item" data-page="page-bin" onclick="navTo('page-bin')"><span class="nav-ico"><i data-lucide="trash-2" class="lucide lucide-lg" style="color:#ff4444"></i></span><span class="nav-txt">Recycle Bin</span></button>
      <button class="nav-item" data-page="page-settings" onclick="navTo('page-settings')"><span class="nav-ico"><i data-lucide="settings" class="lucide lucide-lg"></i></span><span class="nav-txt">Settings</span></button>
    </div>
    <div class="sb-storage">
      <div class="sb-storage-inner">
        <div class="sb-stor-row"><span>Storage</span><span id="sbStorLabel">0 B / 5 GB</span></div>
        <div class="sb-stor-bar"><div class="sb-stor-fill" id="sbStorFill" style="width:0%"></div></div>
        <button class="upg-btn" id="sidebarUpgBtn" onclick="navTo('page-storage')">Upgrade Plan</button>
      </div>
    </div>
      <div id="sb-teams-section" style="display:none;">
        <div class="sb-sec">
          <div class="sb-lbl">Team</div>
          <button class="nav-item" data-page="page-team-files" onclick="navTo('page-team-files')"><span class="nav-ico"><i data-lucide="users" class="lucide lucide-lg" style="color:#00d4ff"></i></span><span class="nav-txt">Team Files</span>@if(!$isTeams)<span style="margin-left:auto;font-size:.6rem;background:rgba(0,212,255,0.1);color:#00d4ff;border-radius:4px;padding:1px 5px;">TEAMS</span>@endif</button>
          <button class="nav-item" data-page="page-audit" onclick="navTo('page-audit')"><span class="nav-ico"><i data-lucide="scroll-text" class="lucide lucide-lg" style="color:#a78bfa"></i></span><span class="nav-txt">Audit Trail</span>@if(!$isTeams)<span style="margin-left:auto;font-size:.6rem;background:rgba(0,212,255,0.1);color:#00d4ff;border-radius:4px;padding:1px 5px;">TEAMS</span>@endif</button>
          <button class="nav-item" data-page="page-admin" onclick="navTo('page-admin')"><span class="nav-ico"><i data-lucide="shield" class="lucide lucide-lg" style="color:#ffd166"></i></span><span class="nav-txt">Admin Dashboard</span>@if(!$isTeams)<span style="margin-left:auto;font-size:.6rem;background:rgba(0,212,255,0.1);color:#00d4ff;border-radius:4px;padding:1px 5px;">TEAMS</span>@endif</button>
          <button class="nav-item" data-page="page-team-members" onclick="navTo('page-team-members')"><span class="nav-ico"><i data-lucide="user-plus" class="lucide lucide-lg" style="color:#fb923c"></i></span><span class="nav-txt">Team Members</span></button>
        </div>
      </div>
  </nav>

  <!-- MAIN CONTENT -->
  <main class="main" id="mainContent">
  <div class="main-watermark"></div>

    <!-- HOME PAGE -->
    <div class="page active" id="page-home">

      @php $usedPercent = 40; /* placeholder - will be dynamic later */ @endphp
      @if($usedPercent >= 100)
      <div id="storage-full-banner" style="background:rgba(255,68,68,0.1);border:1px solid rgba(255,68,68,0.3);border-radius:12px;padding:14px 18px;margin-bottom:20px;display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;">
        <div style="display:flex;align-items:center;gap:10px;">
          <span style="font-size:18px;">&#x1F534;</span>
          <div>
            <div style="font-size:.88rem;font-weight:600;color:#ff4444;">Storage Full</div>
            <div style="font-size:.78rem;color:#6b7280;margin-top:2px;">You've reached your {{ $storageGB }} GB limit. Upgrade to unlock more storage.</div>
          </div>
        </div>
        <button onclick="openUpgradeModal('storage')" style="background:#00f5a0;border:none;border-radius:8px;padding:8px 16px;color:#04060a;font-family:'Clash Display',sans-serif;font-weight:700;font-size:.78rem;cursor:pointer;white-space:nowrap;">Upgrade Now</button>
      </div>
      @endif

      <!-- Onboarding bar — desktop only -->
      <div class="onboard-bar desktop-onboard" id="onboardBar">
        <div class="ob-top">
          <span class="ob-title">Getting Started</span>
          <div style="display:flex;align-items:center;gap:10px;">
            <span class="ob-pct" id="obPct">0 of 4 complete</span>
            <button onclick="dismissOnboarding()" style="background:none;border:none;color:var(--muted);cursor:pointer;font-size:16px;line-height:1;" title="Dismiss">✕</button>
          </div>
        </div>
        <div class="ob-track"><div class="ob-fill" id="obFill" style="width:0%"></div></div>
        <div class="ob-steps">
          <div class="ob-step" id="ob-step-0">
            <span class="ob-step-ico"><i data-lucide="upload" class="lucide lucide-lg" style="color:var(--accent)"></i></span>
            <span class="ob-step-title">Upload your first file</span>
            <span class="ob-step-sub">Add any file to your vault to get started.</span>
            <button class="ob-step-btn" onclick="triggerUpload()">Upload Now</button>
          </div>
          <div class="ob-step" id="ob-step-1">
            <span class="ob-step-ico"><i data-lucide="camera" class="lucide lucide-lg" style="color:#00d4ff"></i></span>
            <span class="ob-step-title">Set up camera backup</span>
            <span class="ob-step-sub">Auto-save every photo you take.</span>
            <button class="ob-step-btn" onclick="openModal('modalCamera')">Turn On</button>
          </div>
          <div class="ob-step" id="ob-step-2">
            <span class="ob-step-ico"><i data-lucide="gift" class="lucide lucide-lg" style="color:#ffd166"></i></span>
            <span class="ob-step-title">Invite a friend</span>
            <span class="ob-step-sub">Both of you get +5 GB free storage.</span>
            <button class="ob-step-btn" onclick="openModal('modalInvite')">Invite Now</button>
          </div>
          <div class="ob-step" id="ob-step-3">
            <span class="ob-step-ico"><i data-lucide="user-circle" class="lucide lucide-lg" style="color:#a78bfa"></i></span>
            <span class="ob-step-title">Complete your profile</span>
            <span class="ob-step-sub">Add a name and bio to your public profile.</span>
            <button class="ob-step-btn" onclick="navTo('page-profile');pulseProfileFields()">Set Up</button>
          </div>
        </div>
      </div>

      <!-- Welcome heading -->
      <div class="home-welcome" style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;flex-wrap:wrap;gap:10px;">
        <div>
          <h1 style="font-family:'Clash Display',sans-serif;font-size:1.3rem;font-weight:700;line-height:1.2;" id="homeGreeting">Welcome to Keeption Vault</h1>
          <p style="color:var(--muted);font-size:.8rem;margin-top:4px;">Private, encrypted, completely yours.</p>
        </div>
        <button class="upg-btn home-upload-btn" style="width:auto;padding:8px 18px;" onclick="triggerUpload()"><i data-lucide="upload" class="lucide"></i> Upload</button>
      </div>

      <!-- Mobile quick-nav pills (only visible on mobile) -->
      <div class="mob-quick-nav" id="mobQuickNav">
        <button onclick="navTo('page-files')"><i data-lucide="folder" class="lucide" style="color:#ffd166"></i> Files</button>
        <button onclick="navTo('page-photos')"><i data-lucide="image" class="lucide" style="color:#00d4ff"></i> Photos</button>
        <button onclick="navTo('page-videos')"><i data-lucide="video" class="lucide" style="color:#ff6b9d"></i> Videos</button>
        <button onclick="navTo('page-music')"><i data-lucide="music" class="lucide" style="color:#a78bfa"></i> Music</button>
        <button onclick="navTo('page-docs')"><i data-lucide="file-text" class="lucide" style="color:#fb923c"></i> Docs</button>
        <button onclick="navTo('page-ai')"><i data-lucide="sparkles" class="lucide" style="color:#ffd166"></i> AI Search</button>
        <button onclick="navTo('page-storage')"><i data-lucide="pie-chart" class="lucide" style="color:#00d4ff"></i> Storage</button>
        <button onclick="navTo('page-settings')"><i data-lucide="settings" class="lucide"></i> Settings</button>
      </div>

      <!-- Recent files -->
      <div id="homeRecentSection" style="display:none;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
          <h2 style="font-family:'Clash Display',sans-serif;font-size:.95rem;font-weight:600;">Recent Files</h2>
          <button class="bulk-btn" onclick="navTo('page-files')" style="font-size:.73rem;">View all →</button>
        </div>
        <div class="file-grid" id="homeRecentGrid"></div>
      </div>

      <!-- Empty state -->
      <div id="homeEmptyState">
        <div class="empty-state home-empty" style="min-height:180px;padding:24px 16px;">
          <div class="es-illo"><i data-lucide="hard-drive" style="width:56px;height:56px;color:var(--muted);stroke-width:1;"></i></div>
          <div class="es-title">Your vault is empty</div>
          <div class="es-sub">Upload your first file to get started. Everything you store here is end-to-end encrypted and visible only to you.</div>
          <div class="es-actions">
            <button class="es-btn primary" onclick="triggerUpload()"><i data-lucide="upload" class="lucide"></i> Upload Files</button>
            <button class="es-btn" onclick="newFolder()"><i data-lucide="folder-plus" class="lucide"></i> Create Folder</button>
          </div>
        </div>
      </div>
    </div>

    <!-- MY FILES PAGE -->
    <div class="page" id="page-files">
      <div class="page-hdr">
        <div>
          <h1 class="page-title">My Files</h1>
          <div class="breadcrumb" id="filesBreadcrumb"><span>My Files</span></div>
        </div>
        <div class="page-actions">
          <button class="t-btn" title="Toggle view (V)" onclick="toggleView('filesGrid')"><i data-lucide="layout-grid" class="lucide"></i></button>
          <button class="bulk-btn" onclick="newFolder('page-files')"><i data-lucide="folder-plus" class="lucide"></i> New Folder</button>
          <button class="upg-btn" style="width:auto;padding:8px 16px;" onclick="triggerUpload()"><i data-lucide="upload" class="lucide"></i> Upload</button>
        </div>
      </div>
      <div id="filesGrid" class="file-grid"></div>
      <div id="filesEmpty" class="empty-state">
        <div class="es-illo"><i data-lucide="cloud" style="width:56px;height:56px;color:var(--muted);stroke-width:1;"></i></div>
        <div class="es-title">Your files will live here</div>
        <div class="es-sub">Upload files or drag and drop anything from your computer to get started.</div>
        <div class="es-actions">
          <button class="es-btn primary" onclick="triggerUpload()"><i data-lucide="upload" class="lucide"></i> Upload Files</button>
          <button class="es-btn" onclick="newFolder('page-files')"><i data-lucide="folder-plus" class="lucide"></i> Create Folder</button>
        </div>
      </div>
    </div>

    <!-- PHOTOS PAGE -->
    <div class="page" id="page-photos">
      <div class="page-hdr">
        <div>
          <h1 class="page-title">Photos</h1>
          <div class="breadcrumb" id="photosBreadcrumb"><span>Photos</span></div>
        </div>
        <div class="page-actions">
          <button class="bulk-btn" onclick="newFolder('page-photos')"><i data-lucide="folder-plus" class="lucide"></i> New Folder</button>
          <button class="upg-btn" style="width:auto;padding:8px 16px;" onclick="triggerUpload()"><i data-lucide="upload" class="lucide"></i> Upload</button>
        </div>
      </div>
      <div id="photosGrid" class="file-grid"></div>
      <div id="photosEmpty" class="empty-state">
        <div class="es-illo"><i data-lucide="camera" style="width:56px;height:56px;color:var(--muted);stroke-width:1;"></i></div>
        <div class="es-title">No photos yet</div>
        <div class="es-sub">Upload your photos here or turn on camera backup to automatically save every photo you take.</div>
        <div class="es-actions">
          <button class="es-btn primary" onclick="triggerUpload()"><i data-lucide="upload" class="lucide"></i> Upload Photos</button>
          <button class="es-btn" onclick="showToast('Camera backup — available on the mobile app','info')"><i data-lucide="camera" class="lucide"></i> Turn On Camera Backup</button>
        </div>
      </div>
    </div>

    <!-- VIDEOS PAGE -->
    <div class="page" id="page-videos">
      <div class="page-hdr">
        <div>
          <h1 class="page-title">Videos</h1>
          <div class="breadcrumb" id="videosBreadcrumb"><span>Videos</span></div>
        </div>
        <div class="page-actions">
          <button class="bulk-btn" onclick="newFolder('page-videos')"><i data-lucide="folder-plus" class="lucide"></i> New Folder</button>
          <button class="upg-btn" style="width:auto;padding:8px 16px;" onclick="triggerUpload()"><i data-lucide="upload" class="lucide"></i> Upload</button>
        </div>
      </div>
      <div id="videosGrid" class="file-grid"></div>
      <div id="videosEmpty" class="empty-state">
        <div class="es-illo"><i data-lucide="video" style="width:56px;height:56px;color:var(--muted);stroke-width:1;"></i></div>
        <div class="es-title">No videos yet</div>
        <div class="es-sub">Upload your videos and stream them from anywhere, on any device.</div>
        <div class="es-actions">
          <button class="es-btn primary" onclick="triggerUpload()"><i data-lucide="upload" class="lucide"></i> Upload Videos</button>
        </div>
      </div>
    </div>

    <!-- MUSIC PAGE -->
    <div class="page" id="page-music">
      <div class="page-hdr">
        <div>
          <h1 class="page-title">Music</h1>
          <div class="breadcrumb" id="musicBreadcrumb"><span>Music</span></div>
        </div>
        <div class="page-actions">
          <button class="bulk-btn" onclick="newFolder('page-music')"><i data-lucide="folder-plus" class="lucide"></i> New Folder</button>
          <button class="upg-btn" style="width:auto;padding:8px 16px;" onclick="triggerUpload()"><i data-lucide="upload" class="lucide"></i> Upload</button>
        </div>
      </div>
      <div id="musicPlayerWrap" style="display:none;">
        <div class="music-player">
          <div class="mp-now">
            <div class="mp-art" id="mpArt"><i data-lucide="music" style="width:24px;height:24px;color:var(--accent);"></i></div>
            <div class="mp-info"><div class="mp-title" id="mpTitle">No track playing</div></div>
            <div class="mp-controls">
              <button class="mp-btn"><i data-lucide="skip-back" class="lucide"></i></button>
              <button class="mp-btn mp-play" id="mpPlay" onclick="togglePlay()"><i data-lucide="play" class="lucide" id="mpPlayIco"></i></button>
              <button class="mp-btn"><i data-lucide="skip-forward" class="lucide"></i></button>
            </div>
          </div>
          <div class="mp-prog"><div class="mp-prog-fill" id="mpProgFill" style="width:0%"></div></div>
        </div>
      </div>
      <div id="musicGrid" class="file-grid"></div>
      <div id="musicEmpty" class="empty-state">
        <div class="es-illo"><i data-lucide="music" style="width:56px;height:56px;color:var(--muted);stroke-width:1;"></i></div>
        <div class="es-title">No music yet</div>
        <div class="es-sub">Upload your songs, albums, and audio files and listen from anywhere.</div>
        <div class="es-actions">
          <button class="es-btn primary" onclick="triggerUpload()"><i data-lucide="upload" class="lucide"></i> Upload Music</button>
        </div>
      </div>
    </div>

    <!-- DOCUMENTS PAGE -->
    <div class="page" id="page-docs">
      <div class="page-hdr">
        <div>
          <h1 class="page-title">Documents</h1>
          <div class="breadcrumb" id="docsBreadcrumb"><span>Documents</span></div>
        </div>
        <div class="page-actions">
          <button class="t-btn" onclick="toggleView('docsGrid')"><i data-lucide="layout-grid" class="lucide"></i></button>
          <button class="bulk-btn" onclick="newFolder('page-docs')"><i data-lucide="folder-plus" class="lucide"></i> New Folder</button>
          <button class="upg-btn" style="width:auto;padding:8px 16px;" onclick="triggerUpload()"><i data-lucide="upload" class="lucide"></i> Upload</button>
        </div>
      </div>
      <div id="docsGrid" class="file-grid"></div>
      <div id="docsEmpty" class="empty-state">
        <div class="es-illo"><i data-lucide="file-text" style="width:56px;height:56px;color:var(--muted);stroke-width:1;"></i></div>
        <div class="es-title">No documents yet</div>
        <div class="es-sub">Upload your PDFs, Word files, spreadsheets, and any other documents to access them from anywhere.</div>
        <div class="es-actions">
          <button class="es-btn primary" onclick="triggerUpload()"><i data-lucide="upload" class="lucide"></i> Upload Documents</button>
          <button class="es-btn" onclick="newFolder('page-docs')"><i data-lucide="folder-plus" class="lucide"></i> Create Folder</button>
        </div>
      </div>
    </div>



    <!-- AI SMART SEARCH PAGE -->
    <div class="page" id="page-ai">
      <div style="max-width:640px;margin:0 auto;text-align:center;padding-top:20px;">
        <div style="margin-bottom:16px;"><i data-lucide="sparkles" style="width:52px;height:52px;color:var(--accent);stroke-width:1.2;"></i></div>
        <h1 style="font-family:'Clash Display',sans-serif;font-size:1.6rem;font-weight:700;margin-bottom:8px;">AI Smart Search</h1>
        <p style="color:var(--muted);font-size:.9rem;margin-bottom:28px;">Search by file name, content inside documents, or describe what you're looking for in plain language.</p>
        <div style="position:relative;margin-bottom:20px;">
          <input type="text" id="aiQuery" placeholder="Describe what you're looking for…" style="width:100%;background:var(--surface2);border:1px solid var(--border);border-radius:12px;padding:14px 50px 14px 18px;color:var(--text);font-family:'Epilogue',sans-serif;font-size:.9rem;outline:none;transition:border-color .2s;" onfocus="this.style.borderColor='rgba(0,245,160,.4)'" onblur="this.style.borderColor=''" onkeydown="if(event.key==='Enter')aiSearch()"/>
          <button onclick="aiSearch()" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:var(--accent);border:none;border-radius:8px;padding:7px 14px;color:#04060a;font-weight:700;cursor:pointer;font-family:'Clash Display',sans-serif;">Search</button>
        </div>
        <div id="aiResults" style="text-align:left;"></div>
      </div>
    </div>

    <!-- DUPLICATE FINDER PAGE -->
    <div class="page" id="page-dupes">
      <div class="page-hdr">
        <div><h1 class="page-title">Duplicate Finder</h1></div>
        <div class="page-actions">
          <button class="upg-btn" style="width:auto;padding:8px 16px;" id="scanBtn" onclick="scanDupes()"><i data-lucide="scan-search" class="lucide"></i> Start Scan</button>
        </div>
      </div>
      <div id="dupesResult">
        <div class="empty-state">
          <div class="es-illo"><i data-lucide="copy" style="width:56px;height:56px;color:var(--muted);stroke-width:1;"></i></div>
          <div class="es-title">Find duplicate files and free up space</div>
          <div class="es-sub">Run a scan to find identical or near-identical files across your storage.</div>
          <div class="es-actions">
            <button class="es-btn primary" onclick="scanDupes()"><i data-lucide="scan-search" class="lucide"></i> Start Scan</button>
          </div>
        </div>
      </div>
    </div>

    <!-- STORAGE ANALYZER PAGE -->
    <div class="page" id="page-storage">
      <div class="page-hdr">
        <div><h1 class="page-title">Storage Analyzer</h1><p class="page-sub" id="storageSubtitle">0 B used of 5 GB</p></div>
        <div class="page-actions">
          <button class="upg-btn" style="width:auto;padding:8px 16px;"><i data-lucide="zap" class="lucide"></i> Upgrade Plan</button>
        </div>
      </div>
      <div id="storageContent"></div>
    </div>

    <!-- RECYCLE BIN PAGE -->
    <div class="page" id="page-bin">
      <div class="page-hdr">
        <div><h1 class="page-title">Recycle Bin</h1><p class="page-sub">Files are permanently deleted after 30 days</p></div>
        <div class="page-actions">
          <button class="bulk-btn" onclick="emptyBin()" style="color:var(--red);border-color:var(--red);" id="emptyBinBtn"><i data-lucide="trash-2" class="lucide"></i> Empty Bin</button>
        </div>
      </div>
      <div id="binContent">
        <div class="empty-state">
          <div class="es-illo"><i data-lucide="trash-2" style="width:56px;height:56px;color:var(--muted);stroke-width:1;"></i></div>
          <div class="es-title">Recycle Bin is empty</div>
          <div class="es-sub">Files you delete are kept here for 30 days before being permanently removed.</div>
        </div>
      </div>
    </div>

    <!-- PUBLIC PROFILE PAGE -->
    <div class="page" id="page-profile">
      <div style="max-width:560px;margin:0 auto;">
        <div style="text-align:center;padding:28px 0 20px;">
          <div style="position:relative;width:80px;margin:0 auto 14px;">
            <div style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,var(--accent2),var(--accent3));display:flex;align-items:center;justify-content:center;font-family:'Clash Display',sans-serif;font-weight:700;font-size:28px;overflow:hidden;" id="profileAvatar">KV</div>
            <label for="avatarInput" style="position:absolute;bottom:0;right:0;width:24px;height:24px;border-radius:50%;background:var(--accent);display:flex;align-items:center;justify-content:center;cursor:pointer;border:2px solid var(--bg);" title="Change photo">
              <i data-lucide="camera" style="width:12px;height:12px;color:#04060a;"></i>
            </label>
            <input type="file" id="avatarInput" accept="image/*" style="position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);border:0;" onchange="previewAvatar(this)"/>
          </div>
          <h1 style="font-family:'Clash Display',sans-serif;font-size:1.3rem;font-weight:700;" id="profileName">{{ session('user_name', session('supabase_email','')) }}</h1>
          <p style="color:var(--muted);font-size:.83rem;margin-top:4px;">Keeption Vault Member</p>
        </div>
        <div style="background:var(--surface);border:1px solid var(--border);border-radius:16px;padding:20px;margin-bottom:16px;">
          <h2 style="font-family:'Clash Display',sans-serif;font-size:.9rem;margin-bottom:14px;">Profile Settings</h2>
          <label style="display:block;font-size:.78rem;color:var(--muted);margin-bottom:5px;">Display Name</label>
          <input type="text" id="profileDisplayName" placeholder="Your name" value="{{ session('user_name','') }}" style="width:100%;background:var(--surface2);border:1px solid var(--border);border-radius:9px;padding:9px 13px;color:var(--text);font-family:'Epilogue',sans-serif;font-size:.85rem;outline:none;margin-bottom:12px;"/>
          <label style="display:block;font-size:.78rem;color:var(--muted);margin-bottom:5px;">Bio</label>
          <textarea id="profileBio" placeholder="Tell people a bit about yourself…" rows="3" style="width:100%;background:var(--surface2);border:1px solid var(--border);border-radius:9px;padding:9px 13px;color:var(--text);font-family:'Epilogue',sans-serif;font-size:.85rem;outline:none;resize:none;margin-bottom:14px;"></textarea>
          <div style="display:flex;align-items:center;gap:10px;">
            <button class="upg-btn" style="width:auto;padding:9px 22px;" id="saveProfileBtn" onclick="saveProfile()">Save Changes</button>
            <span id="profileSaveStatus" style="font-size:.78rem;display:none;"></span>
          </div>
        </div>
        <div style="background:var(--surface);border:1px solid var(--border);border-radius:16px;padding:20px;">
          <div class="empty-state" style="min-height:140px;padding:16px;">
            <div class="es-illo"><i data-lucide="layout-grid" style="width:40px;height:40px;color:var(--muted);stroke-width:1;"></i></div>
            <div class="es-title">Your public profile is empty</div>
            <div class="es-sub">Make files public from your dashboard to showcase your work here.</div>
            <div class="es-actions">
              <button class="es-btn primary" onclick="navTo('page-files')">Go to My Files</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- SETTINGS PAGE -->
    <div class="page" id="page-settings">
      <div class="page-hdr"><div><h1 class="page-title">Settings</h1></div></div>

      <div class="settings-section">
        <h3>Appearance</h3>
        <div class="setting-row">
          <div>
            <div class="setting-label">Dark Mode</div>
            <div class="setting-sub">Use dark theme across the app</div>
          </div>
          <button class="toggle" id="toggleDark" onclick="toggleSetting('toggleDark','darkMode')"></button>
        </div>
        <div class="setting-row">
          <div><div class="setting-label">Language</div></div>
          <select class="setting-select" id="settingLanguage" onchange="onSelectChange('language',this)">
            <option>English</option>
            <option>Spanish</option>
            <option>French</option>
            <option>Portuguese</option>
          </select>
        </div>
      </div>

      <div class="settings-section">
        <h3>Notifications</h3>
        <div class="setting-row">
          <div>
            <div class="setting-label">Upload complete</div>
            <div class="setting-sub">Notify me when a file finishes uploading</div>
          </div>
          <button class="toggle" id="toggleNotifUpload" onclick="toggleSetting('toggleNotifUpload','notifUpload')"></button>
        </div>
        <div class="setting-row">
          <div>
            <div class="setting-label">Shared link viewed</div>
            <div class="setting-sub">Notify me when someone opens my shared link</div>
          </div>
          <button class="toggle" id="toggleNotifLink" onclick="toggleSetting('toggleNotifLink','notifLink')"></button>
        </div>
        <div class="setting-row">
          <div>
            <div class="setting-label">File shared with me</div>
            <div class="setting-sub">Notify me when someone shares a file with me</div>
          </div>
          <button class="toggle" id="toggleNotifShare" onclick="toggleSetting('toggleNotifShare','notifShare')"></button>
        </div>
        <div class="setting-row">
          <div>
            <div class="setting-label">Storage warnings</div>
            <div class="setting-sub">Warn me when storage is nearly full</div>
          </div>
          <button class="toggle" id="toggleNotifStorage" onclick="toggleSetting('toggleNotifStorage','notifStorage')"></button>
        </div>
      </div>

      <div class="settings-section">
        <h3>Storage</h3>
        <div class="setting-row">
          <div>
            <div class="setting-label">Storage warning threshold</div>
            <div class="setting-sub">Show a warning when I reach this usage level</div>
          </div>
          <select class="setting-select" id="storageThreshold" onchange="onSelectChange('storageThreshold',this)">
            <option>70%</option>
            <option>80%</option>
            <option>90%</option>
            <option>95%</option>
          </select>
        </div>
        <div class="setting-row">
          <div>
            <div class="setting-label">Auto camera backup</div>
            <div class="setting-sub">Automatically upload photos from your phone</div>
          </div>
          <button class="toggle" id="toggleCameraBackup" onclick="toggleSetting('toggleCameraBackup','cameraBackup')"></button>
        </div>
      </div>

      <div class="settings-section">
        <h3>Sharing</h3>
        <div class="setting-row">
          <div>
            <div class="setting-label">Shared video quality</div>
            <div class="setting-sub">Quality used when streaming shared videos</div>
          </div>
          <select class="setting-select" id="settingVideoQuality" onchange="onSelectChange('videoQuality',this)">
            <option>Original quality</option>
            <option>Compressed (saves data)</option>
          </select>
        </div>
      </div>

      <div class="settings-section">
        <h3>Account</h3>
        <div class="setting-row">
          <div>
            <div class="setting-label">Sign out</div>
            <div class="setting-sub">Sign out of your Keeption Vault account</div>
          </div>
          <form method="POST" action="/logout" style="margin:0;">
            @csrf
            <button type="submit" class="es-btn" style="color:var(--red);border-color:var(--red);padding:7px 16px;font-size:.78rem;"><i data-lucide="log-out" class="lucide"></i> Sign Out</button>
          </form>
        </div>
        <div class="setting-row">
          <div>
            <div class="setting-label">Reset all settings</div>
            <div class="setting-sub">Restore all settings to their defaults</div>
          </div>
          <button class="es-btn" style="padding:7px 16px;font-size:.78rem;" onclick="resetSettings()">Reset</button>
        </div>
      </div>

    </div>

    <!-- AUDIT TRAIL PAGE -->
    <div class="page" id="page-audit">
      <div class="page-hdr">
        <div><h1 class="page-title">Audit Trail</h1><p class="page-sub">Complete tamper-proof record of every team action</p></div>
        <div class="page-actions">
          <button class="upg-btn" style="width:auto;padding:8px 16px;" onclick="exportAuditCSV()"><i data-lucide="download" class="lucide"></i> Export CSV</button>
        </div>
      </div>
      <!-- Filter bar -->
      <div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:20px;align-items:center;">
        <select id="auditFilterMember" onchange="renderAuditTrail()" style="background:var(--surface2);border:1px solid var(--border);border-radius:8px;padding:7px 12px;color:var(--text);font-family:'Epilogue',sans-serif;font-size:.8rem;outline:none;">
          <option value="">All Members</option>
        </select>
        <select id="auditFilterAction" onchange="renderAuditTrail()" style="background:var(--surface2);border:1px solid var(--border);border-radius:8px;padding:7px 12px;color:var(--text);font-family:'Epilogue',sans-serif;font-size:.8rem;outline:none;">
          <option value="">All Actions</option>
          <option value="upload">Upload</option>
          <option value="download">Download</option>
          <option value="delete">Delete</option>
          <option value="rename">Rename</option>
          <option value="share">Share</option>
          <option value="login">Login</option>
        </select>
        <input type="text" id="auditSearch" placeholder="Search file or action..." oninput="renderAuditTrail()" style="background:var(--surface2);border:1px solid var(--border);border-radius:8px;padding:7px 12px;color:var(--text);font-family:'Epilogue',sans-serif;font-size:.8rem;outline:none;flex:1;min-width:160px;"/>
      </div>
      <div style="background:var(--surface);border:1px solid var(--border);border-radius:14px;overflow:hidden;">
        <div class="audit-grid-row" style="padding:10px 16px;border-bottom:1px solid var(--border);font-size:.7rem;color:var(--muted);font-weight:600;text-transform:uppercase;letter-spacing:.05em;">
          <span class="audit-col-ico"></span><span class="audit-col-details">Action</span><span class="audit-col-member">Member</span><span class="audit-col-date">Date &amp; Time</span><span class="audit-col-device">Device</span>
        </div>
        <div id="auditList">
          <div class="empty-state" style="min-height:200px;">
            <div class="es-illo"><i data-lucide="scroll-text" style="width:48px;height:48px;color:var(--muted);stroke-width:1;"></i></div>
            <div class="es-title">No activity yet</div>
            <div class="es-sub">Team actions will appear here as members use the workspace.</div>
          </div>
        </div>
      </div>
    </div>

    <!-- ADMIN DASHBOARD PAGE -->
    <div class="page" id="page-admin">
      <div class="page-hdr"><div><h1 class="page-title">Admin Dashboard</h1><p class="page-sub">Complete control over your team workspace</p></div></div>

      <!-- Panel 1: Team Overview -->
      <div style="background:var(--surface);border:1px solid rgba(0,212,255,.15);border-radius:14px;padding:20px;margin-bottom:20px;">
        <div style="font-family:'Clash Display',sans-serif;font-size:.95rem;font-weight:600;margin-bottom:16px;display:flex;align-items:center;gap:8px;color:var(--accent2);"><i data-lucide="layout-dashboard" style="width:16px;height:16px;"></i> Team Overview</div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(120px,1fr));gap:14px;margin-bottom:16px;">
          <div style="background:var(--surface2);border-radius:10px;padding:14px;text-align:center;"><div style="font-family:'Clash Display',sans-serif;font-size:1.4rem;font-weight:700;" id="adminStatMembers">1</div><div style="font-size:.72rem;color:var(--muted);margin-top:3px;">Members</div></div>
          <div style="background:var(--surface2);border-radius:10px;padding:14px;text-align:center;"><div style="font-family:'Clash Display',sans-serif;font-size:1.4rem;font-weight:700;" id="adminStatFiles">0</div><div style="font-size:.72rem;color:var(--muted);margin-top:3px;">Team Files</div></div>
          <div style="background:var(--surface2);border-radius:10px;padding:14px;text-align:center;"><div style="font-family:'Clash Display',sans-serif;font-size:1.4rem;font-weight:700;" id="adminStatLinks">0</div><div style="font-size:.72rem;color:var(--muted);margin-top:3px;">Active Links</div></div>
          <div style="background:var(--surface2);border-radius:10px;padding:14px;text-align:center;"><div style="font-family:'Clash Display',sans-serif;font-size:1.4rem;font-weight:700;color:var(--accent);" id="adminStatStorage">0 B</div><div style="font-size:.72rem;color:var(--muted);margin-top:3px;">of 500 GB used</div></div>
        </div>
        <div style="height:6px;background:var(--surface2);border-radius:99px;overflow:hidden;margin-bottom:16px;"><div id="adminOverviewFill" style="height:100%;background:linear-gradient(90deg,var(--accent),var(--accent2));border-radius:99px;width:0%;transition:width .5s;"></div></div>
        <div style="display:flex;gap:8px;flex-wrap:wrap;">
          <button class="upg-btn" style="width:auto;padding:8px 14px;font-size:.78rem;" onclick="openInviteModal()"><i data-lucide="user-plus" class="lucide"></i> Invite Member</button>
          <button class="bulk-btn" style="font-size:.78rem;" onclick="exportAuditCSV()"><i data-lucide="download" class="lucide"></i> Export Audit Log</button>
          <button class="bulk-btn" style="font-size:.78rem;" onclick="navTo('page-audit')"><i data-lucide="scroll-text" class="lucide"></i> View Audit Trail</button>
        </div>
      </div>

      <div class="admin-grid-split">
        <!-- Panel 2: Member Management -->
        <div style="background:var(--surface);border:1px solid rgba(0,212,255,.15);border-radius:14px;padding:20px;">
          <div style="font-family:'Clash Display',sans-serif;font-size:.95rem;font-weight:600;margin-bottom:14px;display:flex;align-items:center;gap:8px;color:var(--accent2);"><i data-lucide="users" style="width:16px;height:16px;"></i> Member Management</div>
          <div id="adminMembersList"><div style="color:var(--muted);font-size:.82rem;">No members yet.</div></div>
          <div style="margin-top:14px;border-top:1px solid var(--border);padding-top:14px;">
            <div style="font-size:.78rem;color:var(--muted);margin-bottom:8px;">Invite new member</div>
            <div style="display:flex;gap:8px;flex-wrap:wrap;">
              <input type="email" id="inviteEmail" placeholder="Email address..." style="flex:1;min-width:140px;background:var(--surface2);border:1px solid var(--border);border-radius:8px;padding:8px 12px;color:var(--text);font-family:'Epilogue',sans-serif;font-size:.82rem;outline:none;"/>
              <select id="inviteRole" style="background:var(--surface2);border:1px solid var(--border);border-radius:8px;padding:8px 10px;color:var(--text);font-family:'Epilogue',sans-serif;font-size:.82rem;outline:none;">
                <option value="editor">Editor</option>
                <option value="viewer">Viewer</option>
                <option value="admin">Admin</option>
              </select>
              <button class="upg-btn" style="width:auto;padding:8px 14px;" onclick="inviteMember()">Send</button>
            </div>
          </div>
        </div>

        <!-- Panel 3: Storage Management -->
        <div style="background:var(--surface);border:1px solid rgba(0,212,255,.15);border-radius:14px;padding:20px;">
          <div style="font-family:'Clash Display',sans-serif;font-size:.95rem;font-weight:600;margin-bottom:14px;display:flex;align-items:center;gap:8px;color:var(--accent2);"><i data-lucide="pie-chart" style="width:16px;height:16px;"></i> Storage Management</div>
          <div style="font-size:.82rem;color:var(--muted);margin-bottom:10px;">Team pool: <span style="color:var(--text);" id="adminStorUsed">0 B</span> of 500 GB used</div>
          <div style="height:8px;background:var(--surface2);border-radius:99px;overflow:hidden;margin-bottom:16px;"><div id="adminStorFill" style="height:100%;background:linear-gradient(90deg,var(--accent),var(--accent2));border-radius:99px;width:0%;transition:width .5s;"></div></div>
          <div id="adminMemberBars"><div style="color:var(--muted);font-size:.82rem;">No usage data yet.</div></div>
          <div style="margin-top:12px;font-size:.75rem;color:var(--muted);background:var(--surface2);border-radius:8px;padding:10px 12px;"><i data-lucide="trending-up" style="width:12px;height:12px;margin-right:4px;"></i> At current rate, 500 GB will be reached in ~24 months</div>
        </div>

        <!-- Panel 4: Security Center -->
        <div style="background:var(--surface);border:1px solid rgba(0,212,255,.15);border-radius:14px;padding:20px;">
          <div style="font-family:'Clash Display',sans-serif;font-size:.95rem;font-weight:600;margin-bottom:14px;display:flex;align-items:center;gap:8px;color:var(--accent2);"><i data-lucide="shield" style="width:16px;height:16px;"></i> Security Center</div>
          <div style="font-size:.78rem;color:var(--muted);margin-bottom:10px;font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Active Sessions</div>
          <div id="adminSessions"><div style="font-size:.82rem;color:var(--muted);">No active sessions detected.</div></div>
          <div style="margin-top:14px;font-size:.78rem;color:var(--muted);margin-bottom:8px;font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Security Settings</div>
          <div style="display:flex;flex-direction:column;gap:8px;">
            <div style="display:flex;justify-content:space-between;align-items:center;font-size:.82rem;"><span>Require 2FA for all members</span><button class="toggle" id="toggle2FA" onclick="toggleSetting('toggle2FA','require2FA')"></button></div>
            <div style="display:flex;justify-content:space-between;align-items:center;font-size:.82rem;"><span>Allow external file sharing</span><button class="toggle on" id="toggleExtShare" onclick="toggleSetting('toggleExtShare','extShare')"></button></div>
          </div>
        </div>

        <!-- Panel 5: Billing -->
        <div style="background:var(--surface);border:1px solid rgba(0,212,255,.15);border-radius:14px;padding:20px;">
          <div style="font-family:'Clash Display',sans-serif;font-size:.95rem;font-weight:600;margin-bottom:14px;display:flex;align-items:center;gap:8px;color:var(--accent2);"><i data-lucide="credit-card" style="width:16px;height:16px;"></i> Billing &amp; Subscription</div>
          <div style="font-size:.82rem;color:var(--muted);line-height:2.2;">
            Plan: <span style="color:var(--text);font-weight:600;">Teams — $8/mo per seat</span><br/>
            Seats: <span style="color:var(--text);" id="adminSeats">1</span> active<br/>
            Monthly total: <span style="color:var(--accent);font-weight:600;">$8.00</span><br/>
            Next billing: <span style="color:var(--text);">May 8, 2026</span><br/>
            Payment: <span style="color:var(--text);">•••• 4242</span>
          </div>
          <div style="margin-top:14px;display:flex;gap:8px;flex-wrap:wrap;">
            <button class="upg-btn" style="width:auto;padding:7px 14px;font-size:.78rem;" onclick="showToast('Invoice #KV-94827 downloaded as PDF', 'success')"><i data-lucide="download" class="lucide"></i> Download Invoice</button>
            <button class="bulk-btn" style="font-size:.78rem;" onclick="openStripeCheckoutModal('Purchasing additional team member seats for your team vault at $8.00 / month.')"><i data-lucide="plus" class="lucide"></i> Add Seat</button>
          </div>
        </div>

        <!-- Panel 6: Team Settings -->
        <div style="background:var(--surface);border:1px solid rgba(0,212,255,.15);border-radius:14px;padding:20px;grid-column:1/-1;">
          <div style="font-family:'Clash Display',sans-serif;font-size:.95rem;font-weight:600;margin-bottom:14px;display:flex;align-items:center;gap:8px;color:var(--accent2);"><i data-lucide="settings-2" style="width:16px;height:16px;"></i> Team Settings</div>
          <div class="admin-settings-grid">
            <div>
              <div style="font-size:.78rem;color:var(--muted);margin-bottom:8px;font-weight:600;text-transform:uppercase;letter-spacing:.05em;">General</div>
              <div class="form-group" style="margin-bottom:10px;"><label style="font-size:.75rem;color:var(--muted);display:block;margin-bottom:4px;">Team Name</label><input type="text" value="My Team" style="width:100%;background:var(--surface2);border:1px solid var(--border);border-radius:8px;padding:8px 12px;color:var(--text);font-family:'Epilogue',sans-serif;font-size:.82rem;outline:none;"/></div>
              <div class="form-group"><label style="font-size:.75rem;color:var(--muted);display:block;margin-bottom:4px;">Default Role for New Members</label><select style="width:100%;background:var(--surface2);border:1px solid var(--border);border-radius:8px;padding:8px 12px;color:var(--text);font-family:'Epilogue',sans-serif;font-size:.82rem;outline:none;"><option>Editor</option><option>Viewer</option></select></div>
            </div>
            <div>
              <div style="font-size:.78rem;color:var(--muted);margin-bottom:8px;font-weight:600;text-transform:uppercase;letter-spacing:.05em;">File Settings</div>
              <div style="display:flex;flex-direction:column;gap:10px;">
                <div style="display:flex;justify-content:space-between;align-items:center;font-size:.82rem;"><span>Auto virus scanning</span><button class="toggle on" id="toggleVirusScan"></button></div>
                <div style="display:flex;justify-content:space-between;align-items:center;font-size:.82rem;"><span>Auto duplicate detection</span><button class="toggle on" id="toggleDupeScan"></button></div>
                <div style="display:flex;justify-content:space-between;align-items:center;font-size:.82rem;"><span>Files visible to all on upload</span><button class="toggle" id="toggleAutoShare"></button></div>
              </div>
            </div>
            <div>
              <div style="font-size:.78rem;color:var(--muted);margin-bottom:8px;font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Notifications</div>
              <div style="display:flex;flex-direction:column;gap:10px;">
                <div style="display:flex;justify-content:space-between;align-items:center;font-size:.82rem;"><span>Weekly activity digest</span><button class="toggle on" id="toggleWeeklyDigest"></button></div>
                <div style="display:flex;justify-content:space-between;align-items:center;font-size:.82rem;"><span>Anomaly detection alerts</span><button class="toggle on" id="toggleAnomalyAlerts"></button></div>
              </div>
            </div>
          </div>
          <div style="margin-top:14px;"><button class="upg-btn" style="width:auto;padding:8px 18px;" onclick="showToast('Team settings saved','success')">Save Settings</button></div>
        </div>

        <!-- Shared Links Panel -->
        <div style="background:var(--surface);border:1px solid rgba(0,212,255,.15);border-radius:14px;padding:20px;">
          <div style="font-family:'Clash Display',sans-serif;font-size:.95rem;font-weight:600;margin-bottom:14px;display:flex;align-items:center;gap:8px;color:var(--accent2);"><i data-lucide="link" style="width:16px;height:16px;"></i> All Shared Links</div>
          <div id="adminLinksList"><div style="color:var(--muted);font-size:.82rem;">No shared links yet.</div></div>
        </div>
      </div>
    </div>

    <!-- TEAM MEMBERS PAGE -->
    <div class="page" id="page-team-members">
      <div class="page-hdr">
        <div><h1 class="page-title">Team Members</h1><p class="page-sub" id="teamMemberCount">Loading...</p></div>
        <div class="page-actions">
          <input type="text" id="memberSearch" placeholder="Search by name or email..." oninput="renderTeamMembers()" style="background:var(--surface2);border:1px solid var(--border);border-radius:8px;padding:7px 12px;color:var(--text);font-family:'Epilogue',sans-serif;font-size:.8rem;outline:none;width:200px;"/>
          <select id="memberFilterRole" onchange="renderTeamMembers()" style="background:var(--surface2);border:1px solid var(--border);border-radius:8px;padding:7px 12px;color:var(--text);font-family:'Epilogue',sans-serif;font-size:.8rem;outline:none;">
            <option value="">All Roles</option>
            <option value="admin">Admin</option>
            <option value="editor">Editor</option>
            <option value="viewer">Viewer</option>
          </select>
          <button class="upg-btn" style="width:auto;padding:8px 14px;" onclick="openInviteModal()"><i data-lucide="user-plus" class="lucide"></i> Invite Member</button>
        </div>
      </div>
      <div id="teamMembersGrid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:14px;">
        <div class="empty-state" style="min-height:200px;grid-column:1/-1;">
          <div class="es-illo"><i data-lucide="users" style="width:48px;height:48px;color:var(--muted);stroke-width:1;"></i></div>
          <div class="es-title">No team members yet</div>
          <div class="es-sub">Invite your first team member to get started.</div>
          <div class="es-actions"><button class="es-btn primary" onclick="openInviteModal()"><i data-lucide="user-plus" class="lucide"></i> Invite Member</button></div>
        </div>
      </div>
    </div>

    <!-- TEAM FILES PAGE -->
    <div class="page" id="page-team-files">
      <div class="page-hdr">
        <div><h1 class="page-title">Team Files</h1><p class="page-sub">Shared workspace — visible to all team members</p></div>
        <div class="page-actions">
          <div id="teamPresence" style="display:flex;gap:-6px;align-items:center;"></div>
          <button class="t-btn" onclick="toggleView('teamFilesGrid')"><i data-lucide="layout-grid" class="lucide"></i></button>
          <button class="bulk-btn" onclick="newFolder('page-team-files')"><i data-lucide="folder-plus" class="lucide"></i> New Folder</button>
          <button class="upg-btn" style="width:auto;padding:8px 16px;" onclick="triggerUpload()"><i data-lucide="upload" class="lucide"></i> Upload</button>
        </div>
      </div>
      <!-- Team banner -->
      <div style="background:linear-gradient(135deg,rgba(0,212,255,.08),rgba(0,245,160,.06));border:1px solid rgba(0,212,255,.2);border-radius:14px;padding:16px 20px;margin-bottom:20px;display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
        <div style="width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,var(--accent2),var(--accent));display:flex;align-items:center;justify-content:center;font-family:'Clash Display',sans-serif;font-weight:700;font-size:1.1rem;color:#04060a;flex-shrink:0;">T</div>
        <div style="flex:1;min-width:120px;">
          <div style="font-family:'Clash Display',sans-serif;font-weight:700;font-size:.95rem;">My Team</div>
          <div style="font-size:.75rem;color:var(--muted);margin-top:2px;" id="teamBannerSub">1 member · 0 B of 500 GB used</div>
        </div>
        <div style="height:6px;width:180px;background:var(--surface2);border-radius:99px;overflow:hidden;flex-shrink:0;"><div id="teamBannerFill" style="height:100%;background:linear-gradient(90deg,var(--accent2),var(--accent));border-radius:99px;width:0%;transition:width .5s;"></div></div>
      </div>
      <div class="breadcrumb" id="teamFilesBreadcrumb" style="margin-bottom:14px;"><span>Team Files</span></div>
      <div id="teamFilesGrid" class="file-grid"></div>
      <div id="teamFilesEmpty" class="empty-state">
        <div class="es-illo"><i data-lucide="folder-open" style="width:56px;height:56px;color:var(--muted);stroke-width:1;"></i></div>
        <div class="es-title">No team files yet</div>
        <div class="es-sub">Upload files here to share them with your entire team. Everyone with Editor or Admin access can view and download.</div>
        <div class="es-actions">
          <button class="es-btn primary" onclick="triggerUpload()"><i data-lucide="upload" class="lucide"></i> Upload Files</button>
          <button class="es-btn" onclick="newFolder('page-team-files')"><i data-lucide="folder-plus" class="lucide"></i> Create Folder</button>
        </div>
      </div>
    </div>
  </main><!-- /main -->

  <!-- DETAILS PANEL -->
  <aside class="details" id="detailsPanel">
    <div class="det-hdr">
      <span class="det-title" id="detTitle">File Details</span>
      <button class="det-close" onclick="closeDetails()">✕</button>
    </div>
    <div class="det-preview">
      <div class="det-prev-box" id="detPreview">📄</div>
    </div>
    <div class="det-meta">
      <div class="meta-row"><span class="meta-k">Name</span><span class="meta-v" id="detName">—</span></div>
      <div class="meta-row"><span class="meta-k">Type</span><span class="meta-v" id="detType">—</span></div>
      <div class="meta-row"><span class="meta-k">Size</span><span class="meta-v" id="detSize">—</span></div>
      <div class="meta-row"><span class="meta-k">Modified</span><span class="meta-v" id="detDate">—</span></div>
      <div class="meta-row"><span class="meta-k">Location</span><span class="meta-v" id="detLoc">My Files</span></div>
    </div>
    <div class="det-actions">
      <button class="act-btn" onclick="detAction('download')"><i data-lucide="download" class="lucide"></i> Download</button>
      <button class="act-btn" onclick="detAction('share')"><i data-lucide="share-2" class="lucide"></i> Share</button>
      <button class="act-btn" onclick="detAction('rename')"><i data-lucide="pencil" class="lucide"></i> Rename</button>
      <button class="act-btn" onclick="detAction('move')"><i data-lucide="folder-input" class="lucide"></i> Move</button>
      <button class="act-btn danger" onclick="detAction('delete')"><i data-lucide="trash-2" class="lucide"></i> Delete</button>
      <button class="act-btn" onclick="detAction('info')"><i data-lucide="info" class="lucide"></i> Info</button>
      <button class="act-btn full" onclick="detAction('open')">Open File</button>
    </div>
  </aside>

</div><!-- /app-body -->

<!-- MOBILE BOTTOM NAV -->
<nav class="mob-nav" id="mobNav">
  <div class="mob-nav-inner">
    <button class="mob-nav-btn active" data-page="page-home" onclick="navTo('page-home')"><span><i data-lucide="home" class="lucide lucide-xl"></i></span><span>Home</span></button>
    <button class="mob-nav-btn" data-page="page-files" onclick="navTo('page-files')"><span><i data-lucide="folder" class="lucide lucide-xl"></i></span><span>Files</span></button>
    <div class="mob-upload-wrap"><button class="mob-upload-btn" onclick="triggerUpload()"><i data-lucide="upload" class="lucide lucide-xl"></i></button></div>

    <button class="mob-nav-btn" onclick="toggleMobMore()"><span><i data-lucide="grid" class="lucide lucide-xl"></i></span><span>More</span></button>
  </div>
</nav>

<!-- MOBILE MORE MENU -->
<div class="mob-more-bg" id="mobMoreBg" onclick="closeMobMore()"></div>
<div class="mob-more-sheet" id="mobMoreSheet">
  <div class="mob-more-handle"></div>
  <div class="mob-more-grid">
    <button onclick="navTo('page-photos');closeMobMore()"><span><i data-lucide="image" class="lucide lucide-xl" style="color:#00d4ff"></i></span><span>Photos</span></button>
    <button onclick="navTo('page-videos');closeMobMore()"><span><i data-lucide="video" class="lucide lucide-xl" style="color:#ff6b9d"></i></span><span>Videos</span></button>
    <button onclick="navTo('page-music');closeMobMore()"><span><i data-lucide="music" class="lucide lucide-xl" style="color:#a78bfa"></i></span><span>Music</span></button>
    <button onclick="navTo('page-docs');closeMobMore()"><span><i data-lucide="file-text" class="lucide lucide-xl" style="color:#fb923c"></i></span><span>Documents</span></button>

    <button onclick="navTo('page-ai');closeMobMore()"><span><i data-lucide="sparkles" class="lucide lucide-xl" style="color:#ffd166"></i></span><span>AI Search</span></button>
    <button onclick="navTo('page-dupes');closeMobMore()"><span><i data-lucide="copy" class="lucide lucide-xl" style="color:#a78bfa"></i></span><span>Duplicates</span></button>
    <button onclick="navTo('page-storage');closeMobMore()"><span><i data-lucide="pie-chart" class="lucide lucide-xl" style="color:#00d4ff"></i></span><span>Storage</span></button>
    <button onclick="navTo('page-bin');closeMobMore()"><span><i data-lucide="trash-2" class="lucide lucide-xl" style="color:#ff4444"></i></span><span>Recycle Bin</span></button>
    <button onclick="navTo('page-profile');closeMobMore()"><span><i data-lucide="user" class="lucide lucide-xl" style="color:#00f5a0"></i></span><span>Profile</span></button>
    <button onclick="navTo('page-settings');closeMobMore()"><span><i data-lucide="settings" class="lucide lucide-xl"></i></span><span>Settings</span></button>
  </div>
</div>

<!-- TOAST STACK -->
<div class="toast-stack" id="toastStack"></div>

<!-- CAMERA BACKUP MODAL -->
<div class="modal-bg" id="modalCamera" onclick="closeModal('modalCamera')">
  <div class="modal" onclick="event.stopPropagation()">
    <button class="modal-close" onclick="closeModal('modalCamera')"><i data-lucide="x" class="lucide"></i></button>
    <h2><i data-lucide="camera" class="lucide lucide-lg" style="color:#00d4ff;margin-right:6px;"></i> Camera Backup</h2>
    <p>Scan this QR code with your phone to set up automatic camera backup on the Keeption Vault mobile app.</p>
    <div class="qr-box"><i data-lucide="smartphone" style="width:64px;height:64px;color:var(--muted);stroke-width:1;"></i></div>
    <p style="text-align:center;font-size:.78rem;color:var(--muted);">Or open <strong style="color:var(--accent);">keeption.com/app</strong> on your phone</p>
    <div class="modal-actions" style="justify-content:center;margin-top:14px;">
      <button class="modal-btn primary" onclick="closeModal('modalCamera');completeOnboardStep(1);showToast('Camera backup enabled','success')">Done</button>
    </div>
  </div>
</div>

<!-- INVITE MODAL -->
<div class="modal-bg" id="modalInvite" onclick="closeModal('modalInvite')">
  <div class="modal" onclick="event.stopPropagation()">
    <button class="modal-close" onclick="closeModal('modalInvite')"><i data-lucide="x" class="lucide"></i></button>
    <h2><i data-lucide="gift" class="lucide lucide-lg" style="color:#ffd166;margin-right:6px;"></i> Invite a Friend</h2>
    <p>Share your referral link. When your friend signs up, you both get +5 GB of free storage.</p>
    <div style="display:flex;gap:8px;margin-bottom:16px;">
      <input class="modal-input" style="margin-bottom:0;flex:1;" value="https://keeption.com/invite/{{ session('supabase_user_id','your-code') }}" readonly id="inviteLink"/>
      <button class="modal-btn primary" onclick="copyInviteLink()" style="white-space:nowrap;">Copy</button>
    </div>
    <div class="modal-actions">
      <button class="modal-btn" onclick="shareVia('whatsapp')">WhatsApp</button>
      <button class="modal-btn" onclick="shareVia('email')">Email</button>
      <button class="modal-btn" onclick="shareVia('sms')">SMS</button>
    </div>
  </div>
</div>

<!-- CONTEXT MENU -->
<div id="ctxMenu" class="ctx-menu" style="display:none;"></div>

<!-- STRIPE SIMULATED CHECKOUT MODAL -->
<div id="stripeCheckoutModal" style="display:none;position:fixed;inset:0;z-index:2000;background:rgba(4,6,10,0.95);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);align-items:center;justify-content:center;padding:20px;" onclick="if(event.target===this)closeStripeCheckoutModal()">
  <div style="background:#0c0f17;border:1px solid rgba(0,245,160,0.25);border-radius:24px;padding:32px;max-width:480px;width:100%;position:relative;margin:auto;box-shadow:0 12px 40px rgba(0,245,160,0.06);">
    <button onclick="closeStripeCheckoutModal()" style="position:absolute;top:16px;right:16px;background:none;border:none;color:#6b7280;font-size:22px;cursor:pointer;line-height:1;transition:color .2s;" onmouseover="this.style.color='var(--red)'" onmouseout="this.style.color='#6b7280'">✕</button>

    <div id="stFormContent">
      <div style="display:flex;align-items:center;gap:12px;margin-bottom:18px;">
        <div style="width:40px;height:40px;border-radius:10px;background:rgba(0,245,160,0.08);border:1px solid rgba(0,245,160,0.25);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
          <i data-lucide="credit-card" style="width:20px;height:20px;color:#00f5a0;"></i>
        </div>
        <div>
          <h2 style="font-family:'Clash Display',sans-serif;font-size:1.2rem;font-weight:700;margin-bottom:2px;background:linear-gradient(90deg,#00f5a0,#00d4ff);-webkit-background-clip:text;-webkit-text-fill-color:transparent;">Stripe Checkout</h2>
          <p style="color:var(--muted);font-size:.78rem;margin:0;">Secure payment by Stripe</p>
        </div>
      </div>

      <p id="stripeCheckoutText" style="color:var(--text);font-size:.82rem;line-height:1.6;margin-bottom:18px;background:rgba(255,255,255,0.02);padding:10px 12px;border:1px solid var(--border);border-radius:8px;"></p>

      <!-- Glassmorphic Credit Card Widget -->
      <div style="background:linear-gradient(135deg,rgba(255,255,255,0.06),rgba(255,255,255,0.01));border:1px solid rgba(255,255,255,0.1);backdrop-filter:blur(8px);border-radius:16px;padding:20px;margin-bottom:22px;position:relative;overflow:hidden;box-shadow:inset 0 1px 1px rgba(255,255,255,0.15);">
        <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:24px;">
          <div style="font-family:'Clash Display',sans-serif;font-weight:700;font-size:1rem;color:var(--text);letter-spacing:1px;">KEEPTION <span style="font-size:.7rem;font-weight:400;color:var(--muted)">VAULT</span></div>
          <div style="font-size:22px;color:rgba(255,255,255,0.8);font-family:'Clash Display',sans-serif;font-weight:700;letter-spacing:-1px;">stripe</div>
        </div>
        <div style="width:38px;height:28px;background:#f59e0b;border-radius:6px;margin-bottom:18px;opacity:0.85;box-shadow:0 2px 4px rgba(0,0,0,0.3)"></div>
        <div id="ccDisplayNumber" style="font-family:'Courier New',Courier,monospace;font-size:1.2rem;letter-spacing:2px;color:#eef0f6;text-shadow:0 1px 2px rgba(0,0,0,0.5);margin-bottom:16px;">•••• •••• •••• ••••</div>
        <div style="display:flex;justify-content:space-between;align-items:center;">
          <div style="min-width:0;flex:1;padding-right:10px;">
            <div style="font-size:.55rem;color:var(--muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:2px;">Cardholder</div>
            <div id="ccDisplayName" style="font-size:.75rem;color:#eef0f6;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-weight:500;">YOUR NAME</div>
          </div>
          <div style="text-align:right;">
            <div style="font-size:.55rem;color:var(--muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:2px;">Expires</div>
            <div id="ccDisplayExp" style="font-size:.75rem;color:#eef0f6;font-weight:500;">MM/YY</div>
          </div>
        </div>
      </div>

      <!-- Card inputs -->
      <div style="display:flex;flex-direction:column;gap:14px;margin-bottom:22px;">
        <div class="form-group" style="margin:0;">
          <label style="font-size:.75rem;color:var(--muted);display:block;margin-bottom:5px;">Card Number</label>
          <input type="text" id="stCardNo" maxlength="19" placeholder="4242 4242 4242 4242" oninput="formatCardNumber(this); document.getElementById('ccDisplayNumber').textContent = this.value || '•••• •••• •••• ••••';" style="width:100%;background:var(--surface2);border:1px solid var(--border);border-radius:10px;padding:11px 14px;color:var(--text);font-family:'Courier New',Courier,monospace;font-size:.9rem;outline:none;transition:border-color .2s;" onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border)'"/>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
          <div class="form-group" style="margin:0;">
            <label style="font-size:.75rem;color:var(--muted);display:block;margin-bottom:5px;">Expiration Date</label>
            <input type="text" id="stCardExp" maxlength="7" placeholder="MM / YY" oninput="formatCardExpiry(this); document.getElementById('ccDisplayExp').textContent = this.value || 'MM/YY';" style="width:100%;background:var(--surface2);border:1px solid var(--border);border-radius:10px;padding:11px 14px;color:var(--text);font-size:.85rem;outline:none;transition:border-color .2s;" onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border)'"/>
          </div>
          <div class="form-group" style="margin:0;">
            <label style="font-size:.75rem;color:var(--muted);display:block;margin-bottom:5px;">CVC</label>
            <input type="password" id="stCardCvc" maxlength="4" placeholder="123" style="width:100%;background:var(--surface2);border:1px solid var(--border);border-radius:10px;padding:11px 14px;color:var(--text);font-size:.85rem;outline:none;transition:border-color .2s;" onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border)'"/>
          </div>
        </div>
        <div class="form-group" style="margin:0;">
          <label style="font-size:.75rem;color:var(--muted);display:block;margin-bottom:5px;">Cardholder Name</label>
          <input type="text" id="stCardName" placeholder="Full name on card" oninput="document.getElementById('ccDisplayName').textContent = this.value.toUpperCase() || 'YOUR NAME';" style="width:100%;background:var(--surface2);border:1px solid var(--border);border-radius:10px;padding:11px 14px;color:var(--text);font-size:.85rem;outline:none;transition:border-color .2s;" onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border)'"/>
        </div>
      </div>

      <!-- Billing Summary -->
      <div id="stripeBillingDetails" style="background:rgba(255,255,255,0.01);border:1px dashed var(--border);border-radius:12px;padding:14px 18px;margin-bottom:24px;"></div>

      <button onclick="processStripePayment()" style="width:100%;padding:14px;background:#00f5a0;border:none;border-radius:12px;color:#04060a;font-family:'Clash Display',sans-serif;font-weight:700;font-size:1rem;cursor:pointer;box-shadow:0 4px 14px rgba(0,245,160,0.2);display:flex;align-items:center;justify-content:center;gap:8px;">
        <i data-lucide="lock" style="width:16px;height:16px;"></i> Pay Safely Now
      </button>
    </div>

    <!-- Processing / Loading state -->
    <div id="stProgress" style="display:none;text-align:center;padding:40px 0;">
      <div style="width:50px;height:50px;border:3px solid rgba(0,245,160,0.1);border-top-color:#00f5a0;border-radius:50%;animation:stSpin .8s linear infinite;margin:0 auto 20px;"></div>
      <h3 style="font-family:'Clash Display',sans-serif;font-size:1.1rem;font-weight:600;margin-bottom:8px;">Authorizing payment...</h3>
      <p style="color:var(--muted);font-size:.8rem;">Contacting Stripe processing servers</p>
    </div>

    <!-- Success state -->
    <div id="stSuccess" style="display:none;text-align:center;padding:40px 0;">
      <div style="width:60px;height:60px;border-radius:50%;background:rgba(0,245,160,0.1);border:2px solid #00f5a0;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
        <i data-lucide="check" style="width:30px;height:30px;color:#00f5a0;stroke-width:3;"></i>
      </div>
      <h3 style="font-family:'Clash Display',sans-serif;font-size:1.2rem;font-weight:700;color:#00f5a0;margin-bottom:8px;">Payment Successful</h3>
      <p style="color:var(--muted);font-size:.8rem;">Workspace details updated instantly</p>
    </div>
  </div>
</div>

<style>
@keyframes stSpin { to { transform: rotate(360deg); } }
</style>

<!-- MOVE MODAL -->
<div class="modal-bg" id="modalMove" onclick="closeModal('modalMove')">
  <div class="modal" onclick="event.stopPropagation()">
    <button class="modal-close" onclick="closeModal('modalMove')"><i data-lucide="x" class="lucide"></i></button>
    <h2><i data-lucide="folder-input" class="lucide lucide-lg" style="color:#ffd166;margin-right:6px;"></i> Move to Folder</h2>
    <p>Choose a destination folder.</p>
    <div class="folder-pick-list" id="folderPickList"></div>
    <div class="modal-actions">
      <button class="modal-btn" onclick="closeModal('modalMove')">Cancel</button>
      <button class="modal-btn primary" onclick="confirmMove()">Move Here</button>
    </div>
  </div>
</div>

<!-- SELF-DESTRUCT CREATE MODAL -->

<!-- NEW FOLDER MODAL -->
<div class="modal-bg" id="modalNewFolder" onclick="closeModal('modalNewFolder')">
  <div class="modal" onclick="event.stopPropagation()" style="max-width:380px;">
    <button class="modal-close" onclick="closeModal('modalNewFolder')"><i data-lucide="x" class="lucide"></i></button>
    <div style="display:flex;align-items:center;gap:12px;margin-bottom:6px;">
      <div style="width:40px;height:40px;border-radius:10px;background:rgba(255,209,102,0.12);border:1px solid rgba(255,209,102,0.25);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <i data-lucide="folder-plus" style="width:20px;height:20px;color:#ffd166;"></i>
      </div>
      <div>
        <h2 style="font-size:1rem;margin-bottom:2px;">New Folder</h2>
        <p style="color:var(--muted);font-size:.78rem;margin:0;">Give your folder a name</p>
      </div>
    </div>
    <input class="modal-input" id="newFolderName" placeholder="Folder name…" maxlength="80"
      onkeydown="if(event.key==='Enter')confirmNewFolder()"
      style="margin-top:16px;margin-bottom:14px;font-size:.95rem;"/>
    <div class="modal-actions">
      <button class="modal-btn" onclick="closeModal('modalNewFolder')">Cancel</button>
      <button class="modal-btn primary" onclick="confirmNewFolder()"><i data-lucide="folder-plus" class="lucide"></i> Create</button>
    </div>
  </div>
</div>

<!-- HIDDEN FILE INPUT -->
<input type="file" id="fileInput" multiple style="position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);border:0;" onchange="handleFileSelect(this)"/>

<script>
window.showToast = function(arg1, arg2, arg3) {
  let icon = '', msg = '', type = 'info';
  if (arg3) { icon = arg1; msg = arg2; type = arg3; }
  else if (arg2) { if (['success', 'error', 'info', 'warn'].includes(arg2)) { msg = arg1; type = arg2; } else { icon = arg1; msg = arg2; } }
  else { msg = arg1; }
  const stack = document.getElementById('toastStack');
  if (!stack) return;
  const el = document.createElement('div'); el.className = 'toast-item ' + type;
  el.innerHTML = (icon ? `<span style="font-size:1.1rem">${icon}</span>` : '') + `<span>${msg}</span>`;
  stack.appendChild(el);
  setTimeout(() => { el.style.opacity = 0; setTimeout(() => el.remove(), 300); }, 4000);
};
// ─── PLAN CONFIG ────────────────────────────────────────────────────────────
const USER_PLAN = '{{ session("plan", "free") }}';
const PLAN_CONFIG = {
  free:  { storageTotal: 5   * 1024**3, maxFileSize: 500  * 1024**2, maxLinks: 5,         versionDays: 7,   selfDestruct: false, aiSearch: false, cameraBackup: false, teams: false, storageTotalLabel: '5 GB'   },
  pro:   { storageTotal: 100 * 1024**3, maxFileSize: 10   * 1024**3, maxLinks: Infinity,  versionDays: 90,  selfDestruct: true,  aiSearch: true,  cameraBackup: true,  teams: false, storageTotalLabel: '100 GB' },
  teams: { storageTotal: 500 * 1024**3, maxFileSize: 50   * 1024**3, maxLinks: Infinity,  versionDays: 180, selfDestruct: true,  aiSearch: true,  cameraBackup: true,  teams: true,  storageTotalLabel: '500 GB' },
};
const PLAN = PLAN_CONFIG[USER_PLAN] || PLAN_CONFIG.free;

// ─── STATE (user's real data only — starts empty) ──────────────────────────
const vault = {
  files: [],        // { id, name, type, size, date, ico, loc, category, folderId }
  folders: JSON.parse(localStorage.getItem('kv_folders') || '[]'),      // { id, name, parentId, page, date }  parentId=null = root
  deletedFiles: [],
  sharedLinks: [],
  destructLinks: [],
  sharedWithMe: [],
  onboardDone: JSON.parse(localStorage.getItem('kv_onboard') || '[false,false,false,false]'),
  storageUsed: 0,
  storageTotal: PLAN.storageTotal,
};

// folder navigation state per page
const folderNav = {
  'page-files':  { stack: [] },  // stack of { id, name }
  'page-photos': { stack: [] },
  'page-videos': { stack: [] },
  'page-music':  { stack: [] },
  'page-docs':   { stack: [] },
};

let selectedFiles = new Set();
let currentPage = 'page-home';
let sidebarCollapsed = false;
let detailsOpen = false;
let musicPlaying = false;
let musicProgress = 0;
let musicTimer = null;
let currentFile = null;

// ─── NAVIGATION ────────────────────────────────────────────────────────────
function navTo(pageId) {
  document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
  const target = document.getElementById(pageId);
  if (target) target.classList.add('active');
  document.querySelectorAll('.nav-item').forEach(n => n.classList.toggle('active', n.dataset.page === pageId));
  document.querySelectorAll('.mob-nav-btn').forEach(n => n.classList.toggle('active', n.dataset.page === pageId));
  currentPage = pageId;
  clearSelection();
  closeDetails();
  if (PAGE_GRID[pageId]) renderPage(pageId);
  if (pageId === 'page-storage') renderStorageAnalyzer();
  if (pageId === 'page-my-links') renderLinks();
  if (pageId === 'page-destruct') renderDestructLinks();
  if (pageId === 'page-bin') renderBin();

  if (typeof USER_PLAN !== 'undefined' && USER_PLAN === 'teams') {
    if (pageId === 'page-team-members' || pageId === 'page-admin' || pageId === 'page-team-files') loadTeamData();
    if (pageId === 'page-audit') loadAuditLogs();
  }
}

// ─── ONBOARDING ────────────────────────────────────────────────────────────
function dismissOnboarding() {
  localStorage.setItem('kv_onboard_dismissed', '1');
  const bar = document.getElementById('onboardBar');
  if (bar) bar.style.display = 'none';
}

function updateOnboarding() {
  if (localStorage.getItem('kv_onboard_dismissed')) {
    const bar = document.getElementById('onboardBar');
    if (bar) bar.style.display = 'none';
    return;
  }
  const done = vault.onboardDone;
  const count = done.filter(Boolean).length;
  const pct = (count / 4) * 100;
  const obFill = document.getElementById('obFill');
  if (obFill) obFill.style.width = pct + '%';
  const obPct = document.getElementById('obPct');
  if (obPct) obPct.textContent = count + ' of 4 complete';
  done.forEach((d, i) => {
    const step = document.getElementById('ob-step-' + i);
    if (step) step.classList.toggle('done', d);
  });
  if (count === 4) {
    setTimeout(() => dismissOnboarding(), 1200);
  }
  localStorage.setItem('kv_onboard', JSON.stringify(done));
}

function completeOnboardStep(index) {
  if (!vault.onboardDone[index]) {
    vault.onboardDone[index] = true;
    updateOnboarding();
  }
}

// ─── STORAGE DISPLAY ───────────────────────────────────────────────────────
function formatBytes(b) {
  if (b === 0) return '0 B';
  const k = 1024, sizes = ['B','KB','MB','GB'];
  const i = Math.floor(Math.log(b) / Math.log(k));
  return parseFloat((b / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
}

function updateStorageDisplay() {
  const used = vault.storageUsed;
  const total = vault.storageTotal;
  const pct = Math.min((used / total) * 100, 100);
  const usedStr = formatBytes(used);
  const totalStr = PLAN.storageTotalLabel;

  const safe = (id, val) => { const el = document.getElementById(id); if (el) el.textContent = val; };
  const safeW = (id, val) => { const el = document.getElementById(id); if (el) el.style.width = val; };

  safe('storageLabel', usedStr + ' / ' + totalStr);
  safeW('spFill', pct + '%');
  safe('sbStorLabel', usedStr + ' / ' + totalStr);
  safeW('sbStorFill', pct + '%');
  safe('storageSubtitle', usedStr + ' used of ' + totalStr);
  // re-render storage analyzer if on that page
  if (currentPage === 'page-storage') renderStorageAnalyzer();
}

// ─── HOME PAGE ─────────────────────────────────────────────────────────────
function renderHome() {
  const recent = vault.files.slice(-6).reverse();
  const hasFiles = recent.length > 0;
  document.getElementById('homeRecentSection').style.display = hasFiles ? 'block' : 'none';
  document.getElementById('homeEmptyState').style.display = hasFiles ? 'none' : 'block';
  if (hasFiles) renderFileCards('homeRecentGrid', recent);
  const email = '{{ session("supabase_email","") }}';
  if (email) {
    const name = email.split('@')[0];
    document.getElementById('homeGreeting').textContent = 'Welcome back, ' + name;
  }
}

// ─── FOLDER HELPERS ────────────────────────────────────────────────────────
function currentFolderId(page) {
  const nav = folderNav[page];
  if (!nav || !nav.stack.length) return null;
  return nav.stack[nav.stack.length - 1].id;
}

function openFolder(folderId, folderName, page) {
  if (!folderNav[page]) return;
  folderNav[page].stack.push({ id: folderId, name: folderName });
  renderPage(page);
}

function breadcrumbNav(page, depth) {
  // depth = index in stack to navigate to (-1 = root)
  if (!folderNav[page]) return;
  if (depth < 0) {
    folderNav[page].stack = [];
  } else {
    folderNav[page].stack = folderNav[page].stack.slice(0, depth + 1);
  }
  renderPage(page);
}

function renderBreadcrumb(page) {
  const nav = folderNav[page];
  const bcId = {
    'page-files':      'filesBreadcrumb',
    'page-photos':     'photosBreadcrumb',
    'page-videos':     'videosBreadcrumb',
    'page-music':      'musicBreadcrumb',
    'page-docs':       'docsBreadcrumb',
    'page-team-files': 'teamFilesBreadcrumb',
  }[page];
  const el = document.getElementById(bcId);
  if (!el) return;
  const rootLabel = { 
    'page-files':      'My Files',
    'page-photos':     'Photos',
    'page-videos':     'Videos',
    'page-music':      'Music',
    'page-docs':       'Documents',
    'page-team-files': 'Team Files',
  }[page] || 'Home';
  
  let html = '';
  if (nav && nav.stack.length > 0) {
    html += `<span onclick="breadcrumbNav('${page}',${nav.stack.length - 2})" style="margin-right:12px;display:inline-flex;align-items:center;gap:4px;color:var(--accent);font-weight:600;font-size:0.78rem;cursor:pointer;"><i data-lucide="arrow-left" style="width:14px;height:14px;stroke-width:2.5;"></i> Back</span> <span style="color:var(--surface3);margin-right:10px;pointer-events:none;">|</span> `;
  }
  html += `<span onclick="breadcrumbNav('${page}',-1)">${rootLabel}</span>`;
  
  if (nav) {
    nav.stack.forEach((f, i) => {
      html += ` <span style="color:var(--muted);pointer-events:none;">›</span> <span onclick="breadcrumbNav('${page}',${i})">${escHtml(f.name)}</span>`;
    });
  }
  el.innerHTML = html;
  if (window.lucide) lucide.createIcons();
}

// ─── PAGE RENDERER (files + folders for a given page) ──────────────────────
const PAGE_CATEGORY = {
  'page-files':  null,   // all non-categorised + folders
  'page-photos': 'photo',
  'page-videos': 'video',
  'page-music':  'music',
  'page-docs':   'doc',
};

const PAGE_GRID = {
  'page-files':  'filesGrid',
  'page-photos': 'photosGrid',
  'page-videos': 'videosGrid',
  'page-music':  'musicGrid',
  'page-docs':   'docsGrid',
};

const PAGE_EMPTY = {
  'page-files':  'filesEmpty',
  'page-photos': 'photosEmpty',
  'page-videos': 'videosEmpty',
  'page-music':  'musicEmpty',
  'page-docs':   'docsEmpty',
};

// Smart render — only re-renders the current page + the category page of the affected file
function renderCurrentAndCategory(affectedCategory) {
  renderPage(currentPage);
  if (affectedCategory) {
    const catPage = { photo:'page-photos', video:'page-videos', music:'page-music', doc:'page-docs', other:'page-files' };
    const target = catPage[affectedCategory];
    if (target && target !== currentPage) renderPage(target);
  }
  // Always keep files page in sync
  if (currentPage !== 'page-files') renderPage('page-files');
}

function renderPage(page) {
  const gridId  = PAGE_GRID[page];
  const emptyId = PAGE_EMPTY[page];
  const cat     = PAGE_CATEGORY[page];
  const folderId = currentFolderId(page);

  renderBreadcrumb(page);

  const grid  = document.getElementById(gridId);
  const empty = document.getElementById(emptyId);
  if (!grid) return;

  // folders that belong to this page and current folder level
  const folders = vault.folders.filter(f => f.page === page && (f.parentId || null) === folderId);

  // files in current folder (and matching category)
  const files = vault.files.filter(f => {
    const inFolder = (f.folderId || null) === folderId;
    if (!inFolder) return false;

    // Distinguish between team and personal files
    if (page === 'page-team-files') {
      if (!f.is_team) return false;
    } else {
      if (f.is_team) return false;
    }

    if (cat === null) return true;          // shows all
    return f.category === cat;
  });

  const hasContent = folders.length > 0 || files.length > 0;
  grid.style.display  = hasContent ? (page === 'page-photos' ? 'grid' : 'grid') : 'none';
  if (empty) empty.style.display = hasContent ? 'none' : 'flex';
  if (!hasContent) return;

  // photos page uses tile layout
  if (page === 'page-photos') {
    grid.className = 'photo-grid';
    const folderTiles = folders.map(fo => {
      const n = escHtml(fo.name);
      return '<div class="photo-tile" style="background:var(--surface2);" ondblclick="openFolder(' + fo.id + ',\'' + n + '\',\'' + page + '\')" title="' + n + '">'
        + '<div class="photo-tile-ico">📁</div>'
        + '<div class="photo-tile-name">' + n + '</div>'
        + '</div>';
    });
    const fileTiles = files.map(f => {
      const n = escHtml(f.name);
      // Use thumbnail URL if available, otherwise fall back to objectUrl or downloadUrl with loading="lazy"
      const thumbSrc = f.thumbUrl || f.objectUrl || f.downloadUrl;
      const img = thumbSrc
        ? '<img src="' + thumbSrc + '" alt="' + n + '" loading="lazy" style="width:100%;height:100%;object-fit:cover;"/>'
        : '<div class="photo-tile-ico"><i data-lucide="' + f.ico + '" style="width:36px;height:36px;color:var(--accent2);stroke-width:1.2;"></i></div>';
      return '<div class="photo-tile" data-fid="' + f.id + '" onclick="openDetails(' + f.id + ')" title="' + n + '">'
        + img
        + '<div class="photo-tile-name">' + escHtml(shortName(f.name)) + '</div>'
        + '</div>';
    });
    grid.innerHTML = folderTiles.join('') + fileTiles.join('');
    return;
  }

  // all other pages use file-grid cards — preserve list-view state across re-renders
  const wasListView = grid.classList.contains('list-view');
  grid.className = 'file-grid' + (wasListView ? ' list-view' : '');

  // Remove any previous list header sibling
  const prevHdr = grid.previousElementSibling;
  if (prevHdr && prevHdr.classList.contains('file-list-row-hdr')) prevHdr.remove();

  // Type badge helper
  const catBadge = (cat) => {
    const map = { photo:'ftb-photo',video:'ftb-video',music:'ftb-music',doc:'ftb-doc',other:'ftb-other' };
    const label = { photo:'Photo',video:'Video',music:'Music',doc:'Doc',other:'File' };
    return '<span class="fc-type-badge '+(map[cat]||'ftb-other')+'">'+(label[cat]||'File')+'</span>';
  };

  const folderCards = folders.map(fo => {
    const n = escHtml(fo.name);
    if (wasListView) {
      return '<div class="file-card folder-card" ondblclick="openFolder(' + fo.id + ',\'' + n + '\',\'' + page + '\')" onclick="selectFolderCard(' + fo.id + ',event,\'' + page + '\')" oncontextmenu="showCtxMenu(event,null,\'' + page + '\',' + fo.id + ')">' + '<div class="fc-check"><i data-lucide="check" style="width:10px;height:10px;"></i></div>' + '<span class="fc-ico-lv"><i data-lucide="folder" style="width:20px;height:20px;color:#ffd166;stroke-width:1.4;"></i></span>' + '<div class="fc-name">' + n + '</div>' + '<div class="fc-lv-type"><span class="fc-type-badge ftb-folder">Folder</span></div>' + '<div class="fc-lv-size">—</div>' + '<div class="fc-lv-date">' + fo.date + '</div>' + '</div>';
    }
    return '<div class="file-card folder-card" ondblclick="openFolder(' + fo.id + ',\'' + n + '\',\'' + page + '\')" onclick="selectFolderCard(' + fo.id + ',event,\'' + page + '\')" oncontextmenu="showCtxMenu(event,null,\'' + page + '\',' + fo.id + ')">' + '<div class="fc-check"><i data-lucide="check" style="width:10px;height:10px;"></i></div>' + '<span class="fc-ico"><i data-lucide="folder" style="width:32px;height:32px;color:#ffd166;stroke-width:1.4;"></i></span>' + '<div class="fc-name">' + n + '</div>' + '<div class="fc-meta">' + fo.date + '</div>' + '</div>';
  });
  const fileCards = files.map(f => {
    const n = escHtml(f.name);
    const displayName = escHtml(shortName(f.name));
    const dblAct = f.category === 'music' ? 'loadTrack(' + f.id + ')' : 'openDetails(' + f.id + ')';
    const icoColors = { photo:'#00d4ff', video:'#ff6b9d', music:'#a78bfa', doc:'#fb923c', other:'#636b7d' };
    const icoColor = icoColors[f.category] || '#636b7d';
    if (wasListView) {
      return '<div class="file-card" id="fc-' + f.id + '" onclick="selectFile(' + f.id + ',event)" ondblclick="' + dblAct + '" oncontextmenu="showCtxMenu(event,' + f.id + ',\'' + page + '\',null)" title="' + n + '">' + '<div class="fc-check"><i data-lucide="check" style="width:10px;height:10px;"></i></div>' + '<span class="fc-ico-lv"><i data-lucide="' + f.ico + '" style="width:20px;height:20px;color:' + icoColor + ';stroke-width:1.4;"></i></span>' + '<div class="fc-name">' + n + '</div>' + '<div class="fc-lv-type">' + catBadge(f.category) + '</div>' + '<div class="fc-lv-size">' + formatBytes(f.size) + '</div>' + '<div class="fc-lv-date">' + f.date + '</div>' + '</div>';
    }
    // Thumbnail logic:
    // - thumbUrl (canvas data-URL from drag-drop) → always use img
    // - objectUrl (blob URL) → use img for photos, img for videos (thumbUrl already captured)
    // - server-loaded photo with downloadUrl → img (inline-served)
    // - server-loaded video with only downloadUrl → video element (browser renders first frame)
    let thumb;
    if (f.thumbUrl || (f.objectUrl && f.category !== 'video')) {
      // High-quality canvas thumbnail or photo blob — use img
      const src = f.thumbUrl || f.objectUrl;
      thumb = '<div class="fc-thumb-wrap" style="width:100%;aspect-ratio:1/1;overflow:hidden;border-radius:8px;margin-bottom:8px;position:relative;"><img src="' + src + '" style="width:100%;height:100%;object-fit:cover;" loading="lazy" alt="' + n + '"/></div><span class="fc-ico" style="display:none;"><i data-lucide="' + f.ico + '" style="width:22px;height:22px;color:' + icoColor + ';stroke-width:1.4;"></i></span>';
    } else if (f.category === 'video' && (f.objectUrl || f.downloadUrl)) {
      // Video: use a muted preload=metadata video element so the browser shows first frame
      const vsrc = f.objectUrl || f.downloadUrl;
      thumb = '<div class="fc-thumb-wrap" style="width:100%;aspect-ratio:1/1;overflow:hidden;border-radius:8px;margin-bottom:8px;position:relative;background:#000;"><video src="' + vsrc + '" muted preload="metadata" playsinline style="width:100%;height:100%;object-fit:cover;pointer-events:none;"></video><div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;pointer-events:none;"><i data-lucide="play-circle" style="width:28px;height:28px;color:rgba(255,255,255,0.85);filter:drop-shadow(0 1px 4px rgba(0,0,0,.7));"></i></div></div><span class="fc-ico" style="display:none;"><i data-lucide="' + f.ico + '" style="width:22px;height:22px;color:' + icoColor + ';stroke-width:1.4;"></i></span>';
    } else if (f.category === 'photo' && f.downloadUrl) {
      // Server-loaded photo with no blob: use downloadUrl directly
      thumb = '<div class="fc-thumb-wrap" style="width:100%;aspect-ratio:1/1;overflow:hidden;border-radius:8px;margin-bottom:8px;"><img src="' + f.downloadUrl + '" style="width:100%;height:100%;object-fit:cover;" loading="lazy" alt="' + n + '"/></div><span class="fc-ico" style="display:none;"><i data-lucide="' + f.ico + '" style="width:22px;height:22px;color:' + icoColor + ';stroke-width:1.4;"></i></span>';
    } else {
      // Fallback: show the lucide icon inside a 1:1 tile
      thumb = '<div class="fc-thumb-wrap" style="width:100%;aspect-ratio:1/1;overflow:hidden;border-radius:8px;margin-bottom:8px;background:rgba(255,255,255,0.03);display:flex;align-items:center;justify-content:center;border:1px dashed rgba(255,255,255,0.05);"><i data-lucide="' + f.ico + '" style="width:48px;height:48px;color:' + icoColor + ';stroke-width:1.2;"></i></div><span class="fc-ico" style="display:none;"><i data-lucide="' + f.ico + '" style="width:24px;height:24px;color:' + icoColor + ';stroke-width:1.4;"></i></span>';
    }
    const playHint = f.category === 'music' ? ' · <span style="color:var(--accent);font-size:.68rem;">Play</span>' : '';
    return '<div class="file-card" id="fc-' + f.id + '" onclick="selectFile(' + f.id + ',event)" ondblclick="' + dblAct + '" oncontextmenu="showCtxMenu(event,' + f.id + ',\'' + page + '\',null)" title="' + n + '">'
      + '<div class="fc-check"><i data-lucide="check" style="width:10px;height:10px;"></i></div>'
      + thumb
      + '<div class="fc-name">' + displayName + '</div>'
      + '<div class="fc-meta">' + formatBytes(f.size) + ' · ' + f.date + playHint + '</div>'
      + '</div>';
  });
  grid.innerHTML = folderCards.join('') + fileCards.join('');
  lucide.createIcons();
}

// ─── FILE CARDS (generic, used by home recent + AI search + bin) ───────────
function renderFileCards(containerId, files) {
  const el = typeof containerId === 'string' ? document.getElementById(containerId) : containerId;
  if (!el) return;
  if (!files.length) return;
  const icoColors = { photo:'#00d4ff', video:'#ff6b9d', music:'#a78bfa', doc:'#fb923c', other:'#636b7d' };
  el.innerHTML = files.map(function(f) {
    const n = escHtml(f.name);
    const icoColor = icoColors[f.category] || '#636b7d';
    // Build thumbnail preview for photos and videos
    let preview;
    if (f.thumbUrl || (f.objectUrl && f.category !== 'video')) {
      const src = f.thumbUrl || f.objectUrl;
      preview = '<div class="fc-thumb-wrap" style="width:100%;aspect-ratio:1/1;overflow:hidden;border-radius:8px;margin-bottom:8px;"><img src="' + src + '" style="width:100%;height:100%;object-fit:cover;" loading="lazy" alt="' + n + '"/></div><span class="fc-ico" style="display:none;"><i data-lucide="' + f.ico + '" style="width:22px;height:22px;color:' + icoColor + ';stroke-width:1.4;"></i></span>';
    } else if (f.category === 'video' && (f.objectUrl || f.downloadUrl)) {
      const vsrc = f.objectUrl || f.downloadUrl;
      preview = '<div class="fc-thumb-wrap" style="width:100%;aspect-ratio:1/1;overflow:hidden;border-radius:8px;margin-bottom:8px;position:relative;background:#000;"><video src="' + vsrc + '" muted preload="metadata" playsinline style="width:100%;height:100%;object-fit:cover;pointer-events:none;"></video><div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;pointer-events:none;"><i data-lucide="play-circle" style="width:28px;height:28px;color:rgba(255,255,255,0.85);filter:drop-shadow(0 1px 4px rgba(0,0,0,.7));"></i></div></div><span class="fc-ico" style="display:none;"><i data-lucide="' + f.ico + '" style="width:22px;height:22px;color:' + icoColor + ';stroke-width:1.4;"></i></span>';
    } else if (f.category === 'photo' && f.downloadUrl) {
      preview = '<div class="fc-thumb-wrap" style="width:100%;aspect-ratio:1/1;overflow:hidden;border-radius:8px;margin-bottom:8px;"><img src="' + f.downloadUrl + '" style="width:100%;height:100%;object-fit:cover;" loading="lazy" alt="' + n + '"/></div><span class="fc-ico" style="display:none;"><i data-lucide="' + f.ico + '" style="width:22px;height:22px;color:' + icoColor + ';stroke-width:1.4;"></i></span>';
    } else {
      preview = '<div class="fc-thumb-wrap" style="width:100%;aspect-ratio:1/1;overflow:hidden;border-radius:8px;margin-bottom:8px;background:rgba(255,255,255,0.03);display:flex;align-items:center;justify-content:center;border:1px dashed rgba(255,255,255,0.05);"><i data-lucide="' + f.ico + '" style="width:48px;height:48px;color:' + icoColor + ';stroke-width:1.2;"></i></div><span class="fc-ico" style="display:none;"><i data-lucide="' + f.ico + '" style="width:24px;height:24px;color:' + icoColor + ';stroke-width:1.4;"></i></span>';
    }
    return '<div class="file-card" id="fc-' + f.id + '" onclick="selectFile(' + f.id + ',event)" ondblclick="openDetails(' + f.id + ')">'
      + '<div class="fc-check"><i data-lucide="check" style="width:10px;height:10px;"></i></div>'
      + preview
      + '<div class="fc-name">' + n + '</div>'
      + '<div class="fc-meta">' + formatBytes(f.size) + ' · ' + f.date + '</div>'
      + '</div>';
  }).join('');
  lucide.createIcons();
}

function selectFolderCard(id, e, page) {
  // single click selects, double click (handled by ondblclick) opens
  if (e && (e.ctrlKey || e.metaKey || e.shiftKey)) {
    // multi-select folders not supported yet, just ignore
    return;
  }
  // do nothing on single click — dblclick opens
}

function escHtml(s) {
  return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// ─── FILE SELECTION ─────────────────────────────────────────────────────────
function selectFile(id, e) {
  if (e && (e.ctrlKey || e.metaKey || e.shiftKey)) { toggleSelect(id); return; }
  clearSelection();
  openDetails(id);
}

function toggleSelect(id) {
  const card = document.getElementById('fc-' + id);
  if (!card) return;
  if (selectedFiles.has(id)) { selectedFiles.delete(id); card.classList.remove('selected'); }
  else { selectedFiles.add(id); card.classList.add('selected'); }
  updateBulkBar();
}

function clearSelection() {
  selectedFiles.forEach(id => { const c = document.getElementById('fc-' + id); if (c) c.classList.remove('selected'); });
  selectedFiles.clear();
  updateBulkBar();
}

function updateBulkBar() {
  const bar = document.getElementById('bulkBar');
  document.getElementById('bulkCount').textContent = selectedFiles.size + ' selected';
  bar.classList.toggle('active', selectedFiles.size > 0);
}

function selectAll() {
  document.querySelectorAll('.file-card').forEach(c => {
    const id = parseInt(c.id.replace('fc-', ''));
    if (!isNaN(id)) { selectedFiles.add(id); c.classList.add('selected'); }
  });
  updateBulkBar();
}

// ─── DETAILS PANEL ──────────────────────────────────────────────────────────
function openDetails(id) {
  const f = vault.files.find(x => x.id === id);
  if (!f) return;
  currentFile = f;
  document.getElementById('detTitle').textContent = f.name;
  document.getElementById('detName').textContent = f.name;
  document.getElementById('detType').textContent = f.type || f.category;
  document.getElementById('detSize').textContent = formatBytes(f.size);
  document.getElementById('detDate').textContent = f.date;
  document.getElementById('detLoc').textContent = f.loc || 'My Files';

  // Real preview — create blob URL on demand for video/music (not during upload)
  const prev = document.getElementById('detPreview');
  if (f.category === 'photo' && (f.objectUrl || f.thumbUrl || f.downloadUrl)) {
    prev.innerHTML = `<img src="${f.objectUrl || f.thumbUrl || f.downloadUrl}" alt="${escHtml(f.name)}" style="width:100%;height:100%;object-fit:cover;border-radius:12px;"/>`;
  } else if (f.category === 'video') {
    // Create blob URL now on demand
    if (!f.objectUrl && f.fileRef) f.objectUrl = URL.createObjectURL(f.fileRef);
    const videoUrl = f.objectUrl || f.downloadUrl;
    if (videoUrl) {
      prev.innerHTML = `<video src="${videoUrl}" controls style="width:100%;height:100%;min-height:180px;object-fit:contain;border-radius:12px;background:#000;"></video>`;
    } else {
      prev.innerHTML = `<i data-lucide="${f.ico}" style="width:64px;height:64px;color:var(--muted);stroke-width:1.5;"></i>`;
    }
  } else if (f.category === 'music') {
    if (!f.objectUrl && f.fileRef) f.objectUrl = URL.createObjectURL(f.fileRef);
    prev.innerHTML = `<i data-lucide="${f.ico}" style="width:64px;height:64px;color:var(--accent);stroke-width:1.5;"></i>`;
  } else {
    prev.innerHTML = `<i data-lucide="${f.ico}" style="width:64px;height:64px;color:var(--muted);stroke-width:1.5;"></i>`;
  }

  lucide.createIcons();
  document.getElementById('detailsPanel').classList.add('open');
  detailsOpen = true;
}

function closeDetails() {
  document.getElementById('detailsPanel').classList.remove('open');
  detailsOpen = false;
  currentFile = null;
}

function detAction(action) {
  if (!currentFile) return;
  if (action === 'delete') {
    const deletedFile = currentFile;
    // Call server API to soft-delete
    if (typeof deletedFile.id === 'number') {
      fetch('/api/files/' + deletedFile.id, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '' }
      }).catch(() => {});
    }
    vault.deletedFiles.push({...deletedFile, deleted: new Date().toLocaleDateString('en-US',{month:'short',day:'numeric',year:'numeric'})});
    vault.files = vault.files.filter(f => f.id !== deletedFile.id);
    vault.storageUsed = Math.max(0, vault.storageUsed - deletedFile.size);
    updateStorageDisplay();
    const card = document.getElementById('fc-' + deletedFile.id);
    if (card) card.remove();
    const tile = document.querySelector('[data-fid="' + deletedFile.id + '"]');
    if (tile) tile.remove();
    closeDetails();
    showToast(deletedFile.name + ' moved to Recycle Bin');
    renderPage(currentPage);
    renderHome();
  } else if (action === 'share') {
    openSharePanel([currentFile]);
  } else if (action === 'download') {
    // Append ?download=1 so the server returns Content-Disposition: attachment
    const rawUrl = currentFile.downloadUrl || currentFile.objectUrl;
    const url = rawUrl && currentFile.downloadUrl
      ? rawUrl + (rawUrl.includes('?') ? '&download=1' : '?download=1')
      : rawUrl;
    if (url) {
      const a = document.createElement('a');
      a.href = url;
      a.download = currentFile.name;
      document.body.appendChild(a);
      a.click();
      document.body.removeChild(a);
      showToast('Downloading ' + currentFile.name + '\u2026');
    } else {
      // Create blob URL on demand for video/music
      if (currentFile.fileRef) {
        const blobUrl = URL.createObjectURL(currentFile.fileRef);
        const a = document.createElement('a');
        a.href = blobUrl;
        a.download = currentFile.name;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        showToast('Downloading ' + currentFile.name + '…');
      } else {
        showToast('File not available for download.', 'error');
      }
    }
  } else if (action === 'rename') {
    openRenameModal(currentFile);
  } else if (action === 'move') {
    openMoveModal([currentFile.id]);
  } else if (action === 'info') {
    showToast(currentFile.name + ' · ' + formatBytes(currentFile.size) + ' · ' + (currentFile.type || currentFile.category));
  } else if (action === 'open') {
    const url = currentFile.downloadUrl || currentFile.objectUrl;
    if (url) { window.open(url, '_blank'); }
    else { showToast('No preview available for ' + currentFile.name); }
  }
}

function openRenameModal(file) {
  // reuse the newFolder modal for renaming
  const input = document.getElementById('newFolderName');
  input.value = file.name;
  const modal = document.getElementById('modalNewFolder');
  modal.querySelector('h2').textContent = 'Rename File';
  modal.querySelector('.modal-btn.primary').onclick = async function() {
    const newName = input.value.trim();
    if (!newName) { input.focus(); return; }
    
    // Call server API to persist rename
    if (typeof file.id === 'number') {
      try {
        const res = await fetch('/api/files/' + file.id + '/rename', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || ''
          },
          body: JSON.stringify({ name: newName })
        });
        if (!res.ok) {
          const data = await res.json();
          showToast(data.error || 'Failed to rename file', 'error');
          return;
        }
      } catch(e) {
        showToast('Network error during rename', 'error');
        return;
      }
    }
    
    file.name = newName;
    const detTitle = document.getElementById('detTitle');
    const detName = document.getElementById('detName');
    if (detTitle) detTitle.textContent = newName;
    if (detName) detName.textContent = newName;
    const card = document.getElementById('fc-' + file.id);
    if (card) { const nm = card.querySelector('.fc-name'); if (nm) nm.textContent = newName; }
    closeModal('modalNewFolder');
    showToast('Renamed to ' + newName);
    renderHome();
    renderCurrentAndCategory();
  };
  openModal('modalNewFolder');
  setTimeout(() => input.focus(), 80);
}

// ─── SIDEBAR ────────────────────────────────────────────────────────────────
function toggleSidebar() {
  sidebarCollapsed = !sidebarCollapsed;
  document.getElementById('sidebar').classList.toggle('collapsed', sidebarCollapsed);
}

// ─── VIEW TOGGLE ────────────────────────────────────────────────────────────
function toggleView(gridId) {
  const el = document.getElementById(gridId);
  if (el) el.classList.toggle('list-view');
}

// ─── UPLOAD ─────────────────────────────────────────────────────────────────
function triggerUpload() { document.getElementById('fileInput').click(); }

function handleFileSelect(input) {
  if (!input.files.length) return;
  Array.from(input.files).forEach(file => simulateUpload(file));
  input.value = '';
}

// Returns a short display name for grid cards — first word + extension
function shortName(name) {
  const dot = name.lastIndexOf('.');
  const base = dot > 0 ? name.substring(0, dot) : name;
  const ext  = dot > 0 ? name.substring(dot) : '';
  // Take first word (split on space, underscore, hyphen, dot)
  const first = base.split(/[\s_\-\.]+/)[0];
  return first.length < base.length ? first + '…' + ext : name;
}

function getFileIco(name) {
  const ext = name.split('.').pop().toLowerCase();
  const map = {
    jpg:'image', jpeg:'image', png:'image', gif:'image', webp:'image', heic:'image',
    mp4:'video', mov:'video', avi:'video', mkv:'video', webm:'video',
    mp3:'music', wav:'music', flac:'music', aac:'music', m4a:'music',
    pdf:'file-text', doc:'file-text', docx:'file-text', xls:'file-spreadsheet',
    xlsx:'file-spreadsheet', ppt:'presentation', pptx:'presentation',
    txt:'file', zip:'archive', rar:'archive', '7z':'archive', tar:'archive', gz:'archive',
    fig:'pen-tool', sketch:'pen-tool', ai:'pen-tool', psd:'pen-tool'
  };
  return map[ext] || 'file';
}

function getFileCategory(name) {
  const ext = name.split('.').pop().toLowerCase();
  if (['jpg','jpeg','png','gif','webp','heic'].includes(ext)) return 'photo';
  if (['mp4','mov','avi','mkv','webm'].includes(ext)) return 'video';
  if (['mp3','wav','flac','aac','m4a'].includes(ext)) return 'music';
  if (['pdf','doc','docx','xls','xlsx','ppt','pptx','txt'].includes(ext)) return 'doc';
  return 'other';
}

// ─── REAL FILE UPLOAD ────────────────────────────────────────────────────────
async function simulateUpload(file) {
  if (file.size > PLAN.maxFileSize) {
    if (USER_PLAN === 'free') showToast('File too large — Free plan allows up to 500 MB per file.', 'error');
    else if (USER_PLAN === 'pro') showToast('File too large — Pro plan allows up to 10 GB per file.', 'error');
    else showToast('File too large — Teams plan allows up to 50 GB per file.', 'error');
    return;
  }
  const bar = document.getElementById('uploadBar');
  const fill = document.getElementById('ubFill');
  const pct = document.getElementById('ubPct');
  const fname = document.getElementById('ubFileName');
  fname.textContent = 'Uploading ' + file.name;
  bar.classList.add('active');
  fill.style.width = '10%'; pct.textContent = '10%';
  const formData = new FormData();
  formData.append('file', file);
  formData.append('_token', document.querySelector('meta[name=csrf-token]')?.content || '');
  const curFolderId = currentFolderId(currentPage);
  if (curFolderId) {
    formData.append('folder_id', curFolderId);
  }
  if (currentPage === 'page-team-files' || (typeof _newFolderTargetPage !== 'undefined' && _newFolderTargetPage === 'page-team-files')) {
    formData.append('is_team', 1);
  }
  try {
    let prog = 10;
    const progIv = setInterval(() => { prog = Math.min(prog + Math.random() * 15, 85); fill.style.width = prog + '%'; pct.textContent = Math.round(prog) + '%'; }, 200);
    const res = await fetch('/api/files/upload', { method: 'POST', body: formData });
    clearInterval(progIv);
    fill.style.width = '100%'; pct.textContent = '100%';
    const data = await res.json();
    if (!res.ok) { showToast(data.error || 'Upload failed.', 'error'); bar.classList.remove('active'); return; }
    const sf = data.file;
    const cat = sf.category;
    const catPage = { photo:'page-photos', video:'page-videos', music:'page-music', doc:'page-docs' };
    const targetPage = catPage[cat] || 'page-files';
    let thumbUrl = null, objectUrl = null;
    if (cat === 'photo') {
      objectUrl = URL.createObjectURL(file);
      try {
        const img = new Image(); img.src = objectUrl;
        await new Promise(r => { img.onload = r; img.onerror = r; });
        const canvas = document.createElement('canvas');
        const MAX = 200, ratio = Math.min(MAX/img.width, MAX/img.height);
        canvas.width = Math.round(img.width*ratio); canvas.height = Math.round(img.height*ratio);
        canvas.getContext('2d').drawImage(img, 0, 0, canvas.width, canvas.height);
        thumbUrl = canvas.toDataURL('image/jpeg', 0.7);
      } catch(e) { thumbUrl = null; }
    } else if (cat === 'video') {
      objectUrl = URL.createObjectURL(file);
      try {
        thumbUrl = await new Promise(resolve => {
          const vid = document.createElement('video');
          vid.preload = 'metadata';
          vid.muted = true;
          vid.playsInline = true;
          vid.crossOrigin = 'anonymous';
          // loadedmetadata guarantees vid.duration and vid.videoWidth/Height are valid
          vid.addEventListener('loadedmetadata', () => {
            vid.currentTime = isFinite(vid.duration) && vid.duration > 0
              ? Math.min(1, vid.duration * 0.1)
              : 0;
          });
          vid.addEventListener('seeked', () => {
            try {
              const w = vid.videoWidth  || 200;
              const h = vid.videoHeight || 200;
              const MAX = 200;
              const ratio = Math.min(MAX / w, MAX / h);
              const cvs = document.createElement('canvas');
              cvs.width  = Math.round(w * ratio);
              cvs.height = Math.round(h * ratio);
              cvs.getContext('2d').drawImage(vid, 0, 0, cvs.width, cvs.height);
              resolve(cvs.toDataURL('image/jpeg', 0.75));
            } catch(e) { resolve(null); }
          });
          vid.addEventListener('error', () => resolve(null));
          // Safety timeout — if seeking never fires, fall back gracefully
          setTimeout(() => resolve(null), 6000);
          vid.src = objectUrl;
          vid.load();
        });
      } catch(e) { thumbUrl = null; }
    }
    const f = { id: sf.id, name: sf.name, type: sf.mime_type||'File', size: sf.size, date: new Date(sf.created_at).toLocaleDateString('en-US',{month:'short',day:'numeric',year:'numeric'}), ico: getFileIco(sf.name), loc: 'My Files', category: cat, folderId: sf.folder_id || null, objectUrl, thumbUrl, fileRef: file, downloadUrl: sf.url, is_team: sf.is_team ? true : false };
    vault.files.push(f);
    vault.storageUsed += f.size;
    updateStorageDisplay();
    completeOnboardStep(0);
    renderPage(targetPage);
    if (currentPage !== targetPage) renderPage(currentPage);
    renderHome();
    setTimeout(() => { bar.classList.remove('active'); showToast(f.name + ' uploaded', 'success'); if (settings.notifUpload) addNotification('check-circle', f.name + ' uploaded successfully', targetPage); }, 300);
  } catch(e) { bar.classList.remove('active'); showToast('Upload failed. Please try again.', 'error'); }
}

// ─── LOAD FILES FROM SERVER ON INIT ──────────────────────────────────────────
async function loadFilesFromServer() {
  try {
    const res = await fetch('/api/files');
    if (!res.ok) return;
    const data = await res.json();
    vault.files = [];
    vault.storageUsed = data.used_bytes || 0;
    for (const sf of data.files) {
      vault.files.push({ id: sf.id, name: sf.name, type: sf.mime_type||'File', size: sf.size, date: new Date(sf.created_at).toLocaleDateString('en-US',{month:'short',day:'numeric',year:'numeric'}), ico: getFileIco(sf.name), loc: 'My Files', category: sf.category, folderId: sf.folder_id || null, objectUrl: null, thumbUrl: null, fileRef: null, downloadUrl: sf.url, is_team: sf.is_team ? true : false });
    }

    // Load folders from the server
    const folderRes = await fetch('/api/folders');
    if (folderRes.ok) {
      const folderData = await folderRes.json();
      vault.folders = folderData.folders.map(fo => ({
        id: fo.id,
        name: fo.name,
        parentId: fo.parent_id || null,
        page: fo.page,
        date: new Date(fo.created_at).toLocaleDateString('en-US',{month:'short',day:'numeric',year:'numeric'})
      }));
    }

    updateStorageDisplay();
    renderHome();
    Object.keys(PAGE_GRID).forEach(p => renderPage(p));
  } catch(e) { console.warn('Could not load files/folders from server:', e); }
}

function addNotification(ico, text, page) {
  notifications.unshift({ ico, text, page, time: 'Just now', read: false, id: Date.now() });
  renderNotifications();
}

function renderNotifications() {
  const list = document.getElementById('notifList');
  const badge = document.getElementById('notifBadge');
  const unread = notifications.filter(n => !n.read).length;
  badge.style.display = unread > 0 ? 'flex' : 'none';
  badge.textContent = unread;
  if (!notifications.length) { list.innerHTML = '<div class="notif-empty">You\'re all caught up</div>'; return; }
  list.innerHTML = notifications.slice(0,8).map(function(n) {
    return '<div class="notif-item ' + (n.read ? '' : 'unread') + '" onclick="clickNotif(' + n.id + ')">'
      + '<span class="notif-ico"><i data-lucide="bell" style="width:17px;height:17px;color:var(--accent);"></i></span>'
      + '<div><div class="notif-txt">' + n.text + '</div><div class="notif-time">' + n.time + '</div></div>'
      + '</div>';
  }).join('');
  lucide.createIcons();
}

function clickNotif(id) {
  const n = notifications.find(x => x.id === id);
  if (n) { n.read = true; if (n.page) navTo(n.page); renderNotifications(); toggleNotifDrop(); }
}

function markAllRead() { notifications.forEach(n => n.read = true); renderNotifications(); }

function toggleNotifDrop() {
  notifDropOpen = !notifDropOpen;
  document.getElementById('notifDrop').style.display = notifDropOpen ? 'block' : 'none';
}

document.addEventListener('click', e => {
  if (!document.getElementById('notifWrap').contains(e.target)) {
    notifDropOpen = false;
    document.getElementById('notifDrop').style.display = 'none';
  }
  hideCtxMenu();
});

// ─── SEARCH DROPDOWN ────────────────────────────────────────────────────────
function showSearchDrop() {
  const q = document.getElementById('globalSearch').value.trim();
  renderSearchDrop(q);
  document.getElementById('searchDrop').classList.add('active');
}
function hideSearchDrop() { document.getElementById('searchDrop').classList.remove('active'); }
function renderSearchDrop(q) {
  const drop = document.getElementById('searchDrop');
  if (!vault.files.length) { drop.innerHTML = '<div class="sdrop-label">No files yet</div>'; return; }
  const matches = q ? vault.files.filter(f => f.name.toLowerCase().includes(q.toLowerCase())) : vault.files.slice(-5).reverse();
  const label = q ? 'Results for "' + escHtml(q) + '"' : 'Recent';
  if (!matches.length) { drop.innerHTML = '<div class="sdrop-label">No results for "' + escHtml(q) + '"</div>'; return; }
  const icoColors = { photo:'#00d4ff', video:'#ff6b9d', music:'#a78bfa', doc:'#fb923c', other:'#636b7d' };
  drop.innerHTML = '<div class="sdrop-label">' + label + '</div>' +
    matches.slice(0,6).map(function(f) {
      const icoColor = icoColors[f.category] || '#636b7d';
      return '<div class="sdrop-item" onclick="openDetails(' + f.id + ');hideSearchDrop()">'
        + '<span><i data-lucide="' + f.ico + '" style="width:16px;height:16px;color:' + icoColor + ';"></i></span>'
        + '<span style="flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">' + escHtml(f.name) + '</span>'
        + '<span class="sdrop-cat">' + f.category + '</span>'
        + '</div>';
    }).join('');
  lucide.createIcons();
}

// ─── MODAL HELPERS ──────────────────────────────────────────────────────────
function openModal(id) { document.getElementById(id).classList.add('active'); }
function closeModal(id) {
  document.getElementById(id).classList.remove('active');
  if (id === 'modalNewFolder') {
    const modal = document.getElementById('modalNewFolder');
    modal.querySelector('h2').textContent = 'New Folder';
    modal.querySelector('.modal-btn.primary').onclick = confirmNewFolder;
  }
}

// ─── INVITE ─────────────────────────────────────────────────────────────────
function copyInviteLink() {
  const val = document.getElementById('inviteLink').value;
  navigator.clipboard && navigator.clipboard.writeText(val);
  showToast('Invite link copied!', 'success');
  completeOnboardStep(2);
  closeModal('modalInvite');
}
function shareVia(channel) {
  const link = document.getElementById('inviteLink').value;
  const msg = encodeURIComponent('Join me on Keeption Vault — private encrypted storage. ' + link);
  const urls = { whatsapp: 'https://wa.me/?text=' + msg, email: 'mailto:?subject=Join Keeption Vault&body=' + msg, sms: 'sms:?body=' + msg };
  window.open(urls[channel], '_blank');
  completeOnboardStep(2);
  closeModal('modalInvite');
}

// ─── SETTINGS ───────────────────────────────────────────────────────────────
const SETTINGS_KEY = 'kv_settings';
const settingsDefaults = {
  darkMode: true,
  language: 'English',
  notifUpload: true,
  notifLink: true,
  notifShare: true,
  notifStorage: true,
  storageThreshold: '80%',
  cameraBackup: false,
  videoQuality: 'Original quality',
};

let settings = Object.assign({}, settingsDefaults, JSON.parse(localStorage.getItem(SETTINGS_KEY) || '{}'));

function saveSettings() {
  localStorage.setItem(SETTINGS_KEY, JSON.stringify(settings));
}

function toggleSetting(btnId, key) {
  settings[key] = !settings[key];
  const el = document.getElementById(btnId);
  if (el) el.classList.toggle('on', settings[key]);
  saveSettings();
  // Side effects
  if (key === 'darkMode') applyDarkMode();
  if (key === 'cameraBackup' && settings[key]) openModal('modalCamera');
  showToast('Setting saved', 'info');
}

function applyDarkMode() {
  // Toggle a light-mode class on root; CSS vars swap when it's present
  document.documentElement.classList.toggle('light-mode', !settings.darkMode);
}

function applySettings() {
  // Toggles
  const toggleMap = {
    darkMode: 'toggleDark',
    notifUpload: 'toggleNotifUpload',
    notifLink: 'toggleNotifLink',
    notifShare: 'toggleNotifShare',
    notifStorage: 'toggleNotifStorage',
    cameraBackup: 'toggleCameraBackup',
  };
  Object.entries(toggleMap).forEach(([key, id]) => {
    const el = document.getElementById(id);
    if (el) el.classList.toggle('on', settings[key]);
  });

  // Selects
  const langEl = document.getElementById('settingLanguage');
  if (langEl) langEl.value = settings.language;

  const threshEl = document.getElementById('storageThreshold');
  if (threshEl) threshEl.value = settings.storageThreshold;

  const qualEl = document.getElementById('settingVideoQuality');
  if (qualEl) qualEl.value = settings.videoQuality;

  applyDarkMode();
}

function onSelectChange(key, el) {
  settings[key] = el.value;
  saveSettings();
  showToast('Setting saved', 'info');
}

// ─── MOBILE MORE MENU ───────────────────────────────────────────────────────
function toggleMobMore() {
  const sheet = document.getElementById('mobMoreSheet');
  const bg = document.getElementById('mobMoreBg');
  const open = sheet.classList.contains('active');
  sheet.classList.toggle('active', !open);
  bg.classList.toggle('active', !open);
}
function closeMobMore() {
  document.getElementById('mobMoreSheet').classList.remove('active');
  document.getElementById('mobMoreBg').classList.remove('active');
}

function resetSettings() {  if (!confirm('Reset all settings to defaults?')) return;
  Object.assign(settings, settingsDefaults);
  saveSettings();
  applySettings();
  showToast('Settings reset to defaults', 'info');
}

// ─── PROFILE PULSE ──────────────────────────────────────────────────────────
function pulseProfileFields() {
  setTimeout(() => {
    ['profileDisplayName','profileBio'].forEach(id => {
      const el = document.getElementById(id);
      if (el) { el.classList.add('pulse-field'); el.addEventListener('animationend', () => el.classList.remove('pulse-field'), {once:true}); }
    });
  }, 400);
}

// ─── INVITEF RIEND (old ob button) ──────────────────────────────────────────
function inviteFriend() { openModal('modalInvite'); }

// ─── BULK ACTIONS ───────────────────────────────────────────────────────────
function bulkDownload() { showToast(selectedFiles.size + ' files downloading…'); clearSelection(); }
function bulkMove() { openMoveModal([...selectedFiles]); }
function bulkShare() {
  selectedFiles.forEach(id => {
    const f = vault.files.find(x => x.id === id);
    if (f) vault.sharedLinks.push({ id: Date.now() + Math.random(), fileId: id, fileName: f.name, views: 0, expires: 'Never', status: 'active' });
  });
  showToast(selectedFiles.size + ' share links created');
  renderLinks();
  clearSelection();
}
function bulkDelete() {
  const ids = [...selectedFiles];
  ids.forEach(id => {
    const f = vault.files.find(x => x.id === id);
    if (f) {
      // Call server API to soft-delete
      if (typeof id === 'number') {
        fetch('/api/files/' + id, {
          method: 'DELETE',
          headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '' }
        }).catch(() => {});
      }
      vault.deletedFiles.push({...f, deleted: new Date().toLocaleDateString('en-US',{month:'short',day:'numeric',year:'numeric'})});
      vault.storageUsed = Math.max(0, vault.storageUsed - f.size);
      // Instantly remove from DOM
      const card = document.getElementById('fc-' + id);
      if (card) card.remove();
    }
  });
  vault.files = vault.files.filter(f => !selectedFiles.has(f.id));
  updateStorageDisplay();
  showToast(ids.length + ' files moved to Recycle Bin');
  clearSelection();
  renderPage(currentPage);
  renderHome();
}

// ─── FOLDER ─────────────────────────────────────────────────────────────────
let _newFolderTargetPage = null;

function newFolder(page) {
  // if a specific page is passed use it, otherwise use current page
  _newFolderTargetPage = page || (PAGE_GRID[currentPage] ? currentPage : 'page-files');
  const input = document.getElementById('newFolderName');
  input.value = '';
  const modal = document.getElementById('modalNewFolder');
  modal.querySelector('h2').textContent = 'New Folder';
  modal.querySelector('.modal-btn.primary').onclick = confirmNewFolder;
  openModal('modalNewFolder');
  setTimeout(() => input.focus(), 80);
}

async function confirmNewFolder() {
  const name = document.getElementById('newFolderName').value.trim();
  if (!name) {
    document.getElementById('newFolderName').focus();
    return;
  }
  const targetPage = _newFolderTargetPage || 'page-files';
  const parentId = currentFolderId(targetPage);
  const isTeam = targetPage === 'page-team-files' ? 1 : 0;

  try {
    const res = await fetch('/api/folders', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || ''
      },
      body: JSON.stringify({
        name: name,
        parent_id: parentId,
        page: targetPage,
        is_team: isTeam
      })
    });
    if (!res.ok) {
      const data = await res.json();
      showToast(data.error || 'Failed to create folder', 'error');
      return;
    }
    const data = await res.json();
    const fo = {
      id: data.folder.id,
      name: data.folder.name,
      parentId: data.folder.parent_id || null,
      page: data.folder.page,
      date: new Date(data.folder.created_at).toLocaleDateString('en-US',{month:'short',day:'numeric',year:'numeric'}),
    };
    vault.folders.push(fo);
    closeModal('modalNewFolder');
    renderPage(targetPage);
    if (currentPage !== targetPage) navTo(targetPage);
    showToast('"' + fo.name + '" folder created');
  } catch(e) {
    showToast('Network error while creating folder', 'error');
  }
  _newFolderTargetPage = null;
}

// ─── LINKS ──────────────────────────────────────────────────────────────────

// ─── CONTEXT MENU ───────────────────────────────────────────────────────────
let ctxFileId = null, ctxPage = null, ctxFolderId = null;

function showCtxMenu(e, fileId, page, folderId) {
  e.preventDefault();
  ctxFileId = fileId; ctxPage = page; ctxFolderId = folderId;
  const menu = document.getElementById('ctxMenu');
  let items = '';
  if (fileId !== null) {
    const f = vault.files.find(x => x.id === fileId);
    if (f && f.category === 'music') items += '<div class="ctx-item" onclick="loadTrack(' + fileId + ');hideCtxMenu()"><i data-lucide="play" style="width:14px;height:14px;margin-right:4px;"></i> Play</div>';
    items += '<div class="ctx-item" onclick="detAction_ctx(\'download\');hideCtxMenu()"><i data-lucide="download" style="width:14px;height:14px;margin-right:4px;"></i> Download</div>';
    items += '<div class="ctx-item" onclick="openDetails(' + fileId + ');hideCtxMenu()"><i data-lucide="info" style="width:14px;height:14px;margin-right:4px;"></i> Details</div>';
    items += '<div class="ctx-item" onclick="detAction_ctx(\'rename\');hideCtxMenu()"><i data-lucide="pencil" style="width:14px;height:14px;margin-right:4px;"></i> Rename</div>';
    items += '<div class="ctx-item" onclick="openMoveModal([' + fileId + ']);hideCtxMenu()"><i data-lucide="folder-input" style="width:14px;height:14px;margin-right:4px;"></i> Move</div>';
    items += '<div class="ctx-item" onclick="detAction_ctx(\'share\');hideCtxMenu()"><i data-lucide="share-2" style="width:14px;height:14px;margin-right:4px;"></i> Share</div>';
    items += '<div class="ctx-sep"></div>';
    items += '<div class="ctx-item danger" onclick="detAction_ctx(\'delete\');hideCtxMenu()"><i data-lucide="trash-2" style="width:14px;height:14px;margin-right:4px;"></i> Delete</div>';
  } else if (folderId !== null) {
    items += '<div class="ctx-item" onclick="renameFolderCtx();hideCtxMenu()"><i data-lucide="pencil" style="width:14px;height:14px;margin-right:4px;"></i> Rename Folder</div>';
    items += '<div class="ctx-sep"></div>';
    items += '<div class="ctx-item danger" onclick="deleteFolderCtx();hideCtxMenu()"><i data-lucide="trash-2" style="width:14px;height:14px;margin-right:4px;"></i> Delete Folder</div>';
  }
  menu.innerHTML = items;
  menu.style.display = 'block';
  const x = Math.min(e.clientX, window.innerWidth - 180);
  const y = Math.min(e.clientY, window.innerHeight - menu.offsetHeight - 10);
  menu.style.left = x + 'px';
  menu.style.top  = y + 'px';
  lucide.createIcons();
}

function hideCtxMenu() { const m = document.getElementById('ctxMenu'); if(m) m.style.display = 'none'; }

function detAction_ctx(action) {
  if (ctxFileId === null) return;
  const f = vault.files.find(x => x.id === ctxFileId);
  if (!f) return;
  currentFile = f;
  detAction(action);
}

function renameFolderCtx() {
  const fo = vault.folders.find(x => x.id === ctxFolderId);
  if (!fo) return;
  // reuse the new folder modal for renaming
  _newFolderTargetPage = ctxPage;
  const input = document.getElementById('newFolderName');
  input.value = fo.name;
  
  // Set up input keydown for renaming when pressing Enter!
  const handleRenameEnter = async function(e) {
    if (e.key === 'Enter') {
      e.preventDefault();
      await executeRename();
    }
  };
  input.onkeydown = handleRenameEnter;

  const executeRename = async function() {
    const name = input.value.trim();
    if (!name) { input.focus(); return; }
    try {
      const res = await fetch('/api/folders/' + fo.id + '/rename', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || ''
        },
        body: JSON.stringify({ name: name })
      });
      if (!res.ok) {
        showToast('Failed to rename folder', 'error');
        return;
      }
      fo.name = name;
      renderPage(ctxPage);
      showToast('Folder renamed');
    } catch(err) {
      showToast('Network error during folder rename', 'error');
    }
    closeModal('modalNewFolder');
    restoreFolderModalDefaults();
  };

  const restoreFolderModalDefaults = function() {
    document.querySelector('#modalNewFolder h2').textContent = 'New Folder';
    document.querySelector('#modalNewFolder .modal-btn.primary').onclick = confirmNewFolder;
    input.onkeydown = function(e) { if(e.key==='Enter') confirmNewFolder(); };
  };

  document.querySelector('#modalNewFolder h2').textContent = 'Rename Folder';
  document.querySelector('#modalNewFolder .modal-btn.primary').onclick = executeRename;

  // Also hook into modal-close reset!
  const origCloseModal = window.closeModal;
  window.closeModal = function(id) {
    origCloseModal(id);
    if (id === 'modalNewFolder') {
      restoreFolderModalDefaults();
    }
  };

  openModal('modalNewFolder');
  setTimeout(() => input.focus(), 80);
}

async function deleteFolderCtx() {
  if (!confirm('Delete this folder and all its contents?')) return;

  try {
    const res = await fetch('/api/folders/' + ctxFolderId, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || ''
      }
    });
    if (!res.ok) {
      showToast('Failed to delete folder', 'error');
      return;
    }

    // Recursively collect all folder IDs to remove from the vault.folders state
    const getRecursiveLocalIds = function(id) {
      let ids = [id];
      const subs = vault.folders.filter(x => x.parentId === id).map(x => x.id);
      subs.forEach(subId => {
        ids = ids.concat(getRecursiveLocalIds(subId));
      });
      return ids;
    };

    const deletedFolderIds = getRecursiveLocalIds(ctxFolderId);

    // Filter local states
    vault.folders = vault.folders.filter(x => !deletedFolderIds.includes(x.id));
    
    // Remove files belonging to these folders
    const removedFiles = vault.files.filter(x => deletedFolderIds.includes(x.folderId));
    removedFiles.forEach(f => { vault.storageUsed = Math.max(0, vault.storageUsed - f.size); });
    vault.files = vault.files.filter(x => !deletedFolderIds.includes(x.folderId));

    updateStorageDisplay();
    renderPage(ctxPage);
    showToast('Folder and contents deleted');
  } catch(e) {
    showToast('Network error during folder deletion', 'error');
  }
}

// ─── MOVE MODAL ─────────────────────────────────────────────────────────────
let moveFileIds = [];
let moveSelectedFolderId = undefined;

function openMoveModal(fileIds) {
  moveFileIds = fileIds;
  moveSelectedFolderId = undefined;
  const list = document.getElementById('folderPickList');
  
  // Find out if the files being moved are team files or personal files
  const firstFile = vault.files.find(x => x.id === fileIds[0]);
  const isTeamMove = firstFile ? firstFile.is_team : (currentPage === 'page-team-files');
  
  const allFolders = vault.folders.filter(fo => {
    if (isTeamMove) {
      return fo.page === 'page-team-files';
    } else {
      return fo.page !== 'page-team-files';
    }
  });

  if (!allFolders.length) {
    list.innerHTML = '<div style="color:var(--muted);font-size:.83rem;padding:10px 0;">No folders yet. Create a folder first.</div>';
  } else {
    list.innerHTML = `<div class="folder-pick-item" onclick="pickFolder(null,this)">📁 Root (no folder)</div>` +
      allFolders.map(fo => `<div class="folder-pick-item" onclick="pickFolder(${fo.id},this)">📁 ${escHtml(fo.name)}</div>`).join('');
  }
  openModal('modalMove');
}

function pickFolder(id, el) {
  moveSelectedFolderId = id; // null = root, number = folder id
  document.querySelectorAll('.folder-pick-item').forEach(x => x.classList.remove('selected'));
  el.classList.add('selected');
}

function confirmMove() {
  if (moveSelectedFolderId === undefined) { showToast('⚠','Pick a destination first','warn'); return; }
  moveFileIds.forEach(id => {
    const f = vault.files.find(x => x.id === id);
    if (f) {
      f.folderId = moveSelectedFolderId;
      if (typeof id === 'number') {
        fetch('/api/files/' + id + '/move', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || ''
          },
          body: JSON.stringify({ folder_id: moveSelectedFolderId })
        }).catch(() => {});
      }
    }
  });
  showToast('📁', moveFileIds.length + ' file(s) moved');
  closeModal('modalMove');
  clearSelection();
  renderCurrentAndCategory();
  moveSelectedFolderId = undefined;
}


// ─── STORAGE ANALYZER ───────────────────────────────────────────────────────
function renderStorageAnalyzer() {
  const el = document.getElementById('storageContent');
  if (!el) return;
  const cats = [
    { key:'photo', label:'Photos',    ico:'image',     color:'#00f5a0' },
    { key:'video', label:'Videos',    ico:'video',     color:'#00d4ff' },
    { key:'music', label:'Music',     ico:'music',     color:'#ff6b9d' },
    { key:'doc',   label:'Documents', ico:'file-text', color:'#ffd166' },
    { key:'other', label:'Other',     ico:'file',      color:'#a78bfa' },
  ];
  const total = vault.storageTotal;
  const used  = vault.storageUsed;
  const pct   = Math.min((used / total) * 100, 100);

  let breakdownHtml = '';
  if (vault.files.length) {
    const catCards = cats.map(function(c) {
      const size = vault.files.filter(function(f){ return f.category === c.key; }).reduce(function(a,f){ return a + f.size; }, 0);
      const cpct = total > 0 ? Math.min((size / total) * 100, 100) : 0;
      return '<div class="stor-cat">'
        + '<div class="stor-cat-top"><span class="stor-cat-ico"><i data-lucide="' + c.ico + '" style="width:22px;height:22px;color:' + c.color + ';stroke-width:1.5;"></i></span>'
        + '<div><div class="stor-cat-name">' + c.label + '</div>'
        + '<div class="stor-cat-size">' + formatBytes(size) + '</div></div></div>'
        + '<div class="stor-bar"><div class="stor-fill" style="width:' + cpct.toFixed(1) + '%;background:' + c.color + ';"></div></div>'
        + '</div>';
    });
    breakdownHtml = '<div class="storage-breakdown">' + catCards.join('') + '</div>';
  } else {
    breakdownHtml = '<div class="empty-state" style="min-height:180px;padding:24px;"><div class="es-illo"><i data-lucide="pie-chart" style="width:56px;height:56px;color:var(--muted);stroke-width:1;"></i></div><div class="es-title">No files uploaded yet</div><div class="es-sub">Upload files to see your storage breakdown here.</div></div>';
  }

  el.innerHTML = '<div style="background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:20px;margin-bottom:20px;">'
    + '<div style="display:flex;justify-content:space-between;font-size:.82rem;color:var(--muted);margin-bottom:10px;">'
    + '<span id="storUsedLabel">' + formatBytes(used) + ' used</span>'
    + '<span id="storFreeLabel">' + formatBytes(total - used) + ' free</span></div>'
    + '<div style="height:10px;background:var(--surface2);border-radius:99px;overflow:hidden;margin-bottom:8px;">'
    + '<div id="storMainFill" style="height:100%;width:' + pct.toFixed(1) + '%;background:linear-gradient(90deg,var(--accent),var(--accent2));border-radius:99px;transition:width .5s;"></div></div>'
    + '<div style="font-size:.75rem;color:var(--muted);" id="storPctLabel">' + Math.round(pct) + '% of 5 GB used</div>'
    + '</div>'
    + breakdownHtml;
  lucide.createIcons();
}

// ─── BIN ────────────────────────────────────────────────────────────────────
function restoreFile(id) {
  const f = vault.deletedFiles.find(x => x.id === id);
  if (!f) return;
  vault.files.push(f);
  vault.storageUsed += f.size;
  vault.deletedFiles = vault.deletedFiles.filter(x => x.id !== id);
  updateStorageDisplay();
  showToast('↩', f.name + ' restored');
  renderBin();
  renderHome();
  // navigate to the file's page
  const catPage = { photo:'page-photos', video:'page-videos', music:'page-music', doc:'page-docs', other:'page-files' };
  const target = catPage[f.category] || 'page-files';
  renderCurrentAndCategory();
  navTo(target);
}
function permDelete(id) {
  vault.deletedFiles = vault.deletedFiles.filter(x => x.id !== id);
  showToast('🗑', 'File permanently deleted');
  renderBin();
}
function emptyBin() {
  if (!vault.deletedFiles.length) return;
  vault.deletedFiles = [];
  showToast('🗑', 'Recycle bin emptied');
  renderBin();
}
function renderBin() {
  const el = document.getElementById('binContent');
  if (!el) return;
  if (!vault.deletedFiles.length) {
    el.innerHTML = '<div class="empty-state"><div class="es-illo"><i data-lucide="trash-2" style="width:56px;height:56px;color:var(--muted);stroke-width:1;"></i></div><div class="es-title">Recycle Bin is empty</div><div class="es-sub">Files you delete are kept here for 30 days before being permanently removed.</div></div>';
    lucide.createIcons(); return;
  }
  const icoColors = { photo:'#00d4ff', video:'#ff6b9d', music:'#a78bfa', doc:'#fb923c', other:'#636b7d' };
  const cards = vault.deletedFiles.map(function(f) {
    const n = escHtml(f.name);
    const icoColor = icoColors[f.category] || '#636b7d';
    return '<div class="file-card">'
      + '<span class="fc-ico"><i data-lucide="' + f.ico + '" style="width:32px;height:32px;color:' + icoColor + ';stroke-width:1.4;"></i></span>'
      + '<div class="fc-name">' + n + '</div>'
      + '<div class="fc-meta">' + formatBytes(f.size) + ' · Deleted ' + f.deleted + '</div>'
      + '<div style="display:flex;gap:6px;margin-top:10px;">'
      + '<button class="bulk-btn" style="font-size:.72rem;" onclick="restoreFile(' + f.id + ')">Restore</button>'
      + '<button class="bulk-btn" style="font-size:.72rem;color:var(--red);" onclick="permDelete(' + f.id + ')">Delete</button>'
      + '</div></div>';
  }).join('');
  el.innerHTML = '<div class="file-grid">' + cards + '</div>';
  lucide.createIcons();
}

// ─── AI SEARCH ──────────────────────────────────────────────────────────────
function aiSearch() {
  const q = document.getElementById('aiQuery').value.trim();
  const results = document.getElementById('aiResults');
  if (!q) return;
  if (!vault.files.length) {
    results.innerHTML = '<div class="empty-state" style="min-height:120px;"><div class="es-illo"><i data-lucide="inbox" style="width:40px;height:40px;color:var(--muted);stroke-width:1;"></i></div><div class="es-title">No files to search</div><div class="es-sub">Upload files first, then search through them here.</div></div>';
    lucide.createIcons(); return;
  }
  results.innerHTML = '<div style="color:var(--muted);font-size:.83rem;padding:10px 0;">🤖 Searching your vault…</div>';
  setTimeout(() => {
    const matches = vault.files.filter(f => f.name.toLowerCase().includes(q.toLowerCase()));
    if (!matches.length) { results.innerHTML = `<div style="color:var(--muted);font-size:.85rem;padding:16px 0;">No files found matching "${escHtml(q)}"</div>`; return; }
    results.innerHTML = `<div style="color:var(--muted);font-size:.78rem;margin-bottom:12px;">${matches.length} result${matches.length>1?'s':''} for "${escHtml(q)}"</div><div class="file-grid"></div>`;
    renderFileCards(results.querySelector('.file-grid'), matches);
  }, 600);
}

// ─── DUPLICATE FINDER ───────────────────────────────────────────────────────
function scanDupes() {
  const el = document.getElementById('dupesResult');
  const btn = document.getElementById('scanBtn');
  if (!vault.files.length) {
    el.innerHTML = '<div class="empty-state"><div class="es-illo"><i data-lucide="inbox" style="width:56px;height:56px;color:var(--muted);stroke-width:1;"></i></div><div class="es-title">No files to scan</div><div class="es-sub">Upload files first, then run a scan to find duplicates.</div></div>';
    lucide.createIcons(); return;
  }
  btn.textContent = '⏳ Scanning…';
  setTimeout(() => {
    btn.textContent = '🔍 Start Scan';
    // Find real duplicates by name similarity
    const seen = {}, dupes = [];
    vault.files.forEach(f => {
      const base = f.name.replace(/\s*\(copy\)|\s*\(\d+\)/gi,'').trim().toLowerCase();
      if (!seen[base]) seen[base] = [];
      seen[base].push(f);
    });
    Object.values(seen).forEach(group => { if (group.length > 1) dupes.push(group); });
    if (!dupes.length) {
      el.innerHTML = '<div class="empty-state"><div class="es-illo"><i data-lucide="check-circle" style="width:56px;height:56px;color:var(--accent);stroke-width:1;"></i></div><div class="es-title">No duplicates found</div><div class="es-sub">Your vault is clean — no duplicate files detected.</div></div>';
      lucide.createIcons(); return;
    }
    const dupeGroups = dupes.map(function(group) {
      const saveSize = formatBytes(group.slice(1).reduce(function(a,f){ return a+f.size; }, 0));
      const dupeFiles = group.map(function(f, i) {
        const n = escHtml(f.name);
        const icoColors = { photo:'#00d4ff', video:'#ff6b9d', music:'#a78bfa', doc:'#fb923c', other:'#636b7d' };
        const icoColor = icoColors[f.category] || '#636b7d';
        const action = i === 0
          ? '<span style="font-size:.68rem;color:var(--accent);margin-left:auto;">Keep</span>'
          : '<button class="dupe-del" onclick="removeDupe(' + f.id + ')">Remove</button>';
        return '<div class="dupe-file">'
          + '<span class="dupe-file-ico"><i data-lucide="' + f.ico + '" style="width:18px;height:18px;color:' + icoColor + ';"></i></span>'
          + '<span class="dupe-file-name">' + n + '</span>'
          + '<span class="dupe-file-size">' + formatBytes(f.size) + '</span>'
          + action
          + '</div>';
      }).join('');
      return '<div class="dupe-group">'
        + '<div class="dupe-hdr"><span class="dupe-title">' + escHtml(group[0].name) + '</span><span class="dupe-save">Save ' + saveSize + '</span></div>'
        + '<div class="dupe-files">' + dupeFiles + '</div>'
        + '</div>';
    }).join('');
    el.innerHTML = '<div style="color:var(--muted);font-size:.82rem;margin-bottom:16px;">Found ' + dupes.length + ' duplicate group' + (dupes.length > 1 ? 's' : '') + '</div>' + dupeGroups;
    lucide.createIcons();
  }, 1200);
}

function removeDupe(id) {
  const f = vault.files.find(x => x.id === id);
  if (f) { vault.storageUsed = Math.max(0, vault.storageUsed - f.size); vault.files = vault.files.filter(x => x.id !== id); updateStorageDisplay(); showToast('🗑', 'Duplicate removed'); scanDupes(); }
}

// ─── MUSIC PLAYER ───────────────────────────────────────────────────────────
let currentTrackId = null;

function loadTrack(fileId) {
  const f = vault.files.find(x => x.id === fileId);
  if (!f || f.category !== 'music') return;
  currentTrackId = fileId;

  // Create blob URL on demand — only when user clicks play
  if (!f.objectUrl && f.fileRef) f.objectUrl = URL.createObjectURL(f.fileRef);
  const trackUrl = f.objectUrl || f.downloadUrl;

  const wrap = document.getElementById('musicPlayerWrap');
  wrap.style.display = 'block';
  document.getElementById('mpTitle').textContent = f.name;
  document.getElementById('mpArt').innerHTML = '<i data-lucide="music" style="width:24px;height:24px;color:var(--accent);"></i>';
  lucide.createIcons();
  musicProgress = 0;
  document.getElementById('mpProgFill').style.width = '0%';
  if (musicPlaying) { clearInterval(musicTimer); musicPlaying = false; }
  document.getElementById('mpPlay').textContent = '▶';

  // Use real HTML5 audio if we have a track URL
  let audioEl = document.getElementById('kvAudioPlayer');
  if (!audioEl) {
    audioEl = document.createElement('audio');
    audioEl.id = 'kvAudioPlayer';
    audioEl.style.display = 'none';
    document.body.appendChild(audioEl);
  }
  if (trackUrl) {
    audioEl.src = trackUrl;
    audioEl.load();
    audioEl.play().catch(() => {});
    audioEl.ontimeupdate = () => {
      if (audioEl.duration) {
        musicProgress = (audioEl.currentTime / audioEl.duration) * 100;
        document.getElementById('mpProgFill').style.width = musicProgress + '%';
      }
    };
    audioEl.onended = () => {
      musicPlaying = false;
      const i = document.getElementById('mpPlayIco');
      if (i) { i.setAttribute('data-lucide','play'); lucide.createIcons(); }
    };
    musicPlaying = true;
    const ico = document.getElementById('mpPlayIco');
    if (ico) { ico.setAttribute('data-lucide','pause'); lucide.createIcons(); }
  } else {
    togglePlay();
  }
}

function togglePlay() {
  musicPlaying = !musicPlaying;
  const ico = document.getElementById('mpPlayIco');
  if (ico) ico.setAttribute('data-lucide', musicPlaying ? 'pause' : 'play');
  lucide.createIcons();
  if (musicPlaying) {
    musicTimer = setInterval(() => {
      musicProgress = Math.min(musicProgress + 0.4, 100);
      document.getElementById('mpProgFill').style.width = musicProgress + '%';
      if (musicProgress >= 100) {
        clearInterval(musicTimer); musicPlaying = false;
        const i = document.getElementById('mpPlayIco');
        if (i) { i.setAttribute('data-lucide','play'); lucide.createIcons(); }
        musicProgress = 0;
      }
    }, 300);
  } else { clearInterval(musicTimer); }
}

// ─── SEARCH ─────────────────────────────────────────────────────────────────
function handleSearch(q) {
  renderSearchDrop(q);
  if (!document.getElementById('searchDrop').classList.contains('active')) {
    document.getElementById('searchDrop').classList.add('active');
  }
}

// ─── PROFILE ────────────────────────────────────────────────────────────────
let _avatarFile = null;

function previewAvatar(input) {
  if (!input.files || !input.files[0]) return;
  _avatarFile = input.files[0];
  const reader = new FileReader();
  reader.onload = e => {
    const av = document.getElementById('profileAvatar');
    av.innerHTML = '<img src="' + e.target.result + '" style="width:100%;height:100%;object-fit:cover;border-radius:50%;"/>';
    // Also update topbar avatar
    const avBtn = document.getElementById('avBtn');
    if (avBtn) avBtn.innerHTML = '<img src="' + e.target.result + '" style="width:100%;height:100%;object-fit:cover;border-radius:50%;"/>';
  };
  reader.readAsDataURL(_avatarFile);
}

async function saveProfile() {
  const name = document.getElementById('profileDisplayName').value.trim();
  const bio  = document.getElementById('profileBio').value.trim();
  const btn  = document.getElementById('saveProfileBtn');
  const status = document.getElementById('profileSaveStatus');

  if (!name) {
    showToast('Please enter a display name.', 'error');
    return;
  }

  btn.disabled = true;
  btn.textContent = 'Saving…';

  const formData = new FormData();
  formData.append('name', name);
  formData.append('bio', bio);
  formData.append('_token', document.querySelector('meta[name=csrf-token]')?.content || '');
  if (_avatarFile) formData.append('avatar', _avatarFile);

  try {
    const res = await fetch('/profile/update', {
      method: 'POST',
      body: formData,
    });
    const data = await res.json();

    if (res.ok && data.success) {
      // Update UI
      document.getElementById('profileName').textContent = name;
      document.getElementById('avBtn').textContent = name.substring(0,2).toUpperCase();
      localStorage.setItem('kv_display_name', name);
      if (data.bio) document.getElementById('profileBio').value = data.bio;

      // Update avatar in topbar if returned
      if (data.avatar_url) {
        const avBtn = document.getElementById('avBtn');
        if (avBtn) avBtn.innerHTML = '<img src="' + data.avatar_url + '" style="width:100%;height:100%;object-fit:cover;border-radius:50%;"/>';
      }

      status.textContent = '✓ Saved';
      status.style.color = 'var(--accent)';
      status.style.display = 'inline';
      showToast('Profile updated successfully', 'success');
      completeOnboardStep(3);
      _avatarFile = null;
    } else {
      showToast(data.error || 'Failed to save profile.', 'error');
    }
  } catch(e) {
    showToast('Network error. Please try again.', 'error');
  } finally {
    btn.disabled = false;
    btn.textContent = 'Save Changes';
    setTimeout(() => { status.style.display = 'none'; }, 3000);
  }
}

// ─── KEYBOARD SHORTCUTS ─────────────────────────────────────────────────────
function closeShortcuts() { document.getElementById('shortcutSheet').classList.remove('active'); }

document.addEventListener('keydown', e => {
  const tag = document.activeElement.tagName;
  if (tag === 'INPUT' || tag === 'TEXTAREA') return;
  switch(e.key) {
    case 'u': case 'U': triggerUpload(); break;
    case 'n': case 'N': newFolder(); break;
    case '/': e.preventDefault(); document.getElementById('globalSearch').focus(); break;
    case 'v': case 'V': {
      const grids = {'page-files':'filesGrid','page-docs':'docsGrid'};
      if (grids[currentPage]) toggleView(grids[currentPage]);
      break;
    }
    case 'a': if (e.ctrlKey||e.metaKey) { e.preventDefault(); selectAll(); } break;
    case 'Delete': if (selectedFiles.size) bulkDelete(); break;
    case 'Escape':
      hideCtxMenu();
      if (document.getElementById('shortcutSheet').classList.contains('active')) closeShortcuts();
      else if (detailsOpen) closeDetails();
      else clearSelection();
      break;
    case '?': document.getElementById('shortcutSheet').classList.add('active'); break;
    case 'p': case 'P': navTo('page-profile'); break;
    case 'l': case 'L':
      if (!window._lHold) {
        window._lHold = setTimeout(() => { window._lHold = null; document.querySelector('form[action="/logout"]').submit(); }, 1000);
        showToast('🚪', 'Hold L for 1 second to log out…', 'warn');
      }
      break;
  }
});
document.addEventListener('keyup', e => {
  if (e.key === 'l' || e.key === 'L') { clearTimeout(window._lHold); window._lHold = null; }
});

// ─── PLAN CONFIG APPLICATION ─────────────────────────────────────────────
function applyPlanConfig() {
  console.log('USER_PLAN:', USER_PLAN);
  // Update vault storage total
  vault.storageTotal = PLAN.storageTotal;

  // Plan badge in avatar dropdown
  const badgeWrap = document.getElementById('planBadgeWrap');
  if (badgeWrap) {
    if (USER_PLAN === 'pro') {
      badgeWrap.innerHTML = '<span style="display:inline-block;font-size:.65rem;font-weight:700;letter-spacing:.05em;background:#00f5a0;color:#04060a;border-radius:99px;padding:2px 10px;">PRO</span>';
    } else if (USER_PLAN === 'teams') {
      badgeWrap.innerHTML = '<span style="display:inline-block;font-size:.65rem;font-weight:700;letter-spacing:.05em;background:linear-gradient(90deg,#00f5a0,#00d4ff);color:#04060a;border-radius:99px;padding:2px 10px;">TEAMS</span>';
    } else {
      badgeWrap.innerHTML = '<span style="display:inline-block;font-size:.65rem;font-weight:700;letter-spacing:.05em;background:rgba(255,255,255,.08);color:var(--muted);border-radius:99px;padding:2px 10px;">FREE PLAN</span>';
    }
  }

  // Storage bar gradient
  const spFill = document.getElementById('spFill');
  const sbStorFill = document.getElementById('sbStorFill');
  if (USER_PLAN === 'pro' || USER_PLAN === 'teams') {
    const grad = 'linear-gradient(90deg,#00f5a0,#00d4ff)';
    if (spFill) spFill.style.background = grad;
    if (sbStorFill) sbStorFill.style.background = grad;
  }

  // Avatar glow for Pro
  const avBtn = document.getElementById('avBtn');
  if (avBtn && USER_PLAN === 'pro') {
    avBtn.style.boxShadow = '0 0 0 2px #00f5a0, 0 0 12px rgba(0,245,160,.4)';
  } else if (avBtn && USER_PLAN === 'teams') {
    avBtn.style.boxShadow = '0 0 0 2px #00d4ff, 0 0 12px rgba(0,212,255,.4)';
  }

  // Sidebar upgrade button label
  const upgBtn = document.getElementById('sidebarUpgBtn');
  if (upgBtn) {
    if (USER_PLAN === 'pro' || USER_PLAN === 'teams') {
      upgBtn.textContent = 'Manage Plan';
      upgBtn.onclick = () => navTo('page-settings');
    }
  }

  // Teams section in sidebar
  const teamsSection = document.getElementById('sb-teams-section');
  if (teamsSection) teamsSection.style.display = USER_PLAN === 'teams' ? 'block' : 'none';

  // Storage bar color warning thresholds will be handled in updateStorageDisplay
  updateStorageDisplay();
}

function exportAuditCSV() {
  showToast('Audit trail exported as CSV');
}

function inviteMember() {
  const email = document.getElementById('inviteEmail').value.trim();
  if (!email) return;
  showToast('Invitation sent to ' + email);
  document.getElementById('inviteEmail').value = '';
}

// --- UPGRADE MODAL ----------------------------------------------------------
function openUpgradeModal(trigger) {
  const nextPlan  = USER_PLAN === 'free' ? 'Pro'   : 'Teams';
  const nextPrice = USER_PLAN === 'free' ? '$3/mo' : '$8/mo';
  const nextLink  = USER_PLAN === 'free' ? '/register?plan=pro' : '/register?plan=teams';
  const features  = USER_PLAN === 'free'
    ? ['100 GB storage (vs 5 GB)', 'Unlimited shared links', '90-day version history', 'Self-destructing links', 'AI Smart Search', 'Auto camera backup']
    : ['500 GB shared storage', 'Collaborative folders', '180-day version history', 'Full audit trail', 'Admin dashboard', 'Priority support'];

  document.getElementById('upgrade-modal-content').innerHTML = `
    <div style="text-align:center;margin-bottom:24px;">
      <div style="font-size:36px;margin-bottom:12px;">?</div>
      <h2 style="font-family:'Clash Display',sans-serif;font-size:1.4rem;font-weight:700;margin-bottom:8px;">Upgrade to ${nextPlan}</h2>
      <p style="color:#6b7280;font-size:.88rem;">Unlock powerful features for ${nextPrice}</p>
    </div>
    <div style="background:rgba(0,245,160,0.05);border:1px solid rgba(0,245,160,0.15);border-radius:12px;padding:16px;margin-bottom:20px;">
      ${features.map(f => `<div style="display:flex;align-items:center;gap:8px;padding:5px 0;font-size:.85rem;color:#eef0f6;"><span style="color:#00f5a0;font-weight:700;">?</span>${f}</div>`).join('')}
    </div>
    <a href="${nextLink}" style="display:block;width:100%;padding:13px;background:#00f5a0;border:none;border-radius:10px;color:#04060a;font-family:'Clash Display',sans-serif;font-weight:700;font-size:.95rem;cursor:pointer;text-align:center;text-decoration:none;margin-bottom:10px;">Upgrade to ${nextPlan} � ${nextPrice}</a>
    <button onclick="closeUpgradeModal()" style="width:100%;padding:10px;background:none;border:1px solid rgba(255,255,255,0.08);border-radius:10px;color:#6b7280;font-size:.85rem;cursor:pointer;font-family:'Epilogue',sans-serif;">Maybe later</button>
  `;
  const modal = document.getElementById('upgrade-modal');
  modal.style.display = 'flex';
}
function closeUpgradeModal() {
  document.getElementById('upgrade-modal').style.display = 'none';
}

// --- LOCKED FEATURE MODAL ---------------------------------------------------
const LOCKED_FEATURES = {
  'self-destruct':  { title:'Self-Destructing Links',    desc:'Create links that expire after one view. Available on Pro and Teams.',          plan:'pro',   cta:'Upgrade to Pro � $3/mo',   link:'/register?plan=pro'   },
  'ai-search':      { title:'AI Smart Search',           desc:'Search inside files using AI � find images by content, search inside PDFs.',    plan:'pro',   cta:'Upgrade to Pro � $3/mo',   link:'/register?plan=pro'   },
  'camera-backup':  { title:'Auto Camera Backup',        desc:'Automatically back up photos from your device over WiFi.',                      plan:'pro',   cta:'Upgrade to Pro � $3/mo',   link:'/register?plan=pro'   },
  'collab-folders': { title:'Collaborative Folders',     desc:'Share folders with your team and collaborate in real time.',                    plan:'teams', cta:'Upgrade to Teams � $8/mo', link:'/register?plan=teams' },
  'audit-trail':    { title:'Full Audit Trail',          desc:'See every action in your workspace � uploads, downloads, shares, and more.',    plan:'teams', cta:'Upgrade to Teams � $8/mo', link:'/register?plan=teams' },
  'admin-dashboard':{ title:'Admin Dashboard',           desc:'Manage members, set storage caps, configure workspace settings.',               plan:'teams', cta:'Upgrade to Teams � $8/mo', link:'/register?plan=teams' },
  'password-links': { title:'Password-Protected Links',  desc:'Require a password before anyone can access your shared files.',               plan:'pro',   cta:'Upgrade to Pro � $3/mo',   link:'/register?plan=pro'   },
};
function openLockedModal(featureKey) {
  let f = LOCKED_FEATURES[featureKey];
  if (!f && featureKey === 'watermarked-share') {
    f = { title: 'Forensic Watermarked Links', desc: 'Overlay recipient IP & email dynamically across shared media. Available on Pro and Teams.', cta: 'Upgrade to Pro — $3/mo', link: '/register?plan=pro' };
  }
  if (!f) return;
  document.getElementById('locked-modal-title').textContent = 'Unlock ' + f.title;
  document.getElementById('locked-modal-desc').textContent  = f.desc;
  document.getElementById('locked-modal-cta').textContent   = f.cta;
  document.getElementById('locked-modal-cta-link').href     = f.link;
  document.getElementById('locked-modal').style.display     = 'flex';
}
function closeLockedModal() {
  document.getElementById('locked-modal').style.display = 'none';
}

// ─── INIT ────────────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  const email = '{{ session("supabase_email","") }}';
  const savedName = localStorage.getItem('kv_display_name');
  const displayName = savedName || email;

  const safeText = (id, val) => { const el = document.getElementById(id); if (el) el.textContent = val; };
  const safeValue = (id, val) => { const el = document.getElementById(id); if (el) el.value = val; };

  try {
    if (email) safeText('avEmail', email);
    if (displayName) {
      const initials = displayName.substring(0,2).toUpperCase();
      safeText('avBtn', initials);
      safeText('profileAvatar', initials);
      safeText('profileName', displayName);
      if (savedName) safeValue('profileDisplayName', savedName);
    }
  } catch(e) { console.error('Error setting profile info:', e); }

  const runSafe = (fn, name) => {
    try { fn(); } catch(e) { console.error('Error during init step ' + name + ':', e); }
  };

  runSafe(applyPlanConfig, 'applyPlanConfig');
  runSafe(applySettings, 'applySettings');
  runSafe(updateOnboarding, 'updateOnboarding');
  runSafe(updateStorageDisplay, 'updateStorageDisplay');
  runSafe(loadFilesFromServer, 'loadFilesFromServer');
  runSafe(renderHome, 'renderHome');
  runSafe(renderNotifications, 'renderNotifications');
  runSafe(renderStorageAnalyzer, 'renderStorageAnalyzer');
  runSafe(renderDestructLinks, 'renderDestructLinks');
  runSafe(renderCurrentAndCategory, 'renderCurrentAndCategory');

  try {
    lucide.createIcons();
  } catch(e) {
    console.error('Lucide error:', e);
  }

  // ─── DRAG & DROP UPLOAD SYSTEM ──────────────────────────────────────────────
  const dropOverlay = document.getElementById('dropOverlay');
  if (dropOverlay) {
    // Prevent default behaviors for drag events
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
      window.addEventListener(eventName, e => {
        e.preventDefault();
        e.stopPropagation();
      }, false);
    });

    // Show drop overlay when dragging files over window
    let dragCounter = 0;
    window.addEventListener('dragenter', e => {
      if (e.dataTransfer && e.dataTransfer.types.includes('Files')) {
        dragCounter++;
        dropOverlay.classList.add('active');
      }
    }, false);

    window.addEventListener('dragleave', e => {
      if (e.dataTransfer && e.dataTransfer.types.includes('Files')) {
        dragCounter--;
        if (dragCounter === 0) {
          dropOverlay.classList.remove('active');
        }
      }
    }, false);

    window.addEventListener('dragover', e => {
      if (e.dataTransfer && e.dataTransfer.types.includes('Files')) {
        e.dataTransfer.dropEffect = 'copy';
        dropOverlay.classList.add('active');
      }
    }, false);

    // Handle dropped files
    window.addEventListener('drop', e => {
      dragCounter = 0;
      dropOverlay.classList.remove('active');
      if (e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files.length > 0) {
        Array.from(e.dataTransfer.files).forEach(file => {
          simulateUpload(file);
        });
      }
    }, false);
  }
});
</script>

<!-- UPGRADE MODAL -->
<div id="upgrade-modal" style="display:none;position:fixed;inset:0;z-index:1000;background:rgba(4,6,10,0.92);backdrop-filter:blur(10px);align-items:center;justify-content:center;" onclick="if(event.target===this)closeUpgradeModal()">
  <div style="background:#0a0d18;border:1px solid rgba(255,255,255,0.1);border-radius:20px;padding:36px;max-width:480px;width:90%;position:relative;">
    <button onclick="closeUpgradeModal()" style="position:absolute;top:14px;right:14px;background:none;border:none;color:#6b7280;font-size:20px;cursor:pointer;line-height:1;">?</button>
    <div id="upgrade-modal-content"></div>
  </div>
</div>

<!-- LOCKED FEATURE MODAL -->
<div id="locked-modal" style="display:none;position:fixed;inset:0;z-index:1000;background:rgba(4,6,10,0.92);backdrop-filter:blur(10px);align-items:center;justify-content:center;" onclick="if(event.target===this)closeLockedModal()">
  <div style="background:#0a0d18;border:1px solid rgba(255,255,255,0.1);border-radius:20px;padding:36px;max-width:420px;width:90%;position:relative;text-align:center;">
    <button onclick="closeLockedModal()" style="position:absolute;top:14px;right:14px;background:none;border:none;color:#6b7280;font-size:20px;cursor:pointer;line-height:1;">?</button>
    <div style="font-size:40px;margin-bottom:16px;">??</div>
    <h2 id="locked-modal-title" style="font-family:'Clash Display',sans-serif;font-size:1.3rem;font-weight:700;margin-bottom:10px;color:#eef0f6;"></h2>
    <p id="locked-modal-desc" style="color:#6b7280;font-size:.88rem;line-height:1.6;margin-bottom:24px;"></p>
    <a id="locked-modal-cta-link" href="/register" style="display:block;text-decoration:none;margin-bottom:10px;">
      <button id="locked-modal-cta" style="width:100%;padding:12px;background:#00f5a0;border:none;border-radius:10px;color:#04060a;font-family:'Clash Display',sans-serif;font-weight:700;font-size:.9rem;cursor:pointer;"></button>
    </a>
    <button onclick="closeLockedModal()" style="width:100%;padding:10px;background:none;border:1px solid rgba(255,255,255,0.08);border-radius:10px;color:#6b7280;font-size:.85rem;cursor:pointer;font-family:'Epilogue',sans-serif;">Maybe later</button>
  </div>
</div>

<!-- SHARE PANEL — Multi-Channel Social Grid -->
<div id="shareModal" style="display:none;position:fixed;inset:0;z-index:1000;background:rgba(4,6,10,0.92);backdrop-filter:blur(10px);align-items:center;justify-content:center;padding:20px;" onclick="if(event.target===this)closeSharePanel()">
  <div style="background:#0a0d18;border:1px solid rgba(255,255,255,0.1);border-radius:20px;padding:28px;max-width:440px;width:100%;position:relative;">
    <button onclick="closeSharePanel()" style="position:absolute;top:14px;right:14px;background:none;border:none;color:#6b7280;font-size:20px;cursor:pointer;line-height:1;">✕</button>

    <h2 style="font-family:'Clash Display',sans-serif;font-size:1.2rem;font-weight:700;margin-bottom:6px;">Share Files</h2>
    <p style="color:#6b7280;font-size:.82rem;margin-bottom:20px;">Choose how you want to share</p>

    <!-- File previews -->
    <div id="spFilesPreview" style="display:flex;gap:8px;overflow-x:auto;padding-bottom:10px;margin-bottom:20px;"></div>

    <!-- 3-column share grid -->
    <div class="share-grid">
      <!-- WhatsApp -->
      <button onclick="shareViaWhatsApp()" class="share-grid-btn btn-wa">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
        WhatsApp
      </button>

      <!-- LinkedIn -->
      <button onclick="shareViaLinkedIn()" class="share-grid-btn btn-li">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.779-1.75-1.75s.784-1.75 1.75-1.75 1.75.779 1.75 1.75-.784 1.75-1.75 1.75zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
        LinkedIn
      </button>

      <!-- Twitter / X -->
      <button onclick="shareViaTwitter()" class="share-grid-btn btn-tw">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
        Twitter / X
      </button>

      <!-- Telegram -->
      <button onclick="shareViaTelegram()" class="share-grid-btn btn-tg">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.16.16-.295.295-.605.295l.213-3.053 5.56-5.023c.24-.213-.054-.33-.373-.12l-6.87 4.326-2.96-.924c-.643-.204-.657-.643.136-.953l11.57-4.46c.536-.194.997.12.797.94z"/></svg>
        Telegram
      </button>

      <!-- Email -->
      <button onclick="showEmailCompose()" class="share-grid-btn btn-ml">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
        Email
      </button>

      <!-- Copy Link -->
      <button onclick="copyShareLinkDirect()" class="share-grid-btn btn-cp">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
        Copy Link
      </button>
    </div>

    <!-- Email compose panel -->
    <div id="emailCompose" style="display:none;margin-top:16px;border-top:1px solid rgba(255,255,255,.08);padding-top:16px;">
      <div style="font-size:.75rem;color:#6b7280;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px;">Recipient Email</div>
      <input type="email" id="emailTo" placeholder="recipient@example.com" style="width:100%;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:8px;padding:10px 12px;color:#eef0f6;font-size:.85rem;outline:none;font-family:'Epilogue',sans-serif;margin-bottom:10px;"/>
      <div style="font-size:.75rem;color:#6b7280;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px;">Personal Message (optional)</div>
      <textarea id="emailMsg" placeholder="Add a message..." style="width:100%;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:8px;padding:10px 12px;color:#eef0f6;font-size:.85rem;outline:none;font-family:'Epilogue',sans-serif;resize:none;height:80px;margin-bottom:12px;"></textarea>
      <button onclick="sendShareEmail()" id="emailSendBtn" style="width:100%;padding:12px;background:#00f5a0;border:none;border-radius:10px;color:#04060a;font-family:'Clash Display',sans-serif;font-weight:700;font-size:.9rem;cursor:pointer;">Send Email</button>
      <div id="emailStatus" style="display:none;margin-top:10px;padding:10px;border-radius:8px;font-size:.82rem;text-align:center;"></div>
    </div>
  </div>
</div>

<script>
// ─── SHARE PANEL ─────────────────────────────────────────────────────────────
let spFiles = [];
let spShareUrl = '';

async function openSharePanel(files) {
  spFiles = files || [];
  spShareUrl = '';

  document.getElementById('emailCompose').style.display = 'none';
  document.getElementById('emailTo').value = '';
  document.getElementById('emailMsg').value = '';
  document.getElementById('emailStatus').style.display = 'none';

  // File previews
  const preview = document.getElementById('spFilesPreview');
  preview.innerHTML = spFiles.map(f => {
    const thumb = f.thumbUrl
      ? '<img src="' + f.thumbUrl + '" style="width:100%;height:100%;object-fit:cover;border-radius:8px;" loading="lazy"/>'
      : '<i data-lucide="' + getFileIco(f.name) + '" style="width:32px;height:32px;color:var(--muted);stroke-width:1.5;"></i>';
    return '<div style="flex-shrink:0;text-align:center;width:72px;">'
      + '<div style="width:72px;height:72px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:10px;display:flex;align-items:center;justify-content:center;overflow:hidden;margin-bottom:4px;">' + thumb + '</div>'
      + '<div style="font-size:.62rem;color:#6b7280;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">' + escHtml(shortName(f.name)) + '</div>'
      + '</div>';
  }).join('');
  lucide.createIcons();

  // ── Preload file blobs for native sharing ──────────────────────────────
  // We must do this BEFORE the user clicks WhatsApp so the Web Share API
  // happens synchronously (otherwise mobile browsers block it).
  spFiles.forEach(f => {
    if (!f.fileRef && !f.preloadedBlob) {
      const src = (f.objectUrl && f.objectUrl.startsWith('blob:')) ? f.objectUrl : 
                  (f.downloadUrl ? f.downloadUrl + (f.downloadUrl.includes('?') ? '&download=1' : '?download=1') : null);
      if (src) {
        fetch(src).then(r => r.blob()).then(b => {
          f.preloadedBlob = b;
        }).catch(e => console.warn('Prefetch error:', e));
      }
    }
  });

  // Generate share link in background
  try {
    const fileMeta = spFiles.map(f => ({ id: f.id, name: f.name, size: f.size, type: f.type || f.category }));
    const res = await fetch('/api/share-codes/generate', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '' },
      body: JSON.stringify({ file_meta: fileMeta, file_ids: spFiles.map(f => f.id), expires_in: 10080, allow_download: true })
    });
    const data = await res.json();
    if (data.url) spShareUrl = data.url;
  } catch(e) {
    spShareUrl = window.location.origin + '/share';
  }

  document.getElementById('shareModal').style.display = 'flex';
}

function closeSharePanel() {
  document.getElementById('shareModal').style.display = 'none';
}

function getShareMessage(url) {
  const count = spFiles.length;
  const senderName = '{{ session("user_name", "Me") }}';
  if (count === 1) {
    const f = spFiles[0];
    const emoji = f.category === 'photo' ? '\uD83D\uDDBC\uFE0F' : f.category === 'video' ? '\uD83C\uDFAC' : f.category === 'music' ? '\uD83C\uDFB5' : '\uD83D\uDCC4';
    return emoji + ' *' + f.name + '*\n'
        + senderName + ' shared this securely with you via Keeption Vault.\n\n'
        + '👉 *Paste this link in your browser to view or download:*\n'
        + url;
  } else {
    return '\uD83D\uDCE6 *' + count + ' files from ' + senderName + '*\n\n'
        + spFiles.map(f => {
            const e = f.category === 'photo' ? '\uD83D\uDDBC\uFE0F' : f.category === 'video' ? '\uD83C\uDFAC' : f.category === 'music' ? '\uD83C\uDFB5' : '\uD83D\uDCC4';
            return e + ' ' + f.name;
          }).join('\n') + '\n\n'
        + '👉 *Paste this link in your browser to view or download:*\n'
        + url;
  }
}

async function shareViaWhatsApp() {
  const url  = spShareUrl || window.location.origin + '/share';
  const file = spFiles[0];
  const msg  = getShareMessage(url);

  // Helper — open WhatsApp with text link (fallback)
  function waLink() {
    const waUrl = 'https://wa.me/?text=' + encodeURIComponent(msg);
    const newWin = window.open(waUrl, '_blank');
    // If popup blocked (e.g. lost user gesture after async fetch), redirect current page
    if (!newWin || newWin.closed || typeof newWin.closed === 'undefined') {
      window.location.href = waUrl;
    }
  }

  // No Web Share API (desktop browsers) → open link immediately
  if (!navigator.canShare || !file) { waLink(); return; }

  // ── Mobile: try native file share for ALL types (image/video/audio/doc) ─
  try {
    let fileObj = null;
    let mime = (file.type && file.type.toLowerCase() !== 'file') ? file.type : null;

    // Source 1 — original File reference from drag & drop
    if (file.fileRef instanceof File) {
      fileObj = file.fileRef;
    } 
    // Source 2 — pre-fetched blob (fetched when share panel opened)
    else if (file.preloadedBlob) {
      const blob = file.preloadedBlob;
      const bestMime = (blob.type && blob.type !== 'application/octet-stream') ? blob.type : (mime || 'application/octet-stream');
      fileObj = new File([blob], file.name, { type: bestMime });
    }

    // Share the actual file — WhatsApp shows native card for ALL types
    if (fileObj && navigator.canShare({ files: [fileObj] })) {
      await navigator.share({
        files: [fileObj],
        title: file.name,
        text:  msg,
      });
      return; // ✅ success — native WhatsApp card shown
    }

    // If prefetch hasn't finished, or file not shareable
    waLink();

  } catch(e) {
    if (e.name === 'AbortError') return; // user dismissed share sheet
    console.warn('shareViaWhatsApp error:', e);
    waLink(); // last resort
  }
}

function shareViaLinkedIn() {
  const url = spShareUrl || window.location.origin + '/share';
  const liUrl = 'https://www.linkedin.com/sharing/share-offsite/?url=' + encodeURIComponent(url);
  window.open(liUrl, '_blank');
}

function shareViaTwitter() {
  const url = spShareUrl || window.location.origin + '/share';
  const msg = getShareMessage(url);
  const twUrl = 'https://twitter.com/intent/tweet?text=' + encodeURIComponent(msg);
  window.open(twUrl, '_blank');
}

function shareViaTelegram() {
  const url = spShareUrl || window.location.origin + '/share';
  const msg = getShareMessage(url);
  const tgUrl = 'https://t.me/share/url?url=' + encodeURIComponent(url) + '&text=' + encodeURIComponent(msg);
  window.open(tgUrl, '_blank');
}

function showEmailCompose() {
  const el = document.getElementById('emailCompose');
  el.style.display = el.style.display === 'none' ? 'block' : 'none';
  if (el.style.display === 'block') {
    setTimeout(() => document.getElementById('emailTo').focus(), 100);
  }
}

async function sendShareEmail() {
  const to  = document.getElementById('emailTo').value.trim();
  const msg = document.getElementById('emailMsg').value.trim();
  const btn = document.getElementById('emailSendBtn');
  const status = document.getElementById('emailStatus');

  if (!to || !to.includes('@')) {
    status.style.display = 'block';
    status.style.background = 'rgba(255,68,68,.1)';
    status.style.border = '1px solid rgba(255,68,68,.3)';
    status.style.color = '#ff4444';
    status.textContent = 'Please enter a valid email address.';
    return;
  }

  btn.disabled = true;
  btn.textContent = 'Sending...';

  try {
    const res = await fetch('/api/share/send-email', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '' },
      body: JSON.stringify({
        to:        to,
        message:   msg,
        share_url: spShareUrl,
        files:     spFiles.map(f => ({ name: f.name, size: f.size, type: f.type || f.category })),
        sender:    '{{ session("user_name", "Someone") }}'
      })
    });
    const data = await res.json();

    status.style.display = 'block';
    if (res.ok) {
      status.style.background = 'rgba(0,245,160,.1)';
      status.style.border = '1px solid rgba(0,245,160,.3)';
      status.style.color = '#00f5a0';
      status.textContent = '✓ Email sent to ' + to;
      btn.textContent = 'Sent!';
      setTimeout(() => closeSharePanel(), 2000);
    } else {
      status.style.background = 'rgba(255,68,68,.1)';
      status.style.border = '1px solid rgba(255,68,68,.3)';
      status.style.color = '#ff4444';
      status.textContent = data.error || 'Failed to send email.';
      btn.disabled = false;
      btn.textContent = 'Send Email';
    }
  } catch(e) {
    status.style.display = 'block';
    status.style.background = 'rgba(255,68,68,.1)';
    status.style.border = '1px solid rgba(255,68,68,.3)';
    status.style.color = '#ff4444';
    status.textContent = 'Network error. Please try again.';
    btn.disabled = false;
    btn.textContent = 'Send Email';
  }
}

function copyShareLinkDirect() {
  const url = spShareUrl || window.location.origin + '/share';
  navigator.clipboard?.writeText(url).then(() => {
    showToast('Link copied to clipboard', 'success');
    closeSharePanel();
  });
}

// ============================================================================
// ==================== TEAM & AUDIT TRAIL FUNCTIONALITY ====================
// ============================================================================
let teamState = { members: [], seats: 1, teams_name: 'My Team', plan: 'free' };
let auditLogs = [];
let pendingInviteAfterSeatPurchase = null; // Store pending invite info

// Register page configuration for team files folder navigation
PAGE_CATEGORY['page-team-files'] = null;
PAGE_GRID['page-team-files'] = 'teamFilesGrid';
PAGE_EMPTY['page-team-files'] = 'teamFilesEmpty';
folderNav['page-team-files'] = { stack: [] };

// Initialize team features on startup
document.addEventListener('DOMContentLoaded', () => {
  if (USER_PLAN === 'teams') {
    // Reveal teams section in sidebar
    const teamsSection = document.getElementById('sb-teams-section');
    if (teamsSection) teamsSection.style.display = 'block';
    
    // Fetch real team members & audit logs
    loadTeamData();
    loadAuditLogs();
  }
});

async function loadTeamData() {
  try {
    const res = await fetch('/api/team/members');
    if (!res.ok) return;
    const data = await res.json();
    
    teamState = data;
    
    // Update dashboard labels
    const countLabel = document.getElementById('teamMemberCount');
    if (countLabel) {
      const activeCount = teamState.members.filter(m => m.status === 'active').length;
      countLabel.textContent = `${activeCount} active member${activeCount !== 1 ? 's' : ''} · ${teamState.seats} seat${teamState.seats !== 1 ? 's' : ''} total`;
    }
    
    // Update Admin dashboard numbers
    const adminStatMembers = document.getElementById('adminStatMembers');
    if (adminStatMembers) adminStatMembers.textContent = teamState.members.length;
    
    const adminSeats = document.getElementById('adminSeats');
    if (adminSeats) adminSeats.textContent = teamState.seats;
    
    const adminMonthlyTotal = document.querySelector('#page-admin div[style*="Billing & Subscription"] span[style*="font-weight:600"]');
    if (adminMonthlyTotal) {
      adminMonthlyTotal.textContent = `$${(teamState.seats * 8).toFixed(2)}`;
    }
    
    const adminOverviewFill = document.getElementById('adminOverviewFill');
    if (adminOverviewFill) {
      const fillPercent = Math.min(100, (teamState.members.length / teamState.seats) * 100);
      adminOverviewFill.style.width = `${fillPercent}%`;
    }
    
    // Update team files usage info
    const teamBannerSub = document.getElementById('teamBannerSub');
    if (teamBannerSub) {
      const teamFilesCount = vault.files.filter(f => f.folderId === 'page-team-files' || (vault.folders.find(fo => fo.id === f.folderId && fo.page === 'page-team-files'))).length;
      const teamFilesSize = vault.files.filter(f => f.folderId === 'page-team-files' || (vault.folders.find(fo => fo.id === f.folderId && fo.page === 'page-team-files'))).reduce((acc, f) => acc + f.size, 0);
      teamBannerSub.textContent = `${teamState.members.filter(m => m.status === 'active').length} member${teamState.members.filter(m => m.status === 'active').length !== 1 ? 's' : ''} · ${formatSize(teamFilesSize)} of 500 GB used`;
      
      const adminStatFiles = document.getElementById('adminStatFiles');
      if (adminStatFiles) adminStatFiles.textContent = teamFilesCount;
      
      const adminStatStorage = document.getElementById('adminStatStorage');
      if (adminStatStorage) adminStatStorage.textContent = formatSize(teamFilesSize);
      
      const adminStorUsed = document.getElementById('adminStorUsed');
      if (adminStorUsed) adminStorUsed.textContent = formatSize(teamFilesSize);
      
      const teamBannerFill = document.getElementById('teamBannerFill');
      if (teamBannerFill) {
        const filePercent = Math.min(100, (teamFilesSize / (500 * 1024**3)) * 100);
        teamBannerFill.style.width = `${filePercent}%`;
      }
      
      const adminStorFill = document.getElementById('adminStorFill');
      if (adminStorFill) {
        const filePercent = Math.min(100, (teamFilesSize / (500 * 1024**3)) * 100);
        adminStorFill.style.width = `${filePercent}%`;
      }
    }

    renderTeamMembersGrid();
    renderAdminMembersPanel();
    renderTeamPresenceAvatars();
    renderStoragePoolBars();
  } catch(e) {
    console.error('Error loading team data:', e);
  }
}

function renderTeamMembersGrid() {
  const grid = document.getElementById('teamMembersGrid');
  if (!grid) return;
  
  if (teamState.members.length === 0) {
    grid.innerHTML = `
      <div class="empty-state" style="min-height:200px;grid-column:1/-1;">
        <div class="es-illo"><i data-lucide="users" style="width:48px;height:48px;color:var(--muted);stroke-width:1;"></i></div>
        <div class="es-title">No team members yet</div>
        <div class="es-sub">Invite your first team member to get started.</div>
        <div class="es-actions"><button class="es-btn primary" onclick="openInviteModal()"><i data-lucide="user-plus" class="lucide"></i> Invite Member</button></div>
      </div>
    `;
    lucide.createIcons();
    return;
  }
  
  grid.innerHTML = teamState.members.map(m => {
    const isPending = m.status === 'pending';
    const isOwner = m.is_owner;
    const initial = m.name ? m.name.charAt(0).toUpperCase() : '?';
    const avatar = m.avatar_url 
      ? `<img src="${m.avatar_url}" style="width:44px;height:44px;border-radius:12px;object-fit:cover;"/>`
      : `<div style="width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,var(--surface3),var(--surface));border:1px solid var(--border);display:flex;align-items:center;justify-content:center;font-family:'Clash Display',sans-serif;font-weight:700;font-size:1.1rem;color:var(--accent);">${initial}</div>`;
      
    return `
      <div style="background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:16px;display:flex;flex-direction:column;gap:12px;position:relative;">
        <div style="display:flex;align-items:center;gap:12px;">
          ${avatar}
          <div style="flex:1;min-width:0;">
            <div style="font-weight:600;font-size:.88rem;color:var(--text);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${escHtml(m.name || m.email.split('@')[0])}</div>
            <div style="font-size:.72rem;color:var(--muted);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${escHtml(m.email)}</div>
          </div>
          ${isPending ? '<span style="font-size:.65rem;background:rgba(245,158,11,0.1);color:#f59e0b;border-radius:6px;padding:2px 6px;font-weight:600;text-transform:uppercase;letter-spacing:.02em;">Pending</span>' : ''}
        </div>
        <div style="display:flex;justify-content:space-between;align-items:center;border-top:1px solid var(--border);padding-top:10px;margin-top:4px;">
          <div style="font-size:.72rem;color:var(--muted);">Role: <strong style="color:var(--text);text-transform:capitalize;">${m.role}</strong></div>
          <div style="display:flex;gap:6px;">
            ${!isOwner ? `
              <button class="bulk-btn" onclick="openRoleChangeModal('${m.id}', '${m.role}')" style="padding:4px 8px;font-size:.7rem;"><i data-lucide="shield" style="width:12px;height:12px;margin-right:2px;"></i> Role</button>
              <button class="bulk-btn danger" onclick="confirmRemoveMember('${m.id}', '${escHtml(m.email)}')" style="padding:4px 8px;font-size:.7rem;"><i data-lucide="trash-2" style="width:12px;height:12px;margin-right:2px;"></i> Remove</button>
            ` : '<span style="font-size:.7rem;color:var(--muted);font-style:italic;">Primary Admin</span>'}
          </div>
        </div>
      </div>
    `;
  }).join('');
  
  lucide.createIcons();
}

function renderAdminMembersPanel() {
  const container = document.getElementById('adminMembersList');
  if (!container) return;
  
  if (teamState.members.length === 0) {
    container.innerHTML = '<div style="color:var(--muted);font-size:.82rem;">No members yet.</div>';
    return;
  }
  
  container.innerHTML = `
    <div style="display:flex;flex-direction:column;gap:8px;max-height:220px;overflow-y:auto;padding-right:4px;">
      ${teamState.members.map(m => {
        const isOwner = m.is_owner;
        return `
          <div style="display:flex;align-items:center;justify-content:space-between;background:var(--surface2);border-radius:10px;padding:8px 12px;border:1px solid var(--border);gap:8px;">
            <div style="flex:1;min-width:0;">
              <div style="font-size:.82rem;font-weight:600;color:var(--text);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${escHtml(m.name || m.email.split('@')[0])}</div>
              <div style="font-size:.7rem;color:var(--muted);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${escHtml(m.email)}</div>
            </div>
            <div style="display:flex;align-items:center;gap:8px;">
              <span style="font-size:.68rem;background:rgba(255,255,255,.03);border:1px solid var(--border);border-radius:6px;padding:2px 8px;color:var(--text);text-transform:capitalize;">${m.role}</span>
              ${!isOwner ? `
                <button onclick="confirmRemoveMember('${m.id}', '${escHtml(m.email)}')" style="background:none;border:none;color:var(--red);cursor:pointer;padding:4px;display:flex;"><i data-lucide="trash-2" style="width:14px;height:14px;"></i></button>
              ` : ''}
            </div>
          </div>
        `;
      }).join('')}
    </div>
  `;
  lucide.createIcons();
}

function renderTeamPresenceAvatars() {
  const presence = document.getElementById('teamPresence');
  if (!presence) return;
  
  // Show random dots/avatars of active team members working in this workspace
  const activeMembers = teamState.members.filter(m => m.status === 'active').slice(0, 4);
  
  let html = '';
  activeMembers.forEach((m, idx) => {
    const initial = m.name ? m.name.charAt(0).toUpperCase() : '?';
    const bgColors = ['#00f5a0', '#00d4ff', '#ff6b9d', '#ffd166'];
    const border = idx > 0 ? 'margin-left:-8px;' : '';
    html += `<div style="width:26px;height:26px;border-radius:50%;background:${bgColors[idx % bgColors.length]};color:#04060a;border:2px solid var(--surface);display:flex;align-items:center;justify-content:center;font-size:.65rem;font-weight:700;${border}cursor:pointer;" title="${escHtml(m.name || m.email)} is online">${initial}</div>`;
  });
  
  if (teamState.members.length > 4) {
    html += `<div style="width:26px;height:26px;border-radius:50%;background:var(--surface3);color:var(--text);border:2px solid var(--surface);display:flex;align-items:center;justify-content:center;font-size:.65rem;font-weight:700;margin-left:-8px;cursor:pointer;">+${teamState.members.length - 4}</div>`;
  }
  
  presence.innerHTML = html;
}

function renderStoragePoolBars() {
  const container = document.getElementById('adminMemberBars');
  if (!container) return;
  
  // Storage per active member (simulated distribution of dynamic files upload)
  const activeMembers = teamState.members.filter(m => m.status === 'active');
  if (activeMembers.length === 0) {
    container.innerHTML = '<div style="color:var(--muted);font-size:.82rem;">No usage data yet.</div>';
    return;
  }
  
  // Distribute team files total size randomly or evenly for mock view
  const teamFilesSize = vault.files.filter(f => f.folderId === 'page-team-files' || (vault.folders.find(fo => fo.id === f.folderId && fo.page === 'page-team-files'))).reduce((acc, f) => acc + f.size, 0);
  
  let totalGB = 500;
  
  let html = '<div style="display:flex;flex-direction:column;gap:10px;margin-top:10px;">';
  activeMembers.forEach((m, idx) => {
    // Generate static but member-specific share proportions
    const shareFraction = idx === 0 ? 0.6 : (idx === 1 ? 0.3 : 0.1);
    const memberBytes = Math.floor(teamFilesSize * shareFraction);
    const memberPercent = Math.min(100, (memberBytes / (totalGB * 1024**3)) * 100);
    
    html += `
      <div>
        <div style="display:flex;justify-content:space-between;align-items:center;font-size:.72rem;margin-bottom:3px;">
          <span style="color:var(--text);">${escHtml(m.name || m.email.split('@')[0])}</span>
          <span style="color:var(--muted);">${formatSize(memberBytes)}</span>
        </div>
        <div style="height:4px;background:rgba(255,255,255,0.03);border-radius:99px;overflow:hidden;">
          <div style="height:100%;width:${Math.max(1, memberPercent)}%;background:linear-gradient(90deg,var(--accent2),var(--accent));border-radius:99px;"></div>
        </div>
      </div>
    `;
  });
  html += '</div>';
  container.innerHTML = html;
}

// Invite modal popup
function openInviteModal() {
  const inviteEmail = document.getElementById('inviteEmail');
  if (inviteEmail) inviteEmail.focus();
  else {
    // Re-route click to modal or input focus
    navTo('page-admin');
    const input = document.getElementById('inviteEmail');
    if (input) setTimeout(() => input.focus(), 150);
  }
}

async function inviteMember() {
  const emailInput = document.getElementById('inviteEmail');
  const roleSelect = document.getElementById('inviteRole');
  if (!emailInput) return;
  
  const email = emailInput.value.trim();
  const role = roleSelect ? roleSelect.value : 'editor';
  
  if (!email || !email.includes('@')) {
    showToast('Please enter a valid email address.', 'error');
    return;
  }
  
  try {
    const res = await fetch('/api/team/invite', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || ''
      },
      body: JSON.stringify({ email, role })
    });
    
    const data = await res.json();
    
    if (res.ok) {
      showToast(`Invitation sent to ${email}`, 'success');
      emailInput.value = '';
      loadTeamData();
      loadAuditLogs();
    } else if (res.status === 403 && data.require_seat) {
      // Out of seats! Trigger Stripe simulated checkout
      pendingInviteAfterSeatPurchase = { email, role };
      openStripeCheckoutModal(data.message);
    } else {
      showToast(data.error || 'Failed to send invitation.', 'error');
    }
  } catch(e) {
    showToast('Network error while inviting team member.', 'error');
  }
}

// Role Changing Modal
function openRoleChangeModal(memberId, currentRole) {
  const role = prompt(`Change member role to: "admin", "editor", or "viewer"?`, currentRole);
  if (!role) return;
  
  const cleanRole = role.trim().toLowerCase();
  if (!['admin','editor','viewer'].includes(cleanRole)) {
    showToast('Invalid role. Must be admin, editor, or viewer.', 'error');
    return;
  }
  
  changeMemberRole(memberId, cleanRole);
}

async function changeMemberRole(id, role) {
  try {
    const res = await fetch('/api/team/role', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || ''
      },
      body: JSON.stringify({ id, role })
    });
    
    if (res.ok) {
      showToast('Role updated successfully', 'success');
      loadTeamData();
      loadAuditLogs();
    } else {
      const data = await res.json();
      showToast(data.error || 'Failed to update role.', 'error');
    }
  } catch(e) {
    showToast('Error updating role.', 'error');
  }
}

function confirmRemoveMember(id, email) {
  if (confirm(`Are you absolutely sure you want to remove ${email} from your team vault? They will instantly lose access to all shared files.`)) {
    removeTeamMember(id);
  }
}

async function removeTeamMember(id) {
  try {
    const res = await fetch(`/api/team/remove/${id}`, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || ''
      }
    });
    
    if (res.ok) {
      showToast('Member removed from team', 'success');
      loadTeamData();
      loadAuditLogs();
    } else {
      const data = await res.json();
      showToast(data.error || 'Failed to remove member.', 'error');
    }
  } catch(e) {
    showToast('Error removing team member.', 'error');
  }
}

async function saveTeamSettings() {
  const teamNameInput = document.querySelector('#page-admin input[value*="Team"]');
  if (!teamNameInput) return;
  
  const teams_name = teamNameInput.value.trim();
  if (!teams_name) return;
  
  try {
    const res = await fetch('/api/team/update-settings', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || ''
      },
      body: JSON.stringify({ teams_name })
    });
    
    if (res.ok) {
      showToast('Team settings updated', 'success');
      
      // Update team visual banners
      const bTitle = document.querySelector('#page-team-files div[style*="Clash Display"][style*="font-weight:700"]');
      if (bTitle) bTitle.textContent = teams_name;
      
      loadTeamData();
      loadAuditLogs();
    } else {
      const data = await res.json();
      showToast(data.error || 'Failed to save settings.', 'error');
    }
  } catch(e) {
    showToast('Error updating team settings.', 'error');
  }
}

// ─── AUDIT TRAIL LOGGING & VISUALS ──────────────────────────────────────────
async function loadAuditLogs() {
  try {
    const memberFilter = document.getElementById('auditFilterMember').value;
    const actionFilter = document.getElementById('auditFilterAction').value;
    const searchVal = document.getElementById('auditSearch').value.trim();
    
    let url = `/api/team/audit?search=${encodeURIComponent(searchVal)}&action=${actionFilter}`;
    if (memberFilter) url += `&member=${encodeURIComponent(memberFilter)}`;
    
    const res = await fetch(url);
    if (!res.ok) return;
    
    auditLogs = await res.json();
    
    // Dynamically build/update Member filter list
    updateAuditMemberFilterOptions();
    
    renderAuditLogsList();
  } catch(e) {
    console.error('Error loading audit logs:', e);
  }
}

function updateAuditMemberFilterOptions() {
  const filter = document.getElementById('auditFilterMember');
  if (!filter) return;
  
  const currentVal = filter.value;
  
  // Extract unique active names in logs
  const members = [...new Set(auditLogs.map(l => l.user_name))];
  
  let html = '<option value="">All Members</option>';
  members.forEach(m => {
    html += `<option value="${escHtml(m)}" ${m === currentVal ? 'selected' : ''}>${escHtml(m)}</option>`;
  });
  
  filter.innerHTML = html;
}

function renderAuditLogsList() {
  const list = document.getElementById('auditList');
  if (!list) return;
  
  if (auditLogs.length === 0) {
    list.innerHTML = `
      <div class="empty-state" style="min-height:200px;">
        <div class="es-illo"><i data-lucide="scroll-text" style="width:48px;height:48px;color:var(--muted);stroke-width:1;"></i></div>
        <div class="es-title">No activity yet</div>
        <div class="es-sub">Team actions will appear here as members use the workspace.</div>
      </div>
    `;
    lucide.createIcons();
    return;
  }
  
  // Actions icons map
  const icoMap = {
    upload:          '<i data-lucide="upload" style="color:var(--accent);width:14px;height:14px;"></i>',
    download:        '<i data-lucide="download" style="color:var(--accent2);width:14px;height:14px;"></i>',
    delete:          '<i data-lucide="trash-2" style="color:var(--red);width:14px;height:14px;"></i>',
    rename:          '<i data-lucide="pencil" style="color:var(--gold);width:14px;height:14px;"></i>',
    move:            '<i data-lucide="folder-input" style="color:var(--accent3);width:14px;height:14px;"></i>',
    invite:          '<i data-lucide="user-plus" style="color:#fb923c;width:14px;height:14px;"></i>',
    role_change:     '<i data-lucide="shield" style="color:#a78bfa;width:14px;height:14px;"></i>',
    settings_change: '<i data-lucide="settings" style="color:var(--text);width:14px;height:14px;"></i>',
    billing_change:  '<i data-lucide="credit-card" style="color:#00f5a0;width:14px;height:14px;"></i>',
    login:           '<i data-lucide="key" style="color:#6b7280;width:14px;height:14px;"></i>',
    register:        '<i data-lucide="user" style="color:#ffd166;width:14px;height:14px;"></i>',
  };
  
  list.innerHTML = auditLogs.map(l => {
    const icon = icoMap[l.action] || '<i data-lucide="activity" style="width:14px;height:14px;"></i>';
    return `
      <div class="audit-grid-row" style="padding:12px 16px;border-bottom:1px solid var(--border);font-size:.8rem;color:var(--text);">
        <div class="audit-col-ico" style="display:flex;align-items:center;justify-content:center;width:24px;height:24px;border-radius:6px;background:rgba(255,255,255,0.03);border:1px solid var(--border);">${icon}</div>
        <div class="audit-col-details" style="font-weight:500;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="${escHtml(l.details)}">${escHtml(l.details)}</div>
        <div class="audit-col-member" style="color:var(--muted);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${escHtml(l.user_name)}</div>
        <div class="audit-col-date" style="color:var(--muted);font-size:.75rem;">${l.date}</div>
        <div class="audit-col-device" style="font-size:.72rem;"><span style="background:var(--surface2);border:1px solid var(--border);border-radius:4px;padding:1px 6px;color:var(--muted);">${escHtml(l.device)}</span></div>
      </div>
    `;
  }).join('');
  
  lucide.createIcons();
}

function exportAuditCSV() {
  window.open('/api/team/audit/export', '_blank');
}

// ─── STRIPE CHECKOUT SIMULATED MODAL ─────────────────────────────────────────
function openStripeCheckoutModal(msgText) {
  const container = document.getElementById('stripeCheckoutModal');
  if (!container) return;
  
  const details = document.getElementById('stripeBillingDetails');
  if (details && pendingInviteAfterSeatPurchase) {
    details.innerHTML = `
      <div style="font-size:.82rem;color:var(--muted);line-height:1.8;">
        Item: <strong style="color:var(--text)">Additional Seat (Teams)</strong><br/>
        Recipient: <strong style="color:var(--text)">${pendingInviteAfterSeatPurchase.email}</strong><br/>
        Price: <strong style="color:var(--accent)">$8.00 / month</strong>
      </div>
    `;
  } else if (details) {
    details.innerHTML = `
      <div style="font-size:.82rem;color:var(--muted);line-height:1.8;">
        Item: <strong style="color:var(--text)">Keeption Teams Subscription Upgrade</strong><br/>
        Seats: <strong style="color:var(--text)">1 user seat</strong><br/>
        Price: <strong style="color:var(--accent)">$8.00 / month</strong>
      </div>
    `;
  }
  
  const textElem = document.getElementById('stripeCheckoutText');
  if (textElem) textElem.textContent = msgText || 'A team member seat costs $8.00 / month. Unlock workspace sharing and full audit logs.';
  
  // Clear modal inputs
  document.getElementById('stCardNo').value = '';
  document.getElementById('stCardExp').value = '';
  document.getElementById('stCardCvc').value = '';
  document.getElementById('stCardName').value = '';
  document.getElementById('stProgress').style.display = 'none';
  document.getElementById('stSuccess').style.display = 'none';
  document.getElementById('stFormContent').style.display = 'block';
  
  container.style.display = 'flex';
}

function closeStripeCheckoutModal() {
  document.getElementById('stripeCheckoutModal').style.display = 'none';
  pendingInviteAfterSeatPurchase = null;
}

// Format card helper inputs
function formatCardNumber(input) {
  let val = input.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
  let matches = val.match(/\d{4,16}/g);
  let match = matches && matches[0] || '';
  let parts = [];

  for (let i=0, len=match.length; i<len; i+=4) {
    parts.push(match.substring(i, i+4));
  }

  if (parts.length > 0) {
    input.value = parts.join(' ');
  } else {
    input.value = val;
  }
}

function formatCardExpiry(input) {
  let val = input.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
  if (val.length >= 2) {
    input.value = val.substring(0, 2) + ' / ' + val.substring(2, 4);
  } else {
    input.value = val;
  }
}

async function processStripePayment() {
  const cardNo = document.getElementById('stCardNo').value.trim();
  const cardExp = document.getElementById('stCardExp').value.trim();
  const cardCvc = document.getElementById('stCardCvc').value.trim();
  const cardName = document.getElementById('stCardName').value.trim();
  
  if (cardNo.length < 15 || cardExp.length < 5 || cardCvc.length < 3 || !cardName) {
    showToast('Please fill out all card details completely.', 'error');
    return;
  }
  
  const form = document.getElementById('stFormContent');
  const prog = document.getElementById('stProgress');
  const succ = document.getElementById('stSuccess');
  
  form.style.display = 'none';
  prog.style.display = 'block';
  
  // Simulated processing delay
  await new Promise(r => setTimeout(r, 2000));
  
  try {
    // API request to add seat
    const res = await fetch('/api/team/add-seat', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || ''
      }
    });
    
    if (res.ok) {
      prog.style.display = 'none';
      succ.style.display = 'block';
      
      // Upgrade sound or feedback
      showToast('Payment successful — seat added!', 'success');
      
      await new Promise(r => setTimeout(r, 1200));
      
      // Close payment modal
      closeStripeCheckoutModal();
      
      // Auto-retry pending invite if applicable!
      if (pendingInviteAfterSeatPurchase) {
        showToast('Processing original team invite...', 'info');
        const invitedEmail = pendingInviteAfterSeatPurchase.email;
        const invitedRole = pendingInviteAfterSeatPurchase.role;
        pendingInviteAfterSeatPurchase = null; // Clear
        
        // Execute invite post directly
        const inviteRes = await fetch('/api/team/invite', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || ''
          },
          body: JSON.stringify({ email: invitedEmail, role: invitedRole })
        });
        
        if (inviteRes.ok) {
          showToast(`Invitation sent to ${invitedEmail}`, 'success');
        } else {
          showToast('Failed to invite member. Please check available seats.', 'error');
        }
      }
      
      // Refresh views
      loadTeamData();
      loadAuditLogs();
    } else {
      showToast('Payment declined. Please try another card.', 'error');
      form.style.display = 'block';
      prog.style.display = 'none';
    }
  } catch(e) {
    showToast('Failed to complete transaction.', 'error');
    form.style.display = 'block';
    prog.style.display = 'none';
  }
}
</script>

<!-- ─── SHARE CODE WIZARD MODAL (must remain inside <body>) ─────────────────
     This block was accidentally placed outside </html>. Fixed here. -->
<div id="shareCodeModal" style="display:none;position:fixed;inset:0;z-index:1000;background:rgba(4,6,10,0.92);backdrop-filter:blur(10px);-webkit-backdrop-filter:blur(10px);align-items:center;justify-content:center;padding:20px;" onclick="if(event.target===this)closeShareCodeModal()">
  <div style="background:#0a0d18;border:1px solid rgba(255,255,255,0.1);border-radius:20px;padding:32px;max-width:520px;width:100%;position:relative;margin:auto;">
    <button onclick="closeShareCodeModal()" style="position:absolute;top:14px;right:14px;background:none;border:none;color:#6b7280;font-size:20px;cursor:pointer;line-height:1;">✕</button>

    <!-- Step 1: Config -->
    <div id="scStep1">
      <h2 style="font-family:'Clash Display',sans-serif;font-size:1.3rem;font-weight:700;margin-bottom:6px;">Generate Share Code</h2>
      <p style="color:#6b7280;font-size:.82rem;margin-bottom:20px;">Create a secure KV-XXXXXX code to share your files with anyone.</p>

      <div id="scFilesPreview" style="display:flex;gap:10px;overflow-x:auto;padding-bottom:10px;margin-bottom:20px;"></div>

      <!-- Expiry -->
      <div style="margin-bottom:18px;">
        <div style="font-size:.75rem;color:#6b7280;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px;">Expiry</div>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;" id="expiryBtns">
          <button class="sc-exp-btn" data-mins="60" onclick="selectExpiry(60,this)">1 Hour</button>
          <button class="sc-exp-btn" data-mins="360" onclick="selectExpiry(360,this)">6 Hours</button>
          <button class="sc-exp-btn sc-exp-active" data-mins="1440" onclick="selectExpiry(1440,this)">24 Hours</button>
          <button class="sc-exp-btn" data-mins="4320" onclick="selectExpiry(4320,this)">3 Days</button>
          <button class="sc-exp-btn" data-mins="10080" onclick="selectExpiry(10080,this)">7 Days</button>
          <button class="sc-exp-btn" data-mins="43200" onclick="selectExpiry(43200,this)">30 Days</button>
        </div>
        <div id="expiryPreview" style="font-size:.75rem;color:#00f5a0;margin-top:8px;"></div>
      </div>

      <!-- Usage limit -->
      <div style="margin-bottom:18px;">
        <div style="font-size:.75rem;color:#6b7280;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px;">Usage Limit</div>
        <div style="display:flex;align-items:center;gap:12px;">
          <input type="number" id="scMaxUses" min="1" placeholder="Unlimited" style="flex:1;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:8px;padding:9px 12px;color:#eef0f6;font-size:.85rem;outline:none;font-family:'Epilogue',sans-serif;"/>
          <label style="display:flex;align-items:center;gap:6px;font-size:.82rem;color:#6b7280;cursor:pointer;white-space:nowrap;">
            <input type="checkbox" id="scSingleUse" onchange="if(this.checked){document.getElementById('scMaxUses').value=1;}else{document.getElementById('scMaxUses').value='';}"/>
            Single use only
          </label>
        </div>
      </div>

      <!-- Password -->
      <div style="margin-bottom:18px;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
          <div style="font-size:.75rem;color:#6b7280;text-transform:uppercase;letter-spacing:.06em;">Password Protection</div>
          <label style="display:flex;align-items:center;gap:6px;cursor:pointer;">
            <input type="checkbox" id="scPwToggle" onchange="document.getElementById('scPwField').style.display=this.checked?'block':'none'"/>
            <span style="font-size:.78rem;color:#6b7280;">Enable</span>
          </label>
        </div>
        <div id="scPwField" style="display:none;">
          <input type="password" id="scPassword" placeholder="Enter a password for recipients" style="width:100%;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:8px;padding:9px 12px;color:#eef0f6;font-size:.85rem;outline:none;font-family:'Epilogue',sans-serif;"/>
          <div style="font-size:.72rem;color:#f59e0b;margin-top:6px;">This password is not stored by Keeption Vault. Write it down before proceeding.</div>
        </div>
      </div>

      <!-- Permissions -->
      <div style="margin-bottom:20px;">
        <div style="font-size:.75rem;color:#6b7280;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px;">Permissions</div>
        <label style="display:flex;align-items:center;gap:8px;margin-bottom:8px;cursor:pointer;font-size:.83rem;color:#eef0f6;">
          <input type="checkbox" id="scAllowDl" checked/> Allow Download
        </label>
        <label style="display:flex;align-items:center;gap:8px;margin-bottom:8px;cursor:pointer;font-size:.83rem;color:#eef0f6;">
          <input type="checkbox" id="scAllowReshare"/> Allow Re-Share
        </label>
        <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:.83rem;color:{{ $isFree ? '#6b7280' : '#eef0f6' }};" {{ $isFree ? 'onclick="openLockedModal(\'watermarked-share\'); event.preventDefault()"' : '' }}>
          <input type="checkbox" id="scWatermark" {{ $isFree ? 'disabled' : '' }}/> Forensic Watermark (IP & Email overlay) @if($isFree)<span style="font-size:.6rem;background:rgba(0,245,160,0.1);color:#00f5a0;border-radius:4px;padding:1px 5px;margin-left:4px;">PRO</span>@endif
        </label>
      </div>

      <button onclick="generateShareCode()" style="width:100%;padding:13px;background:#00f5a0;border:none;border-radius:10px;color:#04060a;font-family:'Clash Display',sans-serif;font-weight:700;font-size:.95rem;cursor:pointer;">Generate Code</button>
    </div>

    <!-- Step 2: Result -->
    <div id="scStep2" style="display:none;text-align:center;">
      <div style="font-size:14px;margin-bottom:16px;">🔐</div>
      <div style="font-family:'Clash Display',sans-serif;font-size:3rem;font-weight:700;letter-spacing:.1em;color:#00f5a0;margin-bottom:8px;" id="scResultCode"></div>
      <div style="font-size:.78rem;color:#6b7280;margin-bottom:24px;" id="scResultExpiry"></div>
      <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;margin-bottom:16px;">
        <button onclick="copyShareCode()" style="padding:10px;background:rgba(0,245,160,.08);border:1px solid rgba(0,245,160,.2);border-radius:10px;color:#00f5a0;font-size:.82rem;cursor:pointer;font-family:'Epilogue',sans-serif;">📋 Copy Code</button>
        <button onclick="copyShareLink()" style="padding:10px;background:rgba(0,245,160,.08);border:1px solid rgba(0,245,160,.2);border-radius:10px;color:#00f5a0;font-size:.82rem;cursor:pointer;font-family:'Epilogue',sans-serif;">🔗 Copy Link</button>
        <button onclick="shareVia()" style="padding:10px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:10px;color:#eef0f6;font-size:.82rem;cursor:pointer;font-family:'Epilogue',sans-serif;">📤 Share Via</button>
      </div>
      <div id="scQRWrap" style="margin-bottom:16px;"></div>
      <button onclick="closeShareCodeModal()" style="width:100%;padding:10px;background:none;border:1px solid rgba(255,255,255,.08);border-radius:10px;color:#6b7280;font-size:.85rem;cursor:pointer;font-family:'Epilogue',sans-serif;">Done</button>
    </div>
  </div>
</div>

<style>
.sc-exp-btn{padding:8px;border-radius:8px;border:1px solid rgba(255,255,255,.08);background:rgba(255,255,255,.03);color:#6b7280;font-size:.78rem;cursor:pointer;transition:all .15s;font-family:'Epilogue',sans-serif;}
.sc-exp-btn:hover{border-color:rgba(0,245,160,.3);color:#00f5a0;}
.sc-exp-active{border-color:rgba(0,245,160,.4)!important;background:rgba(0,245,160,.08)!important;color:#00f5a0!important;}
</style>

<script>
// ─── SHARE CODE WIZARD ──────────────────────────────────────────────────────
let scFiles = [];
let scExpiryMins = 1440;
let scResultCodeVal = '';
let scResultUrl = '';

function openShareCodeWizard(files) {
  scFiles = files || [];
  scExpiryMins = 1440;
  scResultCodeVal = '';
  scResultUrl = '';

  // Reset form
  document.getElementById('scStep1').style.display = 'block';
  document.getElementById('scStep2').style.display = 'none';
  document.getElementById('scMaxUses').value = '';
  document.getElementById('scSingleUse').checked = false;
  document.getElementById('scPwToggle').checked = false;
  document.getElementById('scPwField').style.display = 'none';
  document.getElementById('scPassword').value = '';
  document.getElementById('scAllowDl').checked = true;
  document.getElementById('scAllowReshare').checked = false;
  if(document.getElementById('scWatermark')) document.getElementById('scWatermark').checked = false;
  document.getElementById('scQRWrap').style.display = 'none';

  // Reset expiry buttons
  document.querySelectorAll('.sc-exp-btn').forEach(b => b.classList.remove('sc-exp-active'));
  document.querySelector('[data-mins="1440"]').classList.add('sc-exp-active');
  updateExpiryPreview(1440);

  // File previews
  const preview = document.getElementById('scFilesPreview');
  preview.innerHTML = scFiles.map(f => {
    return '<div style="flex-shrink:0;text-align:center;width:80px;">'
      + '<div style="width:80px;height:80px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:10px;display:flex;align-items:center;justify-content:center;margin-bottom:4px;"><i data-lucide="' + getFileIco(f.name) + '" style="width:36px;height:36px;color:var(--muted);stroke-width:1.5;"></i></div>'
      + '<div style="font-size:.65rem;color:#6b7280;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">' + f.name + '</div>'
      + '</div>';
  }).join('');
  lucide.createIcons();

  document.getElementById('shareCodeModal').style.display = 'flex';
}

function closeShareCodeModal() {
  document.getElementById('shareCodeModal').style.display = 'none';
}

function selectExpiry(mins, btn) {
  scExpiryMins = mins;
  document.querySelectorAll('.sc-exp-btn').forEach(b => b.classList.remove('sc-exp-active'));
  btn.classList.add('sc-exp-active');
  updateExpiryPreview(mins);
}

function updateExpiryPreview(mins) {
  const d = new Date(Date.now() + mins * 60000);
  const opts = {weekday:'long',year:'numeric',month:'long',day:'numeric',hour:'2-digit',minute:'2-digit'};
  document.getElementById('expiryPreview').textContent = 'Expires: ' + d.toLocaleDateString('en-US', opts);
}

async function generateShareCode() {
  const fileMeta = scFiles.map(f => ({ id: f.id, name: f.name, size: f.size, type: f.type || f.category }));
  const fileIds  = scFiles.map(f => f.id);
  const pw       = document.getElementById('scPwToggle').checked ? document.getElementById('scPassword').value : null;
  const maxUses  = document.getElementById('scMaxUses').value ? parseInt(document.getElementById('scMaxUses').value) : null;

  try {
    const res = await fetch('/api/share-codes/generate', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || ''
      },
      body: JSON.stringify({
        file_meta:      fileMeta,
        file_ids:       fileIds,
        expires_in:     scExpiryMins,
        max_uses:       maxUses,
        password:       pw,
        allow_download: document.getElementById('scAllowDl').checked,
        allow_reshare:  document.getElementById('scAllowReshare').checked,
        self_destruct:  false,
        watermarked:    document.getElementById('scWatermark') ? document.getElementById('scWatermark').checked : false,
      })
    });

    const data = await res.json();
    if (!res.ok) { showToast(data.error || 'Failed to generate code', 'error'); return; }

    scResultCodeVal = data.code;
    scResultUrl     = data.url;

    document.getElementById('scResultCode').textContent = data.code;
    const exp = new Date(data.expires_at);
    document.getElementById('scResultExpiry').textContent = 'Expires ' + exp.toLocaleDateString('en-US',{month:'short',day:'numeric',year:'numeric',hour:'2-digit',minute:'2-digit'});

    document.getElementById('scStep1').style.display = 'none';
    document.getElementById('scStep2').style.display = 'block';

    showToast('Share code generated: ' + data.code, 'success');
    renderQR();
    completeOnboardStep(2);

  } catch(e) {
    showToast('Failed to generate share code. Make sure you are logged in.', 'error');
  }
}

function copyShareCode() {
  const raw = scResultCodeVal.replace('KV-', '');
  navigator.clipboard?.writeText(raw).then(() => showToast('Code copied: ' + scResultCodeVal, 'success'));
}

function copyShareLink() {
  navigator.clipboard?.writeText(scResultUrl).then(() => showToast('Link copied to clipboard', 'success'));
}

function shareVia() {
  const text = 'I am sharing files with you through Keeption Vault. Enter code ' + scResultCodeVal + ' at ' + window.location.origin + '/share to access them securely.';
  if (navigator.share) {
    navigator.share({ title: 'Keeption Vault Share', text: text, url: scResultUrl });
  } else {
    navigator.clipboard?.writeText(text).then(() => showToast('Share message copied to clipboard', 'success'));
  }
}

function renderQR() {
  const wrap = document.getElementById('scQRWrap');
  const shareUrl = window.location.origin + '/share/' + scResultCodeVal;
  const qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&margin=10&data=' + encodeURIComponent(shareUrl);
  wrap.innerHTML = '<img src="' + qrUrl + '" style="width:160px;height:160px;border-radius:12px;display:block;margin:0 auto;background:#fff;" alt="QR Code" onerror="this.parentElement.innerHTML=\'<p style=\\\'color:#6b7280;font-size:.8rem;text-align:center;\\\'>QR requires internet connection</p>\\\'"/>'
    + '<div style="font-size:.72rem;color:#6b7280;margin-top:8px;text-align:center;">' + shareUrl + '</div>'
    + '<button onclick="downloadQR(\'' + encodeURIComponent(shareUrl) + '\')" style="display:block;margin:8px auto 0;padding:6px 16px;background:rgba(0,245,160,.08);border:1px solid rgba(0,245,160,.2);border-radius:8px;color:#00f5a0;font-size:.75rem;cursor:pointer;font-family:\'Epilogue\',sans-serif;">Download QR</button>';
}

function downloadQR(encodedUrl) {
  const url = encodedUrl || encodeURIComponent(window.location.origin + '/share/' + scResultCodeVal);
  const a = document.createElement('a');
  a.href = 'https://api.qrserver.com/v1/create-qr-code/?size=400x400&margin=10&data=' + url;
  a.download = 'keeption-share-' + scResultCodeVal + '.png';
  a.target = '_blank';
  document.body.appendChild(a);
  a.click();
  document.body.removeChild(a);
}
</script>

</body>
</html>
