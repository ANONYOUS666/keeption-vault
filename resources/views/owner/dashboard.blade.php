<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<meta name="theme-color" content="#050810"/>
<title>Command Center — Keeption Vault</title>
<link rel="icon" type="image/png" href="/favicon.png"/>
<link href="https://fonts.googleapis.com/css2?family=Clash+Display:wght@400;500;600;700&family=Epilogue:wght@300;400;500;600&display=swap" rel="stylesheet"/>
<style>
:root{
  --bg:#050810;--surface:#0a0d18;--surface2:#0f1422;--surface3:#161c2e;
  --border:rgba(255,255,255,0.08);--border-glow:rgba(0,245,160,0.2);
  --accent:#00f5a0;--red:#ff4444;--amber:#f59e0b;--blue:#3b82f6;
  --text:#eef0f6;--muted:#6b7280;
  --sidebar-w:240px;
}
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box;}
html,body{height:100%;overflow:hidden;}
body{font-family:'Epilogue',sans-serif;background:var(--bg);color:var(--text);display:flex;}
::-webkit-scrollbar{width:4px;height:4px;}
::-webkit-scrollbar-track{background:transparent;}
::-webkit-scrollbar-thumb{background:var(--surface3);border-radius:99px;}

/* SIDEBAR */
.sidebar{
  width:var(--sidebar-w);min-width:var(--sidebar-w);height:100vh;
  background:var(--surface);border-right:1px solid var(--border);
  display:flex;flex-direction:column;position:fixed;left:0;top:0;z-index:100;
}
.sidebar-header{
  padding:20px 16px 16px;border-bottom:1px solid var(--border);
}
.sidebar-title{
  font-family:'Clash Display',sans-serif;font-weight:700;font-size:.7rem;
  letter-spacing:.12em;color:var(--accent);text-transform:uppercase;margin-bottom:6px;
}
.sidebar-email{
  font-size:.72rem;color:var(--muted);word-break:break-all;line-height:1.4;
}
.sidebar-nav{flex:1;overflow-y:auto;padding:12px 8px;}
.nav-item{
  display:flex;align-items:center;gap:10px;padding:9px 10px;border-radius:8px;
  cursor:pointer;font-size:.82rem;color:var(--muted);transition:background .15s,color .15s;
  margin-bottom:2px;user-select:none;position:relative;
}
.nav-item:hover{background:rgba(255,255,255,.04);color:var(--text);}
.nav-item.active{background:rgba(0,245,160,.08);color:var(--accent);}
.nav-item .badge{
  margin-left:auto;background:var(--red);color:#fff;font-size:.6rem;
  font-weight:700;padding:1px 5px;border-radius:99px;
}
.sidebar-footer{padding:12px 8px;border-top:1px solid var(--border);}
.logout-form button{
  width:100%;padding:9px;border-radius:8px;border:1px solid var(--border);
  background:transparent;color:var(--muted);font-family:'Epilogue',sans-serif;
  font-size:.82rem;cursor:pointer;transition:background .15s,color .15s;
}
.logout-form button:hover{background:rgba(255,68,68,.08);color:var(--red);border-color:rgba(255,68,68,.3);}

/* MAIN */
.main{
  margin-left:var(--sidebar-w);flex:1;height:100vh;overflow-y:auto;
  display:flex;flex-direction:column;
}
.mock-banner{
  background:rgba(245,158,11,.12);border-bottom:1px solid rgba(245,158,11,.3);
  padding:8px 24px;font-size:.75rem;color:#f59e0b;font-weight:600;
  letter-spacing:.04em;text-align:center;flex-shrink:0;
}
.content{padding:28px 28px;flex:1;}
.section{display:none;}
.section.active{display:block;}

/* SECTION HEADER */
.section-title{
  font-family:'Clash Display',sans-serif;font-weight:700;font-size:1.4rem;
  margin-bottom:6px;
}
.section-sub{font-size:.82rem;color:var(--muted);margin-bottom:24px;}

/* METRIC GRID */
.metric-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:16px;margin-bottom:28px;}
.metric-card{
  background:var(--surface);border:1px solid var(--border);border-radius:12px;
  padding:18px 20px;
}
.metric-label{font-size:.72rem;color:var(--muted);text-transform:uppercase;letter-spacing:.08em;margin-bottom:8px;}
.metric-value{font-family:'Clash Display',sans-serif;font-size:1.6rem;font-weight:700;margin-bottom:6px;}
.metric-trend{font-size:.75rem;display:flex;align-items:center;gap:4px;}
.trend-up{color:var(--accent);}
.trend-down{color:var(--red);}
.trend-neutral{color:var(--muted);}

