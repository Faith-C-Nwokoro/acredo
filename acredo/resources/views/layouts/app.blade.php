<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Acredo — @yield('title','Dashboard')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
    <style>
    :root{--bg:#0A0A0F;--surface:#111118;--elevated:#1A1A24;--border:#2A2A3A;--primary:#6366F1;--primary-h:#4F46E5;--accent:#06B6D4;--success:#10B981;--warning:#F59E0B;--danger:#EF4444;--text:#F8FAFC;--text-2:#94A3BB;--text-3:#475569;--sw:240px;--th:60px}
    *{box-sizing:border-box;margin:0;padding:0}
    html,body{height:100%;background:var(--bg);color:var(--text);font-family:'Inter',sans-serif}
    .mono{font-family:'JetBrains Mono',monospace}
    ::-webkit-scrollbar{width:5px}::-webkit-scrollbar-track{background:var(--bg)}::-webkit-scrollbar-thumb{background:var(--border);border-radius:3px}
    #app-shell{display:flex;height:100vh;overflow:hidden}
    /* SIDEBAR */
    #sidebar{width:var(--sw);flex-shrink:0;background:var(--surface);border-right:1px solid var(--border);display:flex;flex-direction:column;overflow-y:auto;transition:transform .28s ease;z-index:50}
    .sb-logo{padding:18px 20px 14px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:11px}
    .sb-logo .aclogo{width:38px;height:38px;flex-shrink:0}
    .sb-logo .brand{font-size:1.1rem;font-weight:700;color:var(--text);letter-spacing:.4px}
    .nav-sec{padding:14px 10px;flex:1}
    .nav-lbl{font-size:.62rem;font-weight:600;color:var(--text-3);text-transform:uppercase;letter-spacing:1px;padding:0 10px;margin:12px 0 6px}
    .nav-item{display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;color:var(--text-2);font-size:.85rem;font-weight:500;text-decoration:none;transition:all .18s;margin-bottom:2px}
    .nav-item:hover{background:var(--elevated);color:var(--text)}
    .nav-item.active{background:rgba(99,102,241,.12);color:var(--primary);border-left:3px solid var(--primary)}
    .nav-item svg{width:18px;height:18px;flex-shrink:0}
    .sb-foot{padding:14px 10px;border-top:1px solid var(--border)}
    .net-badge{display:inline-flex;align-items:center;gap:6px;background:rgba(6,182,212,.1);border:1px solid rgba(6,182,212,.3);color:var(--accent);font-size:.68rem;font-weight:600;padding:5px 10px;border-radius:20px}
    .net-dot{width:6px;height:6px;border-radius:50%;background:var(--accent);animation:pulse 2s infinite}
    @keyframes pulse{0%,100%{opacity:1}50%{opacity:.4}}
    .back-home{display:flex;align-items:center;gap:7px;color:var(--text-3);font-size:.75rem;text-decoration:none;padding:7px 0;margin-bottom:10px;transition:color .2s}
    .back-home:hover{color:var(--text-2)}
    /* SIDEBAR OVERLAY */
    #sb-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.65);z-index:40}
    #sb-overlay.open{display:block}
    /* MAIN */
    #main-wrap{flex:1;display:flex;flex-direction:column;overflow:hidden;min-width:0}
    #top-bar{height:var(--th);flex-shrink:0;background:var(--surface);border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;padding:0 20px;gap:12px}
    #top-left{display:flex;align-items:center;gap:10px}
    .page-title{font-size:.95rem;font-weight:600;color:var(--text)}
    #menu-btn{display:none;background:none;border:1px solid var(--border);border-radius:8px;padding:7px;cursor:pointer;color:var(--text-2);align-items:center;justify-content:center}
    #wallet-btn{display:flex;align-items:center;gap:8px;background:var(--primary);color:#fff;border:none;cursor:pointer;padding:8px 16px;border-radius:10px;font-size:.8rem;font-weight:600;font-family:inherit;transition:background .2s;white-space:nowrap}
    #wallet-btn:hover{background:var(--primary-h)}
    #wallet-btn.connected{background:rgba(99,102,241,.15);border:1px solid rgba(99,102,241,.4);color:var(--primary)}
    #page-body{flex:1;overflow-y:auto;padding:22px}
    /* MOBILE */
    @media(max-width:768px){
        #sidebar{position:fixed;top:0;left:0;bottom:0;transform:translateX(-100%)}
        #sidebar.open{transform:translateX(0)}
        #menu-btn{display:inline-flex}
        #page-body{padding:14px;padding-bottom:78px}
    }
    /* BOTTOM NAV */
    #bottom-nav{display:none;position:fixed;bottom:0;left:0;right:0;background:var(--surface);border-top:1px solid var(--border);z-index:30;padding-bottom:env(safe-area-inset-bottom)}
    .bnav{display:flex}
    .bnav-item{flex:1;display:flex;flex-direction:column;align-items:center;gap:3px;padding:7px 0 5px;text-decoration:none;color:var(--text-3);font-size:.57rem;font-weight:500;transition:color .18s}
    .bnav-item.active{color:var(--primary)}
    .bnav-item svg{width:20px;height:20px}
    @media(max-width:768px){#bottom-nav{display:block}}
    /* LAYOUT GRIDS */
    .stat-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:13px;margin-bottom:18px}
    .col-6040{display:grid;grid-template-columns:3fr 2fr;gap:16px}
    .two-col{display:grid;grid-template-columns:1fr 1fr;gap:16px}
    @media(max-width:1100px){.stat-grid{grid-template-columns:repeat(2,1fr)}}
    @media(max-width:900px){.col-6040,.two-col{grid-template-columns:1fr}}
    @media(max-width:520px){.stat-grid{grid-template-columns:1fr 1fr}}
    /* CARDS */
    .card{background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:16px}
    .card-title{font-size:.7rem;font-weight:600;color:var(--text-2);text-transform:uppercase;letter-spacing:.5px;margin-bottom:9px}
    .card-value{font-size:1.65rem;font-weight:700;color:var(--text);line-height:1}
    .card-sub{font-size:.74rem;color:var(--text-2);margin-top:5px}
    .score-glow{box-shadow:0 0 34px rgba(99,102,241,.1)}
    /* BADGES */
    .badge{display:inline-flex;align-items:center;padding:3px 9px;border-radius:20px;font-size:.67rem;font-weight:700}
    .badge-success{background:rgba(16,185,129,.15);color:var(--success);border:1px solid rgba(16,185,129,.3)}
    .badge-warning{background:rgba(245,158,11,.15);color:var(--warning);border:1px solid rgba(245,158,11,.3)}
    .badge-danger{background:rgba(239,68,68,.15);color:var(--danger);border:1px solid rgba(239,68,68,.3)}
    .badge-muted{background:rgba(71,85,105,.2);color:var(--text-3);border:1px solid rgba(71,85,105,.3)}
    .badge-primary{background:rgba(99,102,241,.15);color:var(--primary);border:1px solid rgba(99,102,241,.3)}
    .badge-accent{background:rgba(6,182,212,.12);color:var(--accent);border:1px solid rgba(6,182,212,.3)}
    .tier-a{background:rgba(16,185,129,.15);color:#34d399;border:1px solid rgba(16,185,129,.3)}
    .tier-b{background:rgba(245,158,11,.15);color:#fbbf24;border:1px solid rgba(245,158,11,.3)}
    .tier-c{background:rgba(249,115,22,.15);color:#fb923c;border:1px solid rgba(249,115,22,.3)}
    .tier-d{background:rgba(239,68,68,.15);color:#f87171;border:1px solid rgba(239,68,68,.3)}
    /* BUTTONS */
    .btn{display:inline-flex;align-items:center;gap:7px;padding:9px 18px;border-radius:9px;font-size:.81rem;font-weight:600;cursor:pointer;border:none;font-family:inherit;transition:all .18s;text-decoration:none;white-space:nowrap}
    .btn-primary{background:var(--primary);color:#fff}.btn-primary:hover{background:var(--primary-h)}
    .btn-ghost{background:transparent;color:var(--text-2);border:1px solid var(--border)}.btn-ghost:hover{background:var(--elevated);color:var(--text)}
    .btn-success{background:rgba(16,185,129,.15);color:var(--success);border:1px solid rgba(16,185,129,.3)}.btn-success:hover{background:rgba(16,185,129,.25)}
    .btn-sm{padding:5px 11px;font-size:.72rem;border-radius:8px}
    .btn-full{width:100%;justify-content:center}
    .btn:disabled{opacity:.5;cursor:not-allowed}
    /* INPUTS */
    .input-group{margin-bottom:13px}
    .input-label{display:block;font-size:.74rem;font-weight:500;color:var(--text-2);margin-bottom:5px}
    .input{width:100%;background:var(--elevated);border:1px solid var(--border);border-radius:9px;padding:9px 12px;font-size:.84rem;color:var(--text);font-family:inherit;outline:none;transition:border-color .18s}
    .input:focus{border-color:var(--primary)}
    .input::placeholder{color:var(--text-3)}
    select.input{cursor:pointer}
    .range-input{-webkit-appearance:none;width:100%;height:4px;background:var(--border);border-radius:2px;outline:none;cursor:pointer;margin-top:7px}
    .range-input::-webkit-slider-thumb{-webkit-appearance:none;width:14px;height:14px;background:var(--primary);border-radius:50%}
    /* TABS */
    .tabs{display:flex;border-bottom:1px solid var(--border);margin-bottom:20px;overflow-x:auto;-webkit-overflow-scrolling:touch;gap:0}
    .tabs::-webkit-scrollbar{height:0}
    .tab-btn{padding:9px 18px;font-size:.82rem;font-weight:600;color:var(--text-2);cursor:pointer;background:none;border:none;font-family:inherit;border-bottom:2px solid transparent;transition:all .18s;margin-bottom:-1px;white-space:nowrap}
    .tab-btn:hover{color:var(--text)}
    .tab-btn.active{color:var(--primary);border-bottom-color:var(--primary)}
    .tab-panel{display:none}.tab-panel.active{display:block}
    /* TABLE */
    .table-wrap{overflow-x:auto;-webkit-overflow-scrolling:touch}
    table{width:100%;border-collapse:collapse;font-size:.82rem;min-width:440px}
    th{font-size:.69rem;font-weight:600;color:var(--text-3);text-transform:uppercase;letter-spacing:.5px;padding:9px 13px;text-align:left;border-bottom:1px solid var(--border)}
    td{padding:12px 13px;color:var(--text-2);border-bottom:1px solid rgba(42,42,58,.5)}
    tr:last-child td{border-bottom:none}
    tr:hover td{background:rgba(255,255,255,.014)}
    td strong{color:var(--text);font-weight:500}
    /* SECTION TITLE */
    .section-title{font-size:.98rem;font-weight:700;color:var(--text);margin-bottom:13px;display:flex;align-items:center;gap:8px}
    /* SEG CONTROL */
    .seg-control{display:flex;gap:3px;background:var(--elevated);border-radius:9px;padding:3px;border:1px solid var(--border)}
    .seg-btn{flex:1;padding:6px 8px;border-radius:7px;font-size:.75rem;font-weight:600;cursor:pointer;background:none;border:none;font-family:inherit;color:var(--text-2);transition:all .18s;white-space:nowrap;text-align:center}
    .seg-btn.active{background:var(--primary);color:#fff}
    /* SUMMARY BOX */
    .summary-box{background:var(--elevated);border:1px solid var(--border);border-radius:11px;padding:13px}
    .summary-row{display:flex;justify-content:space-between;align-items:center;padding:5px 0;font-size:.79rem;color:var(--text-2)}
    .summary-row:not(:last-child){border-bottom:1px solid var(--border)}
    .summary-row strong{color:var(--text);font-weight:600}
    /* MISC */
    .progress-wrap{background:var(--elevated);border-radius:4px;height:5px;overflow:hidden}
    .progress-bar{height:100%;background:linear-gradient(90deg,var(--primary),var(--accent));border-radius:4px;transition:width 1s}
    .connect-prompt{text-align:center;padding:50px 20px}
    .connect-prompt h2{font-size:1.2rem;font-weight:700;margin:16px 0 8px}
    .connect-prompt p{color:var(--text-2);font-size:.86rem;margin-bottom:20px;max-width:340px;margin-left:auto;margin-right:auto;line-height:1.6}
    .skeleton{background:linear-gradient(90deg,var(--elevated) 25%,var(--border) 50%,var(--elevated) 75%);background-size:200% 100%;animation:shimmer 1.5s infinite;border-radius:7px}
    @keyframes shimmer{0%{background-position:200% 0}100%{background-position:-200% 0}}
    .modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,.72);z-index:1000;display:flex;align-items:center;justify-content:center;backdrop-filter:blur(4px);padding:16px}
    .modal{background:var(--surface);border:1px solid var(--border);border-radius:17px;padding:22px;width:100%;max-width:410px}
    .modal-title{font-size:1rem;font-weight:700;margin-bottom:13px}
    .modal-actions{display:flex;gap:9px;margin-top:16px}
    .collapsible-btn{background:none;border:none;color:var(--text-2);font-size:.77rem;cursor:pointer;font-family:inherit;display:flex;align-items:center;gap:6px;padding:4px 0}
    .collapsible-btn:hover{color:var(--text)}
    .collapsible-content{display:none;padding-top:10px}
    .collapsible-content.open{display:block}
    .chart-container{position:relative;height:155px}
    .divider{height:1px;background:var(--border);margin:13px 0}
    .flex{display:flex}.items-center{align-items:center}.justify-between{justify-content:space-between}
    .gap-2{gap:8px}.gap-3{gap:12px}.gap-4{gap:16px}
    .mb-4{margin-bottom:16px}.mb-6{margin-bottom:22px}.mt-2{margin-top:8px}.mt-3{margin-top:12px}
    .text-sm{font-size:.8rem}.text-xs{font-size:.69rem}.text-muted{color:var(--text-2)}
    .text-primary{color:var(--primary)}.text-success{color:var(--success)}.text-warning{color:var(--warning)}.text-danger{color:var(--danger)}
    .font-bold{font-weight:700}.w-full{width:100%}.hidden{display:none!important}
    .p-4{padding:16px}.bg-elevated{background:var(--elevated)}
    .rep-ring-wrap{display:flex;flex-direction:column;align-items:center;padding:16px 0 8px}
    .rep-ring-wrap svg{width:145px;height:145px}
    .gauge-wrap{display:flex;flex-direction:column;align-items:center;margin:13px 0}
    .gauge-wrap svg{width:185px;height:105px}
    .gauge-val{font-size:1.85rem;font-weight:800;margin-top:4px}
    .gauge-label{font-size:.71rem;color:var(--text-2)}
    .nft-card{border:2px solid var(--border);border-radius:12px;overflow:hidden;cursor:pointer;transition:all .2s}
    .nft-card:hover{border-color:rgba(99,102,241,.5);transform:translateY(-2px)}
    .nft-card.selected{border-color:var(--primary);box-shadow:0 0 0 3px rgba(99,102,241,.14)}
    .nft-thumb{width:100%;aspect-ratio:1}
    .nft-info{padding:9px 10px;background:var(--surface)}
    .nft-name{font-size:.77rem;font-weight:600;color:var(--text)}
    .nft-floor{font-size:.7rem;color:var(--text-2);margin-top:2px}
    /* LOAN CARD */
    .loan-req-card{background:var(--surface);border:1px solid var(--border);border-radius:13px;padding:14px 16px;transition:border-color .2s}
    .loan-req-card:hover{border-color:rgba(99,102,241,.4)}
    .lrc-top{display:flex;align-items:flex-start;justify-content:space-between;gap:10px;flex-wrap:wrap;margin-bottom:10px}
    .lrc-body{display:flex;flex-wrap:wrap;gap:16px;margin-bottom:8px}
    .lrd .lbl{font-size:.67rem;color:var(--text-3);margin-bottom:2px}
    .lrd .val{font-size:.81rem;font-weight:600;color:var(--text)}
    .lrc-expand{display:none;padding-top:11px;border-top:1px solid var(--border);margin-top:10px}
    .lrc-expand.open{display:block}
    .exp-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:10px}
    @media(max-width:540px){.exp-grid{grid-template-columns:repeat(2,1fr)}}
    /* TOAST */
    #toast-box{position:fixed;top:14px;right:14px;z-index:9999;display:flex;flex-direction:column;gap:8px;max-width:300px;width:calc(100vw - 28px)}
    .toast{background:var(--elevated);border:1px solid var(--border);border-radius:12px;padding:12px 15px;box-shadow:0 14px 36px rgba(0,0,0,.5);animation:slideIn .3s ease}
    .toast-title{font-size:.8rem;font-weight:700;color:var(--success);margin-bottom:5px;display:flex;align-items:center;gap:6px}
    .toast-hash{font-size:.68rem;color:var(--text-2);font-family:'JetBrains Mono',monospace;word-break:break-all}
    .toast-link{font-size:.68rem;color:var(--primary);text-decoration:none;display:block;margin-top:3px}
    @keyframes slideIn{from{transform:translateX(110%);opacity:0}to{transform:translateX(0);opacity:1}}
    @keyframes spin{to{transform:rotate(360deg)}}
    </style>
</head>
<body>
<div id="app-shell">
    <!-- SIDEBAR -->
    <aside id="sidebar">
        <div class="sb-logo">
            <!-- Real AC Monogram Logo: bold interlocked A + C with 3D blue-cyan gradient -->
            <svg class="aclogo" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="la" x1="4" y1="3" x2="26" y2="46" gradientUnits="userSpaceOnUse">
                        <stop offset="0%" stop-color="#9AAEFF"/>
                        <stop offset="48%" stop-color="#5060EE"/>
                        <stop offset="100%" stop-color="#2535C0"/>
                    </linearGradient>
                    <linearGradient id="lad" x1="0" y1="0" x2="1" y2="1">
                        <stop offset="0%" stop-color="#08124A" stop-opacity=".8"/>
                        <stop offset="100%" stop-color="#040828" stop-opacity=".45"/>
                    </linearGradient>
                    <linearGradient id="lc" x1="16" y1="4" x2="48" y2="45" gradientUnits="userSpaceOnUse">
                        <stop offset="0%" stop-color="#72EEFF"/>
                        <stop offset="52%" stop-color="#06B6D4"/>
                        <stop offset="100%" stop-color="#0A6878"/>
                    </linearGradient>
                </defs>
                <!-- A depth shadow -->
                <polygon points="13.5,44 7.5,44 17.5,5 22.5,5" fill="url(#lad)" opacity=".7"/>
                <polygon points="31,44 25,44 22.5,5 27.5,5" fill="url(#lad)" opacity=".7"/>
                <rect x="12" y="24.5" width="20.5" height="5.5" fill="url(#lad)" opacity=".7"/>
                <!-- A front -->
                <polygon points="12,42 6,42 16,4 21,4" fill="url(#la)"/>
                <polygon points="29.5,42 23.5,42 21,4 26,4" fill="url(#la)"/>
                <rect x="10.5" y="23" width="21" height="5.2" fill="url(#la)"/>
                <!-- C shadow -->
                <path d="M 41,10.5 A 16.5,16.5 0 1,1 41,37" stroke="#030D22" stroke-width="9" fill="none" stroke-linecap="round" opacity=".55"/>
                <!-- C main -->
                <path d="M 40,9.5 A 15,15 0 1,1 40,36.5" stroke="url(#lc)" stroke-width="7.5" fill="none" stroke-linecap="round"/>
                <!-- C highlight -->
                <path d="M 40,9.5 A 15,15 0 0,1 40,36.5" stroke="rgba(200,245,255,.22)" stroke-width="1.5" fill="none" stroke-linecap="round"/>
            </svg>
            <span class="brand">ACREDO</span>
        </div>
        <nav class="nav-sec">
            <div class="nav-lbl">Main</div>
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>Dashboard</a>
            <a href="{{ route('borrow') }}" class="nav-item {{ request()->routeIs('borrow') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>Borrow</a>
            <a href="{{ route('marketplace') }}" class="nav-item {{ request()->routeIs('marketplace') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M3 3h18M3 9h18M3 15h18M3 21h18"/></svg>Marketplace</a>
            <div class="nav-lbl">Yield</div>
            <a href="{{ route('vault') }}" class="nav-item {{ request()->routeIs('vault') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>Vault</a>
            <a href="{{ route('pool') }}" class="nav-item {{ request()->routeIs('pool') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>Pool</a>
            <div class="nav-lbl">Account</div>
            <a href="{{ route('profile') }}" class="nav-item {{ request()->routeIs('profile') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>Profile</a>
        </nav>
        <div class="sb-foot">
            <a href="{{ route('landing') }}" class="back-home"><svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>Back to Home</a>
            <div class="net-badge"><div class="net-dot"></div>Stacks Testnet</div>
        </div>
    </aside>
    <div id="sb-overlay" onclick="closeSb()"></div>

    <!-- MAIN -->
    <div id="main-wrap">
        <header id="top-bar">
            <div id="top-left">
                <button id="menu-btn" onclick="openSb()"><svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg></button>
                <span class="page-title">@yield('page-title','Dashboard')</span>
            </div>
            <button id="wallet-btn" onclick="toggleWallet()">
                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/></svg>
                <span id="wt">Connect Wallet</span>
            </button>
        </header>
        <main id="page-body">@yield('content')</main>
    </div>
</div>

<!-- BOTTOM NAV -->
<nav id="bottom-nav">
    <div class="bnav">
        <a href="{{ route('dashboard') }}" class="bnav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>Home</a>
        <a href="{{ route('borrow') }}" class="bnav-item {{ request()->routeIs('borrow') ? 'active' : '' }}"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>Borrow</a>
        <a href="{{ route('marketplace') }}" class="bnav-item {{ request()->routeIs('marketplace') ? 'active' : '' }}"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M3 3h18M3 9h18M3 15h18M3 21h18"/></svg>Market</a>
        <a href="{{ route('vault') }}" class="bnav-item {{ request()->routeIs('vault') ? 'active' : '' }}"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>Vault</a>
        <a href="{{ route('profile') }}" class="bnav-item {{ request()->routeIs('profile') ? 'active' : '' }}"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>Profile</a>
    </div>
</nav>

<div id="toast-box"></div>
<script>
const WS={connected:{{ session('wallet_connected') ? 'true' : 'false' }},address:'{{ session('wallet_address','') }}',bns:'{{ session('wallet_bns','') }}'};
function csrf(){return document.querySelector('meta[name="csrf-token"]').content}
function trunc(a){return a.slice(0,6)+'…'+a.slice(-5)}
function updateWUI(){const b=document.getElementById('wallet-btn'),t=document.getElementById('wt');if(WS.connected){b.classList.add('connected');t.textContent=WS.bns||trunc(WS.address)}else{b.classList.remove('connected');t.textContent='Connect Wallet'}}
async function toggleWallet(){
    if(WS.connected){await fetch('/wallet/disconnect',{method:'POST',headers:{'X-CSRF-TOKEN':csrf()}});WS.connected=false;updateWUI();location.reload()}
    else{const r=await fetch('/wallet/connect',{method:'POST',headers:{'X-CSRF-TOKEN':csrf()}});const d=await r.json();if(d.success){WS.connected=true;WS.address=d.wallet.address;WS.bns=d.wallet.bnsName;updateWUI();showToast('Wallet Connected','vilansh.btc',null,true);setTimeout(()=>location.reload(),700)}}
}
function openSb(){document.getElementById('sidebar').classList.add('open');document.getElementById('sb-overlay').classList.add('open')}
function closeSb(){document.getElementById('sidebar').classList.remove('open');document.getElementById('sb-overlay').classList.remove('open')}
async function sendTx(label){
    const btn=event.currentTarget,orig=btn.innerHTML;
    btn.disabled=true;btn.innerHTML='<svg style="animation:spin 1s linear infinite;width:13px;height:13px;flex-shrink:0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg> Processing…';
    await new Promise(r=>setTimeout(r,900));
    const res=await fetch('/api/transaction',{method:'POST',headers:{'X-CSRF-TOKEN':csrf()}});
    const d=await res.json();
    btn.innerHTML=orig;btn.disabled=false;showToast('Transaction Submitted',d.txHash);
}
function showToast(title,hash,link,noLink){
    const c=document.getElementById('toast-box'),el=document.createElement('div');
    el.className='toast';
    el.innerHTML=`<div class="toast-title"><svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M20 6 9 17l-5-5"/></svg>${title}</div><div class="toast-hash">${hash}</div>${!noLink?'<a href="#" class="toast-link">View on Explorer →</a>':''}`;
    c.appendChild(el);
    setTimeout(()=>{el.style.opacity='0';el.style.transform='translateX(110%)';el.style.transition='all .3s';setTimeout(()=>el.remove(),310)},5000);
}
function toggleCollapsible(id){document.getElementById(id).classList.toggle('open')}
function copyText(t){navigator.clipboard.writeText(t);showToast('Copied!',t.slice(0,28)+'…',null,true)}
function setSegActive(el,cid){document.querySelectorAll('#'+cid+' .seg-btn').forEach(b=>b.classList.remove('active'));el.classList.add('active')}
updateWUI();
</script>
@stack('scripts')
</body>
</html>