/* SUMMARY BAR */
.summary-bar{
  background:var(--surface);border:1px solid var(--border);border-radius:10px;
  padding:14px 20px;margin-bottom:20px;font-size:.82rem;color:var(--muted);
  display:flex;align-items:center;gap:8px;
}
.summary-bar strong{color:var(--text);}

/* SEARCH */
.search-input{
  width:100%;max-width:320px;padding:8px 14px;border-radius:8px;
  border:1px solid var(--border);background:var(--surface2);color:var(--text);
  font-family:'Epilogue',sans-serif;font-size:.82rem;margin-bottom:16px;
  outline:none;transition:border-color .15s;
}
.search-input:focus{border-color:var(--accent);}
.search-input::placeholder{color:var(--muted);}

/* TABLE */
.table-wrap{overflow-x:auto;border-radius:10px;border:1px solid var(--border);}
table{width:100%;border-collapse:collapse;font-size:.8rem;}
thead tr{background:var(--surface2);}
th{padding:10px 14px;text-align:left;font-size:.7rem;text-transform:uppercase;
   letter-spacing:.07em;color:var(--muted);font-weight:600;white-space:nowrap;}
td{padding:10px 14px;border-top:1px solid var(--border);color:var(--text);white-space:nowrap;}
tbody tr:hover{background:rgba(255,255,255,.02);}

/* STATUS BADGES */
.badge-active{background:rgba(0,245,160,.12);color:var(--accent);padding:2px 8px;border-radius:99px;font-size:.7rem;font-weight:600;}
.badge-inactive{background:rgba(107,114,128,.12);color:var(--muted);padding:2px 8px;border-radius:99px;font-size:.7rem;font-weight:600;}
.badge-warning{background:rgba(245,158,11,.12);color:#f59e0b;padding:2px 8px;border-radius:99px;font-size:.7rem;font-weight:600;}

/* REVENUE CARDS */
.rev-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:14px;margin-bottom:24px;}
.rev-card{background:var(--surface);border:1px solid var(--border);border-radius:10px;padding:16px 18px;}
.rev-label{font-size:.7rem;color:var(--muted);text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px;}
.rev-value{font-family:'Clash Display',sans-serif;font-size:1.4rem;font-weight:700;color:var(--accent);}

/* SVG CHART */
.chart-wrap{background:var(--surface);border:1px solid var(--border);border-radius:10px;padding:20px;margin-bottom:24px;}
.chart-title{font-size:.75rem;color:var(--muted);text-transform:uppercase;letter-spacing:.08em;margin-bottom:14px;}

/* ALERTS */
.alert-list{display:flex;flex-direction:column;gap:10px;}
.alert-item{
  background:var(--surface);border:1px solid var(--border);border-radius:10px;
  padding:14px 18px;display:flex;align-items:flex-start;gap:14px;
}
.alert-sev{padding:2px 8px;border-radius:99px;font-size:.68rem;font-weight:700;text-transform:uppercase;white-space:nowrap;flex-shrink:0;}
.sev-info{background:rgba(59,130,246,.15);color:#60a5fa;}
.sev-warning{background:rgba(245,158,11,.15);color:#fbbf24;}
.sev-critical{background:rgba(255,68,68,.15);color:#f87171;}
.alert-time{font-size:.72rem;color:var(--muted);margin-bottom:4px;}
.alert-msg{font-size:.82rem;color:var(--text);}

/* FORM */
.form-group{margin-bottom:16px;}
.form-label{display:block;font-size:.75rem;color:var(--muted);margin-bottom:6px;text-transform:uppercase;letter-spacing:.06em;}
.form-input,.form-select,.form-textarea{
  width:100%;padding:9px 14px;border-radius:8px;border:1px solid var(--border);
  background:var(--surface2);color:var(--text);font-family:'Epilogue',sans-serif;
  font-size:.85rem;outline:none;transition:border-color .15s;
}
.form-input:focus,.form-select:focus,.form-textarea:focus{border-color:var(--accent);}
.form-textarea{resize:vertical;min-height:100px;}
.form-select option{background:var(--surface2);}
.btn-primary{
  padding:10px 22px;border-radius:8px;border:none;background:var(--accent);
  color:#050810;font-family:'Clash Display',sans-serif;font-weight:700;
  font-size:.82rem;cursor:pointer;transition:opacity .15s;
}
.btn-primary:hover{opacity:.85;}
.confirm-msg{
  display:none;margin-top:12px;padding:10px 16px;border-radius:8px;
  background:rgba(0,245,160,.1);border:1px solid rgba(0,245,160,.25);
  color:var(--accent);font-size:.82rem;
}

/* TOGGLES */
.toggle-list{display:flex;flex-direction:column;gap:14px;margin-bottom:28px;}
.toggle-row{
  background:var(--surface);border:1px solid var(--border);border-radius:10px;
  padding:14px 18px;display:flex;align-items:center;justify-content:space-between;
}
.toggle-label{font-size:.85rem;color:var(--text);}
.toggle-desc{font-size:.72rem;color:var(--muted);margin-top:2px;}
.toggle{position:relative;width:40px;height:22px;flex-shrink:0;}
.toggle input{opacity:0;width:0;height:0;}
.toggle-slider{
  position:absolute;inset:0;border-radius:99px;background:var(--surface3);
  cursor:pointer;transition:background .2s;
}
.toggle-slider::before{
  content:'';position:absolute;width:16px;height:16px;border-radius:50%;
  background:#fff;top:3px;left:3px;transition:transform .2s;
}
.toggle input:checked + .toggle-slider{background:var(--accent);}
.toggle input:checked + .toggle-slider::before{transform:translateX(18px);}

/* INFO FIELDS */
.info-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:14px;}
.info-card{background:var(--surface);border:1px solid var(--border);border-radius:10px;padding:14px 18px;}
.info-key{font-size:.7rem;color:var(--muted);text-transform:uppercase;letter-spacing:.07em;margin-bottom:4px;}
/* RESPONSIVE DESIGN FOR COMMAND CENTER */
@media(max-width:768px) {
  .sidebar {
    transform: translateX(-100%);
    transition: transform 0.25s ease-in-out;
    z-index: 200;
    width: 240px;
    height: 100vh;
    position: fixed;
    top: 0;
    left: 0;
  }
  .sidebar.mobile-open {
    transform: translateX(0);
    box-shadow: 0 0 32px rgba(0,0,0,0.8);
  }
  .sidebar-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(4,6,10,0.6);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    z-index: 180;
    animation: fadeIn 0.2s ease both;
  }
  .sidebar-overlay.active {
    display: block;
  }
  @keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
  }
  .main {
    margin-left: 0 !important;
  }
  .mobile-hdr {
    display: flex !important;
  }
  .content {
    padding: 16px 16px 32px !important;
  }
  .metric-grid {
    grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)) !important;
    gap: 10px !important;
    margin-bottom: 20px !important;
  }
  .metric-card {
    padding: 12px 14px !important;
  }
  .metric-value {
    font-size: 1.3rem !important;
  }
  .rev-grid {
    grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)) !important;
    gap: 10px !important;
  }
  .rev-card {
    padding: 12px 14px !important;
  }
  .rev-value {
    font-size: 1.25rem !important;
  }
  .summary-bar {
    flex-wrap: wrap;
    gap: 10px;
    padding: 10px 14px !important;
  }
  .search-input {
    max-width: 100% !important;
  }
  .alert-item {
    flex-direction: column;
    gap: 8px !important;
  }
  .alert-sev {
    align-self: flex-start;
  }
  .info-grid {
    grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)) !important;
  }
  .toggle-row {
    padding: 12px 14px !important;
  }
}
</style>
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar">
  <div class="sidebar-header">
    <div class="sidebar-title">Command Center</div>
    <div class="sidebar-email">{{ env('OWNER_EMAIL', 'owner@keeption.com') }}</div>
  </div>
  <nav class="sidebar-nav">
    <div class="nav-item active" onclick="navTo('home')">
      <span>🏠</span> Home
    </div>
    <div class="nav-item" onclick="navTo('free-users')">
      <span>👤</span> Free Users
    </div>
    <div class="nav-item" onclick="navTo('pro-users')">
      <span>⭐</span> Pro Users
    </div>
    <div class="nav-item" onclick="navTo('teams')">
      <span>🏢</span> Teams
    </div>
    <div class="nav-item" onclick="navTo('revenue')">
      <span>💰</span> Revenue &amp; Finance
    </div>
    <div class="nav-item" onclick="navTo('analytics')">
      <span>📊</span> Platform Analytics
    </div>
    <div class="nav-item" onclick="navTo('alerts')" id="nav-alerts">
      <span>🔔</span> Alerts Center
      <span class="badge">3</span>
    </div>
    <div class="nav-item" onclick="navTo('comms')">
      <span>📢</span> Communications
    </div>
    <div class="nav-item" onclick="navTo('settings')">
      <span>⚙️</span> Platform Settings
    </div>
  </nav>
  <div class="sidebar-footer">
    <form class="logout-form" method="POST" action="/owner/logout">
      @csrf
      <button type="submit">↩ Logout</button>
    </form>
  </div>
</aside>

<!-- SIDEBAR OVERLAY -->
<div class="sidebar-overlay" onclick="toggleOwnerSidebar()"></div>

<!-- MAIN -->
<div class="main">
  <!-- Mobile Header -->
  <div class="mobile-hdr" style="display:none;background:var(--surface);border-bottom:1px solid var(--border);padding:12px 20px;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:150;">
    <span style="font-family:'Clash Display',sans-serif;font-weight:700;font-size:0.9rem;letter-spacing:.08em;color:var(--accent);">COMMAND CENTER</span>
    <button id="menuToggle" onclick="toggleOwnerSidebar()" style="background:none;border:none;color:var(--text);font-size:1.3rem;cursor:pointer;display:flex;align-items:center;justify-content:center;">☰</button>
  </div>
  <div class="mock-banner">⚠ Live data from MySQL · Mock data shown for Revenue, Analytics, Alerts</div>
  <div class="content">

    <!-- HOME -->
    <div id="section-home" class="section active">
      <div class="section-title">Platform Overview</div>
      <div class="section-sub">Live user data from MySQL · Revenue figures are mock</div>
      <div class="metric-grid">
        <div class="metric-card">
          <div class="metric-label">Total Users</div>
          <div class="metric-value">{{ $totalUsers }}</div>
          <div class="metric-trend trend-neutral">All registered accounts</div>
        </div>
        <div class="metric-card">
          <div class="metric-label">New Today</div>
          <div class="metric-value">{{ $newToday }}</div>
          <div class="metric-trend trend-up">{{ $newThisWeek }} this week</div>
        </div>
        <div class="metric-card">
          <div class="metric-label">Free Users</div>
          <div class="metric-value">{{ $freeUsers }}</div>
          <div class="metric-trend trend-neutral">Free plan</div>
        </div>
        <div class="metric-card">
          <div class="metric-label">Pro Subscribers</div>
          <div class="metric-value">{{ $proUsers }}</div>
          <div class="metric-trend trend-up">$3/mo each</div>
        </div>
        <div class="metric-card">
          <div class="metric-label">Teams Users</div>
          <div class="metric-value">{{ $teamsUsers }}</div>
          <div class="metric-trend trend-up">$8/mo each</div>
        </div>
        <div class="metric-card">
          <div class="metric-label">Est. MRR</div>
          <div class="metric-value">${{ ($proUsers * 3) + ($teamsUsers * 8) }}</div>
          <div class="metric-trend trend-up">Based on active plans</div>
        </div>
        <div class="metric-card">
          <div class="metric-label">Storage Used</div>
          <div class="metric-value">—</div>
          <div class="metric-trend trend-neutral">Connect storage to track</div>
        </div>
        <div class="metric-card">
          <div class="metric-label">Conversion Rate</div>
          <div class="metric-value">{{ $totalUsers > 0 ? round((($proUsers + $teamsUsers) / $totalUsers) * 100, 1) : 0 }}%</div>
          <div class="metric-trend trend-neutral">Paid / Total</div>
        </div>
      </div>

      <!-- Recent Signups -->
      <div class="section-sub" style="margin-bottom:12px;color:var(--text);font-size:.88rem;font-weight:600;">Recent Signups</div>
      <div class="table-wrap">
        <table>
          <thead><tr><th>Name</th><th>Email</th><th>Plan</th><th>Joined</th></tr></thead>
          <tbody>
            @forelse($recentUsers as $u)
            <tr>
              <td>{{ $u->name }}</td>
              <td>{{ $u->email }}</td>
              <td>
                @if($u->plan === 'pro') <span class="badge-active">Pro</span>
                @elseif($u->plan === 'teams') <span class="badge-warning">Teams</span>
                @else <span class="badge-inactive">Free</span>
                @endif
              </td>
              <td>{{ $u->created_at->diffForHumans() }}</td>
            </tr>
            @empty
            <tr><td colspan="4" style="text-align:center;color:var(--muted);padding:24px;">No users yet</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <!-- FREE USERS -->
    <div id="section-free-users" class="section">
      <div class="section-title">Free Users</div>
      <div class="section-sub">All free-tier accounts — live from MySQL</div>
      <div class="summary-bar">Total free users: <strong>{{ $freeUsers }}</strong></div>
      <input class="search-input" type="text" placeholder="Search by email…" oninput="filterTable('free-table', this.value, 1)"/>
      <div class="table-wrap">
        <table id="free-table">
          <thead><tr>
            <th>Name</th><th>Email</th><th>Joined</th><th>Status</th>
          </tr></thead>
          <tbody>
            @foreach(\App\Models\User::where('plan','free')->orderByDesc('created_at')->limit(50)->get() as $u)
            <tr>
              <td>{{ $u->name }}</td>
              <td>{{ $u->email }}</td>
              <td>{{ $u->created_at->format('Y-m-d') }}</td>
              <td><span class="badge-active">Active</span></td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    <!-- PRO USERS -->
    <div id="section-pro-users" class="section">
      <div class="section-title">Pro Subscribers</div>
      <div class="section-sub">All pro-tier subscribers — live from MySQL</div>
      <div class="summary-bar">Total pro subscribers: <strong>{{ $proUsers }}</strong> &nbsp;|&nbsp; Est. MRR from Pro: <strong>${{ $proUsers * 3 }}</strong></div>
      <input class="search-input" type="text" placeholder="Search by email…" oninput="filterTable('pro-table', this.value, 1)"/>
      <div class="table-wrap">
        <table id="pro-table">
          <thead><tr>
            <th>Name</th><th>Email</th><th>Joined</th><th>Status</th>
          </tr></thead>
          <tbody>
            @foreach(\App\Models\User::where('plan','pro')->orderByDesc('created_at')->limit(50)->get() as $u)
            <tr>
              <td>{{ $u->name }}</td>
              <td>{{ $u->email }}</td>
              <td>{{ $u->created_at->format('Y-m-d') }}</td>
              <td><span class="badge-active">Active</span></td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    <!-- TEAMS -->
    <div id="section-teams" class="section">
      <div class="section-title">Teams Workspaces</div>
      <div class="section-sub">All team workspaces — mock data</div>
      <div class="summary-bar">Total workspaces: <strong>16</strong> &nbsp;|&nbsp; Total members: <strong>94</strong> &nbsp;|&nbsp; Teams MRR: <strong>$240</strong></div>
      <input class="search-input" type="text" placeholder="Search by workspace…" oninput="filterTable('teams-table', this.value, 0)"/>
      <div class="table-wrap">
        <table id="teams-table">
          <thead><tr>
            <th>Workspace</th><th>Owner</th><th>Members</th><th>Storage</th><th>MRR</th><th>Status</th>
          </tr></thead>
          <tbody>
            <tr><td>Acme Corp</td><td>ceo@acme.com</td><td>12</td><td>180 GB</td><td>$30</td><td><span class="badge-active">Active</span></td></tr>
            <tr><td>Design Studio X</td><td>lead@studiox.io</td><td>5</td><td>94 GB</td><td>$15</td><td><span class="badge-active">Active</span></td></tr>
            <tr><td>DevOps Team Alpha</td><td>ops@alpha.dev</td><td>8</td><td>220 GB</td><td>$30</td><td><span class="badge-warning">Trial</span></td></tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- REVENUE -->
    <div id="section-revenue" class="section">
      <div class="section-title">Revenue &amp; Finance</div>
      <div class="section-sub">Financial overview — mock data</div>
      <div class="rev-grid">
        <div class="rev-card"><div class="rev-label">MRR</div><div class="rev-value">$640</div></div>
        <div class="rev-card"><div class="rev-label">ARR</div><div class="rev-value">$7,680</div></div>
        <div class="rev-card"><div class="rev-label">ARPU</div><div class="rev-value">$4.57</div></div>
        <div class="rev-card"><div class="rev-label">Churn Rate</div><div class="rev-value" style="color:#f59e0b">2.1%</div></div>
      </div>
      <div class="chart-wrap">
        <div class="chart-title">Monthly Revenue (Jan–Jul 2025)</div>
        <svg width="100%" height="140" viewBox="0 0 560 140" preserveAspectRatio="none">
          <defs>
            <linearGradient id="barGrad" x1="0" y1="0" x2="0" y2="1">
              <stop offset="0%" stop-color="#00f5a0" stop-opacity="0.9"/>
              <stop offset="100%" stop-color="#00f5a0" stop-opacity="0.3"/>
            </linearGradient>
          </defs>
          <!-- bars: Jan=480, Feb=510, Mar=540, Apr=570, May=600, Jun=620, Jul=640 -->
          <rect x="10"  y="56" width="60" height="84" rx="4" fill="url(#barGrad)"/>
          <rect x="90"  y="49" width="60" height="91" rx="4" fill="url(#barGrad)"/>
          <rect x="170" y="42" width="60" height="98" rx="4" fill="url(#barGrad)"/>
          <rect x="250" y="35" width="60" height="105" rx="4" fill="url(#barGrad)"/>
          <rect x="330" y="28" width="60" height="112" rx="4" fill="url(#barGrad)"/>
          <rect x="410" y="22" width="60" height="118" rx="4" fill="url(#barGrad)"/>
          <rect x="490" y="16" width="60" height="124" rx="4" fill="url(#barGrad)"/>
          <text x="40"  y="135" fill="#6b7280" font-size="10" text-anchor="middle">Jan</text>
          <text x="120" y="135" fill="#6b7280" font-size="10" text-anchor="middle">Feb</text>
          <text x="200" y="135" fill="#6b7280" font-size="10" text-anchor="middle">Mar</text>
          <text x="280" y="135" fill="#6b7280" font-size="10" text-anchor="middle">Apr</text>
          <text x="360" y="135" fill="#6b7280" font-size="10" text-anchor="middle">May</text>
          <text x="440" y="135" fill="#6b7280" font-size="10" text-anchor="middle">Jun</text>
          <text x="520" y="135" fill="#6b7280" font-size="10" text-anchor="middle">Jul</text>
        </svg>
      </div>
      <div class="section-sub" style="margin-bottom:12px">Recent Transactions</div>
      <div class="table-wrap">
        <table>
          <thead><tr><th>Date</th><th>User Email</th><th>Plan</th><th>Amount</th><th>Status</th></tr></thead>
          <tbody>
            <tr><td>2025-07-10</td><td>frank@startup.io</td><td>Pro</td><td>$8</td><td><span class="badge-active">Paid</span></td></tr>
            <tr><td>2025-07-10</td><td>grace.lee@company.com</td><td>Pro</td><td>$8</td><td><span class="badge-active">Paid</span></td></tr>
            <tr><td>2025-07-09</td><td>ops@alpha.dev</td><td>Teams</td><td>$30</td><td><span class="badge-active">Paid</span></td></tr>
            <tr><td>2025-07-08</td><td>henry@freelance.dev</td><td>Pro</td><td>$8</td><td><span class="badge-active">Paid</span></td></tr>
            <tr><td>2025-07-07</td><td>iris.wang@design.co</td><td>Pro</td><td>$8</td><td><span class="badge-warning">Pending</span></td></tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- ANALYTICS -->
    <div id="section-analytics" class="section">
      <div class="section-title">Platform Analytics</div>
      <div class="section-sub">Usage metrics — mock data</div>
      <div class="metric-grid">
        <div class="metric-card"><div class="metric-label">DAU</div><div class="metric-value">89</div><div class="metric-trend trend-up">▲ 7% vs last week</div></div>
        <div class="metric-card"><div class="metric-label">WAU</div><div class="metric-value">312</div><div class="metric-trend trend-up">▲ 5%</div></div>
        <div class="metric-card"><div class="metric-label">MAU</div><div class="metric-value">847</div><div class="metric-trend trend-up">▲ 3.1%</div></div>
        <div class="metric-card"><div class="metric-label">Avg Session</div><div class="metric-value">8.4 min</div><div class="metric-trend trend-neutral">— stable</div></div>
        <div class="metric-card"><div class="metric-label">Files / 30d</div><div class="metric-value">2,847</div><div class="metric-trend trend-up">▲ 12%</div></div>
        <div class="metric-card"><div class="metric-label">Storage Growth</div><div class="metric-value">12 GB/mo</div><div class="metric-trend trend-up">▲ 8%</div></div>
      </div>
      <div class="chart-wrap">
        <div class="chart-title">User Growth (Jan–Jul 2025)</div>
        <svg width="100%" height="120" viewBox="0 0 560 120" preserveAspectRatio="none">
          <polyline points="40,100 120,88 200,74 280,60 360,46 440,34 520,22"
            fill="none" stroke="#00f5a0" stroke-width="2.5" stroke-linejoin="round"/>
          <polyline points="40,100 120,88 200,74 280,60 360,46 440,34 520,22 520,110 40,110"
            fill="rgba(0,245,160,0.07)" stroke="none"/>
          <circle cx="40"  cy="100" r="4" fill="#00f5a0"/>
          <circle cx="120" cy="88"  r="4" fill="#00f5a0"/>
          <circle cx="200" cy="74"  r="4" fill="#00f5a0"/>
          <circle cx="280" cy="60"  r="4" fill="#00f5a0"/>
          <circle cx="360" cy="46"  r="4" fill="#00f5a0"/>
          <circle cx="440" cy="34"  r="4" fill="#00f5a0"/>
          <circle cx="520" cy="22"  r="4" fill="#00f5a0"/>
          <text x="40"  y="118" fill="#6b7280" font-size="10" text-anchor="middle">Jan</text>
          <text x="120" y="118" fill="#6b7280" font-size="10" text-anchor="middle">Feb</text>
          <text x="200" y="118" fill="#6b7280" font-size="10" text-anchor="middle">Mar</text>
          <text x="280" y="118" fill="#6b7280" font-size="10" text-anchor="middle">Apr</text>
          <text x="360" y="118" fill="#6b7280" font-size="10" text-anchor="middle">May</text>
          <text x="440" y="118" fill="#6b7280" font-size="10" text-anchor="middle">Jun</text>
          <text x="520" y="118" fill="#6b7280" font-size="10" text-anchor="middle">Jul</text>
        </svg>
      </div>
      <div class="section-sub" style="margin-bottom:12px">Top File Types</div>
      <div class="table-wrap">
        <table>
          <thead><tr><th>File Type</th><th>Count</th><th>% of Total</th></tr></thead>
          <tbody>
            <tr><td>PDF</td><td>8,412</td><td>29.6%</td></tr>
            <tr><td>JPEG / PNG</td><td>7,890</td><td>27.7%</td></tr>
            <tr><td>MP4 / MOV</td><td>4,230</td><td>14.9%</td></tr>
            <tr><td>DOCX / XLSX</td><td>3,810</td><td>13.4%</td></tr>
            <tr><td>ZIP / RAR</td><td>2,100</td><td>7.4%</td></tr>
            <tr><td>Other</td><td>1,985</td><td>7.0%</td></tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- ALERTS -->
    <div id="section-alerts" class="section">
      <div class="section-title">Alerts Center</div>
      <div class="section-sub">3 unread alerts — mock data</div>
      <div class="alert-list">
        <div class="alert-item">
          <span class="alert-sev sev-critical">Critical</span>
          <div>
            <div class="alert-time">2025-07-10 14:32 UTC</div>
            <div class="alert-msg">Storage node eu-west-2 reporting 94% capacity. Immediate attention required.</div>
          </div>
        </div>
        <div class="alert-item">
          <span class="alert-sev sev-warning">Warning</span>
          <div>
            <div class="alert-time">2025-07-10 11:15 UTC</div>
            <div class="alert-msg">Unusual login pattern detected from IP 185.220.101.x — 12 failed attempts in 5 minutes.</div>
          </div>
        </div>
        <div class="alert-item">
          <span class="alert-sev sev-warning">Warning</span>
          <div>
            <div class="alert-time">2025-07-09 22:04 UTC</div>
            <div class="alert-msg">Stripe webhook delivery failed for subscription renewal (invoice_id: in_mock_8821). Retrying.</div>
          </div>
        </div>
        <div class="alert-item">
          <span class="alert-sev sev-info">Info</span>
          <div>
            <div class="alert-time">2025-07-09 18:00 UTC</div>
            <div class="alert-msg">Scheduled maintenance window completed successfully. All services nominal.</div>
          </div>
        </div>
        <div class="alert-item">
          <span class="alert-sev sev-info">Info</span>
          <div>
            <div class="alert-time">2025-07-08 09:30 UTC</div>
            <div class="alert-msg">New team workspace "DevOps Team Alpha" created — trial period started.</div>
          </div>
        </div>
      </div>
    </div>

    <!-- COMMUNICATIONS -->
    <div id="section-comms" class="section">
      <div class="section-title">Communications</div>
      <div class="section-sub">Platform announcements — mock data</div>
      <div class="section-sub" style="margin-bottom:12px;color:var(--text)">Past Announcements</div>
      <div class="table-wrap" style="margin-bottom:28px">
        <table>
          <thead><tr><th>Date</th><th>Subject</th><th>Audience</th><th>Status</th></tr></thead>
          <tbody>
            <tr><td>2025-06-15</td><td>New Pro Plan Features</td><td>Pro</td><td><span class="badge-active">Sent</span></td></tr>
            <tr><td>2025-05-01</td><td>Scheduled Maintenance Notice</td><td>All</td><td><span class="badge-active">Sent</span></td></tr>
            <tr><td>2025-04-10</td><td>Teams Plan Launch</td><td>All</td><td><span class="badge-active">Sent</span></td></tr>
            <tr><td>2025-03-20</td><td>Storage Limit Updates</td><td>Free</td><td><span class="badge-inactive">Draft</span></td></tr>
          </tbody>
        </table>
      </div>
      <div class="section-sub" style="margin-bottom:16px;color:var(--text)">New Announcement</div>
      <form onsubmit="submitAnnouncement(event)" style="max-width:560px">
        <div class="form-group">
          <label class="form-label">Subject</label>
          <input class="form-input" type="text" placeholder="Announcement subject…" required/>
        </div>
        <div class="form-group">
          <label class="form-label">Audience</label>
          <select class="form-select">
            <option value="all">All Users</option>
            <option value="free">Free Users</option>
            <option value="pro">Pro Subscribers</option>
            <option value="teams">Teams</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Message</label>
          <textarea class="form-textarea" placeholder="Write your announcement…" required></textarea>
        </div>
        <button class="btn-primary" type="submit">Queue Announcement</button>
        <div class="confirm-msg" id="comms-confirm">✓ Announcement queued successfully (mock — no actual send)</div>
      </form>
    </div>

    <!-- SETTINGS -->
    <div id="section-settings" class="section">
      <div class="section-title">Platform Settings</div>
      <div class="section-sub">Feature flags and app info — changes are not persisted (mock)</div>
      <div class="toggle-list">
        <div class="toggle-row">
          <div>
            <div class="toggle-label">Maintenance Mode</div>
            <div class="toggle-desc">Puts the platform in read-only maintenance state</div>
          </div>
          <label class="toggle">
            <input type="checkbox" onchange="settingsSaved()"/>
            <span class="toggle-slider"></span>
          </label>
        </div>
        <div class="toggle-row">
          <div>
            <div class="toggle-label">New Registrations</div>
            <div class="toggle-desc">Allow new users to sign up</div>
          </div>
          <label class="toggle">
            <input type="checkbox" checked onchange="settingsSaved()"/>
            <span class="toggle-slider"></span>
          </label>
        </div>
        <div class="toggle-row">
          <div>
            <div class="toggle-label">Pro Plan Available</div>
            <div class="toggle-desc">Enable Pro plan for new and existing users</div>
          </div>
          <label class="toggle">
            <input type="checkbox" checked onchange="settingsSaved()"/>
            <span class="toggle-slider"></span>
          </label>
        </div>
        <div class="toggle-row">
          <div>
            <div class="toggle-label">Teams Plan Available</div>
            <div class="toggle-desc">Enable Teams plan for workspace creation</div>
          </div>
          <label class="toggle">
            <input type="checkbox" checked onchange="settingsSaved()"/>
            <span class="toggle-slider"></span>
          </label>
        </div>
      </div>
      <div class="confirm-msg" id="settings-confirm" style="max-width:320px;margin-bottom:20px">✓ Settings saved (mock — no server persistence)</div>
      <div class="section-sub" style="margin-bottom:12px;color:var(--text)">App Info</div>
      <div class="info-grid">
        <div class="info-card">
          <div class="info-key">App Version</div>
          <div class="info-val">{{ config('app.version', '1.0.0') }}</div>
        </div>
        <div class="info-card">
          <div class="info-key">Environment</div>
          <div class="info-val">{{ app()->environment() }}</div>
        </div>
        <div class="info-card">
          <div class="info-key">Laravel Version</div>
          <div class="info-val">{{ app()->version() }}</div>
        </div>
        <div class="info-card">
          <div class="info-key">PHP Version</div>
          <div class="info-val">{{ PHP_VERSION }}</div>
        </div>
      </div>
    </div>

  </div><!-- /content -->
</div><!-- /main -->

<script>
// Section map: nav id → section element id
const SECTIONS = {
  'home':       'section-home',
  'free-users': 'section-free-users',
  'pro-users':  'section-pro-users',
  'teams':      'section-teams',
  'revenue':    'section-revenue',
  'analytics':  'section-analytics',
  'alerts':     'section-alerts',
  'comms':      'section-comms',
  'settings':   'section-settings',
};

function toggleOwnerSidebar() {
  const sidebar = document.querySelector('.sidebar');
  const overlay = document.querySelector('.sidebar-overlay');
  if (sidebar.classList.contains('mobile-open')) {
    sidebar.classList.remove('mobile-open');
    if (overlay) overlay.classList.remove('active');
  } else {
    sidebar.classList.add('mobile-open');
    if (overlay) overlay.classList.add('active');
  }
}

function navTo(id) {
  // Hide all sections
  Object.values(SECTIONS).forEach(sid => {
    const el = document.getElementById(sid);
    if (el) el.classList.remove('active');
  });
  // Show target
  const target = document.getElementById(SECTIONS[id]);
  if (target) target.classList.add('active');

  // Update nav highlights
  document.querySelectorAll('.nav-item').forEach(item => {
    item.classList.remove('active');
  });
  // Find the nav item that triggered this section
  document.querySelectorAll('.nav-item').forEach(item => {
    const onclick = item.getAttribute('onclick') || '';
    if (onclick.includes("'" + id + "'")) {
      item.classList.add('active');
    }
  });

  // Scroll main content to top
  const main = document.querySelector('.main');
  if (main) main.scrollTop = 0;

  // Auto-close sidebar on mobile after navigating
  if (window.innerWidth <= 768) {
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.querySelector('.sidebar-overlay');
    if (sidebar) sidebar.classList.remove('mobile-open');
    if (overlay) overlay.classList.remove('active');
  }
}

// Client-side table search filter
function filterTable(tableId, query, colIndex) {
  const table = document.getElementById(tableId);
  if (!table) return;
  const rows = table.querySelectorAll('tbody tr');
  const q = query.toLowerCase().trim();
  rows.forEach(row => {
    const cell = row.cells[colIndex];
    const text = cell ? cell.textContent.toLowerCase() : '';
    row.style.display = (!q || text.includes(q)) ? '' : 'none';
  });
}

// Communications form submit
function submitAnnouncement(e) {
  e.preventDefault();
  const msg = document.getElementById('comms-confirm');
  msg.style.display = 'block';
  setTimeout(() => { msg.style.display = 'none'; }, 4000);
  e.target.reset();
}

// Settings toggle feedback
function settingsSaved() {
  const msg = document.getElementById('settings-confirm');
  msg.style.display = 'block';
  clearTimeout(window._settingsTimer);
  window._settingsTimer = setTimeout(() => { msg.style.display = 'none'; }, 3000);
}
</script>
</body>
</html>
