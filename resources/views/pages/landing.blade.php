<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Acredo — Structured Credit & Yield on Stacks</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;700;800&family=DM+Sans:wght@300;400;500&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --black:#020208;--surface:#0E0E1A;--card:#13131F;
  --border:#1C1C2E;--border2:#252538;
  --indigo:#5B5FEE;--indigo-b:#818CF8;--cyan:#22D3EE;
  --violet:#7C3AED;--emerald:#10B981;
  --text:#EEEEF7;--text2:#8B8BA8;--text3:#3D3D58;
  --nav-h:68px;
}
html{scroll-behavior:smooth}
body{background:var(--black);color:var(--text);font-family:'DM Sans',sans-serif;overflow-x:hidden;line-height:1.6}
::-webkit-scrollbar{width:4px}
::-webkit-scrollbar-track{background:var(--black)}
::-webkit-scrollbar-thumb{background:var(--border2);border-radius:2px}
body::after{content:'';position:fixed;inset:0;background-image:url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.035'/%3E%3C/svg%3E");pointer-events:none;z-index:998;opacity:.7}
.glow{position:fixed;border-radius:50%;pointer-events:none;filter:blur(110px);z-index:0}
.g1{width:700px;height:700px;background:radial-gradient(circle,rgba(91,95,238,.13),transparent 70%);top:-220px;left:-180px;animation:d1 22s ease-in-out infinite}
.g2{width:500px;height:500px;background:radial-gradient(circle,rgba(34,211,238,.07),transparent 70%);top:35%;right:-160px;animation:d2 26s ease-in-out infinite}
.g3{width:380px;height:380px;background:radial-gradient(circle,rgba(124,58,237,.09),transparent 70%);bottom:-80px;left:25%;animation:d1 19s 4s ease-in-out infinite reverse}
@keyframes d1{0%,100%{transform:translate(0,0)}40%{transform:translate(55px,-70px)}70%{transform:translate(-35px,45px)}}
@keyframes d2{0%,100%{transform:translate(0,0)}35%{transform:translate(-60px,55px)}65%{transform:translate(45px,-35px)}}

/* ══════════════════ NAV ══════════════════ */
nav{
  position:fixed;top:0;left:0;right:0;z-index:200;
  height:var(--nav-h);
  display:flex;align-items:center;
  /* 3-column: logo | center-links | right-actions */
  padding:0 clamp(16px,4vw,72px);
  gap:12px;
  background:rgba(2,2,8,.78);
  backdrop-filter:blur(24px) saturate(1.4);
  border-bottom:1px solid rgba(255,255,255,.04);
  transition:background .35s;
}
nav.scrolled{background:rgba(2,2,8,.96)}

/* Logo — never shrinks, never wraps */
.logo{
  display:flex;align-items:center;gap:10px;
  text-decoration:none;flex-shrink:0;
}
.logo-mark{width:36px;height:36px;flex-shrink:0;display:block}
.logo-text{
  font-family:'Syne',sans-serif;font-size:1.08rem;
  font-weight:800;color:var(--text);letter-spacing:.4px;white-space:nowrap;
}

/* Center nav — grows to fill middle, links centered inside */
.nav-center{
  flex:1;display:flex;justify-content:center;
  min-width:0;overflow:hidden;
}
.nav-links{
  display:flex;gap:2px;list-style:none;align-items:center;
  flex-wrap:nowrap;
}
.nav-links a{
  color:var(--text2);text-decoration:none;
  font-size:.83rem;font-weight:400;
  padding:7px 12px;border-radius:8px;
  transition:color .2s,background .2s;white-space:nowrap;
}
.nav-links a:hover{color:var(--text);background:rgba(255,255,255,.05)}

/* Right actions — never shrinks */
.nav-right{display:flex;align-items:center;gap:10px;flex-shrink:0}

/* Shared button base */
.btn-base{
  display:inline-flex;align-items:center;justify-content:center;gap:7px;
  white-space:nowrap;cursor:pointer;
  font-family:'DM Sans',sans-serif;font-size:.82rem;font-weight:500;
  border-radius:10px;text-decoration:none;transition:all .22s;
}
.btn-ghost{padding:8px 18px;background:transparent;border:1px solid var(--border2);color:var(--text2)}
.btn-ghost:hover{border-color:var(--indigo);color:var(--indigo-b)}
.btn-launch{padding:8px 20px;background:var(--indigo);color:#fff;border:none}
.btn-launch:hover{background:#4951E8;transform:translateY(-1px);box-shadow:0 6px 28px rgba(91,95,238,.4)}
.btn-launch svg{transition:transform .25s}
.btn-launch:hover svg{transform:translateX(3px)}

/* Hamburger — only shows on mobile */
.nav-toggle{
  display:none;
  background:none;border:1px solid var(--border2);border-radius:8px;
  padding:8px;cursor:pointer;color:var(--text2);
  align-items:center;justify-content:center;flex-shrink:0;
}

/* Breakpoints */
/* Hide center links below 900px */
@media(max-width:900px){.nav-center{display:none}}
/* Hide ghost "Dashboard" below 640px, show hamburger */
@media(max-width:640px){
  .btn-ghost{display:none}
  .nav-toggle{display:inline-flex}
}
/* Hide logo text below 360px */
@media(max-width:360px){.logo-text{display:none}}

/* Mobile drawer */
.nav-drawer{
  display:none;flex-direction:column;
  position:fixed;top:var(--nav-h);left:0;right:0;
  background:rgba(5,5,12,.97);backdrop-filter:blur(28px);
  border-bottom:1px solid var(--border);
  padding:16px clamp(16px,4vw,72px) 24px;
  z-index:190;gap:3px;
}
.nav-drawer.open{display:flex}
.nd-link{color:var(--text2);text-decoration:none;font-size:.92rem;padding:12px 14px;border-radius:10px;transition:all .2s;display:block}
.nd-link:hover{color:var(--text);background:rgba(255,255,255,.04)}
.nd-btns{display:flex;gap:10px;margin-top:12px;padding-top:14px;border-top:1px solid var(--border)}
.nd-btns a{flex:1;padding:11px 0;text-align:center;justify-content:center}

/* ══════════════════ HERO ══════════════════ */
.hero{
  position:relative;z-index:1;min-height:100svh;
  display:flex;flex-direction:column;align-items:center;justify-content:center;
  text-align:center;overflow:hidden;
  padding:calc(var(--nav-h) + 56px) clamp(20px,6vw,80px) 56px;
}
.hero-spot{position:absolute;width:900px;height:600px;left:50%;top:50%;transform:translate(-50%,-55%);background:radial-gradient(ellipse,rgba(91,95,238,.08) 0%,rgba(34,211,238,.04) 40%,transparent 70%);pointer-events:none}
.hero-grid-bg{position:absolute;inset:0;background-image:linear-gradient(rgba(255,255,255,.021) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.021) 1px,transparent 1px);background-size:80px 80px;mask-image:radial-gradient(ellipse 80% 60% at 50% 50%,black,transparent);pointer-events:none}

.eyebrow{display:inline-flex;align-items:center;gap:8px;background:rgba(91,95,238,.1);border:1px solid rgba(91,95,238,.28);border-radius:100px;padding:5px 14px 5px 7px;font-size:.71rem;font-weight:500;color:var(--indigo-b);margin-bottom:24px;animation:fadeUp .6s ease both}
.eyebrow-dot{width:18px;height:18px;border-radius:50%;background:var(--indigo);display:flex;align-items:center;justify-content:center;flex-shrink:0}
.eyebrow-dot svg{width:9px;height:9px}

.hero-h1{font-family:'Syne',sans-serif;font-size:clamp(2.8rem,8.5vw,7.2rem);font-weight:800;line-height:.93;letter-spacing:clamp(-1px,-0.04em,-3px);margin-bottom:20px;animation:fadeUp .7s .1s ease both}
@media(max-width:480px){.hero-h1{letter-spacing:-1px}}
.h1a{display:block;color:var(--text)}
.h1b{display:block;-webkit-text-fill-color:transparent;-webkit-background-clip:text;background-clip:text;background-image:linear-gradient(105deg,var(--indigo-b) 0%,var(--cyan) 48%,var(--violet) 100%);background-size:300% 100%;animation:fadeUp .7s .1s ease both,gm 7s 1s ease-in-out infinite alternate}
@keyframes gm{0%{background-position:0 50%}100%{background-position:100% 50%}}

.hero-sub{font-size:clamp(.88rem,1.8vw,1.08rem);color:var(--text2);font-weight:300;max-width:500px;margin:0 auto 36px;line-height:1.8;animation:fadeUp .7s .2s ease both}

.hero-cta{display:flex;gap:12px;flex-wrap:wrap;justify-content:center;margin-bottom:60px;animation:fadeUp .7s .3s ease both}
.btn-hp{display:inline-flex;align-items:center;gap:9px;background:var(--indigo);color:#fff;padding:13px 30px;border-radius:12px;font-size:.93rem;font-weight:500;font-family:'DM Sans',sans-serif;text-decoration:none;border:none;cursor:pointer;transition:all .3s;box-shadow:0 0 44px rgba(91,95,238,.27);position:relative;overflow:hidden}
.btn-hp::after{content:'';position:absolute;inset:0;background:linear-gradient(135deg,rgba(255,255,255,.1),transparent 60%);opacity:0;transition:opacity .3s}
.btn-hp:hover{background:#4951E8;transform:translateY(-3px);box-shadow:0 12px 52px rgba(91,95,238,.44)}
.btn-hp:hover::after{opacity:1}
.btn-hp svg,.btn-ho svg{transition:transform .3s}
.btn-hp:hover svg{transform:translateX(4px)}
.btn-ho{display:inline-flex;align-items:center;gap:8px;background:transparent;border:1px solid var(--border2);color:var(--text2);padding:13px 22px;border-radius:12px;font-size:.93rem;font-weight:400;font-family:'DM Sans',sans-serif;text-decoration:none;transition:all .25s;cursor:pointer}
.btn-ho:hover{border-color:rgba(34,211,238,.4);color:var(--cyan);transform:translateY(-2px)}

/* Hero scene */
.hero-scene{position:relative;width:100%;max-width:900px;animation:fadeUp .8s .4s ease both}
.scene-h{position:relative;width:100%;height:clamp(260px,48vw,415px)}
.float-card{position:absolute;background:rgba(14,14,26,.88);border:1px solid rgba(255,255,255,.08);border-radius:14px;padding:12px 16px;backdrop-filter:blur(18px);box-shadow:0 18px 54px rgba(0,0,0,.5),inset 0 1px 0 rgba(255,255,255,.05);animation:flt var(--dur,9s) var(--del,0s) ease-in-out infinite;white-space:nowrap}
@keyframes flt{0%,100%{transform:translateY(0)}50%{transform:translateY(var(--fy,-14px))}}
.fc-l{font-size:.59rem;font-weight:500;letter-spacing:.8px;text-transform:uppercase;color:var(--text3);margin-bottom:4px}
.fc-v{font-family:'Syne',sans-serif;font-weight:800;line-height:1}
.fc-s{font-size:.67rem;color:var(--text2);margin-top:3px}
@media(max-width:560px){.fc-side{display:none}.scene-h{height:240px}}
.scene-fade{position:absolute;bottom:0;left:0;right:0;height:90px;background:linear-gradient(to top,var(--black),transparent);pointer-events:none}

/* ══════════════════ STATS ══════════════════ */
.stats-wrap{position:relative;z-index:1;padding:0 clamp(20px,5vw,72px);margin-bottom:86px}
.stats-card{background:var(--card);border:1px solid var(--border);border-radius:20px;padding:clamp(22px,3.5vw,38px) clamp(22px,3.5vw,44px);display:grid;grid-template-columns:repeat(4,1fr);gap:0}
.sc{padding:0 clamp(14px,2.5vw,32px);border-right:1px solid var(--border)}
.sc:first-child{padding-left:0}.sc:last-child{border-right:none;padding-right:0}
.sn{font-family:'Syne',sans-serif;font-size:clamp(1.5rem,3.2vw,2.4rem);font-weight:800;line-height:1;color:var(--text)}
.sn em{color:var(--indigo-b);font-style:normal}
.sl{font-size:.74rem;color:var(--text3);margin-top:6px;font-weight:400}
@media(max-width:780px){.stats-card{grid-template-columns:1fr 1fr;gap:0}.sc{padding:clamp(12px,2.5vw,18px) clamp(12px,2.5vw,20px);border-right:none;border-bottom:1px solid var(--border)}.sc:nth-child(odd){padding-left:0;border-right:1px solid var(--border)}.sc:nth-child(even){padding-right:0}.sc:nth-last-child(-n+2){border-bottom:none}}
@media(max-width:400px){.stats-card{grid-template-columns:1fr}.sc{border-right:none !important;padding:14px 0 !important}.sc:last-child{border-bottom:none}}

/* ══════════════════ SECTION BASE ══════════════════ */
section{position:relative;z-index:1;padding:clamp(60px,8vw,96px) clamp(20px,6vw,96px)}
.chip{display:inline-flex;align-items:center;gap:6px;background:rgba(34,211,238,.06);border:1px solid rgba(34,211,238,.18);color:var(--cyan);padding:4px 12px;border-radius:100px;font-size:.67rem;font-weight:500;letter-spacing:.8px;text-transform:uppercase;margin-bottom:15px}
.sec-h{font-family:'Syne',sans-serif;font-size:clamp(1.75rem,4.2vw,3.3rem);font-weight:800;line-height:.95;letter-spacing:-1.5px;margin-bottom:13px}
.sec-p{font-size:.9rem;color:var(--text2);line-height:1.78;max-width:450px}
.reveal{opacity:0;transform:translateY(20px);transition:opacity .62s ease,transform .62s ease}
.reveal.in{opacity:1;transform:none}

/* HOW */
.how-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:3px;margin-top:48px}
.how-card{background:var(--card);border:1px solid var(--border);padding:clamp(22px,3vw,36px) clamp(18px,2.5vw,28px);position:relative;overflow:hidden;transition:border-color .3s,transform .35s}
.how-card:first-child{border-radius:18px 0 0 18px}.how-card:last-child{border-radius:0 18px 18px 0}
.how-card:hover{border-color:rgba(91,95,238,.44);transform:translateY(-5px);z-index:2}
.how-card::before{content:'';position:absolute;inset:0;background:linear-gradient(145deg,rgba(91,95,238,.05),transparent 60%);opacity:0;transition:opacity .35s}
.how-card:hover::before{opacity:1}
.how-n{font-family:'Syne',sans-serif;font-size:4.8rem;font-weight:800;color:var(--border);line-height:1;position:absolute;top:12px;right:16px;user-select:none}
.how-icon{width:46px;height:46px;border-radius:13px;display:flex;align-items:center;justify-content:center;margin-bottom:20px;position:relative;z-index:1}
.how-name{font-family:'Syne',sans-serif;font-size:1.08rem;font-weight:700;margin-bottom:9px;position:relative;z-index:1}
.how-desc{font-size:.84rem;color:var(--text2);line-height:1.7;position:relative;z-index:1}
@media(max-width:700px){.how-grid{grid-template-columns:1fr;gap:3px}.how-card:first-child{border-radius:18px 18px 0 0}.how-card:last-child{border-radius:0 0 18px 18px}}

/* BENTO */
.bento{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-top:48px}
.bc{background:var(--card);border:1px solid var(--border);border-radius:18px;padding:clamp(20px,2.8vw,28px);transition:border-color .3s,transform .3s;position:relative;overflow:hidden}
.bc:hover{border-color:var(--border2);transform:translateY(-4px)}
.bc.s2{grid-column:span 2}
.bc-tl{position:absolute;top:0;left:20px;right:20px;height:1px;background:linear-gradient(90deg,transparent,currentColor,transparent);opacity:0;transition:opacity .3s}
.bc:hover .bc-tl{opacity:.28}
.bc-icon{width:42px;height:42px;border-radius:11px;display:flex;align-items:center;justify-content:center;margin-bottom:16px}
.bc-name{font-family:'Syne',sans-serif;font-size:.98rem;font-weight:700;margin-bottom:8px}
.bc-desc{font-size:.82rem;color:var(--text2);line-height:1.7}
@media(max-width:840px){.bento{grid-template-columns:1fr 1fr}.bc.s2{grid-column:span 2}}
@media(max-width:500px){.bento{grid-template-columns:1fr}.bc.s2{grid-column:span 1}}
.gauge-mini{margin-top:16px}.gauge-mini svg{width:100%;max-width:240px}

/* TIERS */
.tiers-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-top:48px}
.tier{background:var(--card);border:1px solid var(--border);border-radius:18px;padding:clamp(18px,2.5vw,24px) clamp(16px,2vw,22px);position:relative;overflow:hidden;transition:transform .35s,border-color .3s}
.tier:hover{transform:translateY(-6px)}
.tier-glow{position:absolute;width:140px;height:140px;border-radius:50%;bottom:-52px;right:-52px;filter:blur(42px);opacity:.12;pointer-events:none}
.tl{font-family:'Syne',sans-serif;font-size:2.7rem;font-weight:800;line-height:1;margin-bottom:2px}
.tn{font-size:.6rem;font-weight:500;text-transform:uppercase;letter-spacing:.9px;margin-bottom:15px}
.tb{font-size:1.35rem;font-weight:700;font-family:'Syne',sans-serif;margin-bottom:2px}
.tbl{font-size:.63rem;color:var(--text3);margin-bottom:12px}
.tr{font-size:.77rem;color:var(--text2)}
.ts{margin-top:9px;font-size:.63rem;color:var(--text3)}
@media(max-width:800px){.tiers-grid{grid-template-columns:1fr 1fr}}
@media(max-width:420px){.tiers-grid{grid-template-columns:1fr}}

/* CTA */
.cta-outer{position:relative;z-index:1;padding:clamp(40px,6vw,64px) clamp(20px,6vw,80px) clamp(60px,10vw,105px)}
.cta-box{background:linear-gradient(135deg,rgba(91,95,238,.1),rgba(14,14,26,.8) 50%,rgba(34,211,238,.05));border:1px solid rgba(91,95,238,.24);border-radius:26px;padding:clamp(48px,8vw,76px) clamp(22px,6vw,56px);text-align:center;position:relative;overflow:hidden}
.cta-box::before{content:'';position:absolute;inset:-1px;border-radius:26px;background:linear-gradient(135deg,rgba(91,95,238,.17),transparent 40%,rgba(34,211,238,.07));z-index:0;pointer-events:none}
.cta-box>*{position:relative;z-index:1}
.cta-sh{position:absolute;border-radius:50%;background:radial-gradient(circle,rgba(91,95,238,.13),transparent 70%);pointer-events:none}
.cs1{width:360px;height:360px;left:-95px;top:-120px;animation:d1 24s ease-in-out infinite}
.cs2{width:260px;height:260px;right:-65px;bottom:-85px;background:radial-gradient(circle,rgba(34,211,238,.09),transparent 70%);animation:d2 20s ease-in-out infinite}
.cta-tag{position:absolute;background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.22);color:var(--emerald);font-size:.64rem;font-weight:500;padding:5px 11px;border-radius:100px}
.ct1{top:24px;left:30px}.ct2{top:24px;right:30px}.ct3{bottom:24px;left:30px}.ct4{bottom:24px;right:30px}
@media(max-width:600px){.cta-tag{display:none}.cta-box{padding:42px 20px}}
.cta-h{font-family:'Syne',sans-serif;font-size:clamp(1.75rem,4.2vw,3.4rem);font-weight:800;letter-spacing:-1.5px;line-height:.95;margin-bottom:14px}
.cta-sub{font-size:.92rem;color:var(--text2);max-width:430px;margin:0 auto 30px;line-height:1.75}
.cta-btns{display:flex;gap:12px;justify-content:center;flex-wrap:wrap}

/* FOOTER */
footer{position:relative;z-index:1;border-top:1px solid var(--border);padding:28px clamp(20px,5vw,72px);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px}
.ft-logo{font-family:'Syne',sans-serif;font-size:.87rem;font-weight:800;color:var(--text3)}
.ft-links{display:flex;gap:18px;flex-wrap:wrap}
.ft-links a{font-size:.74rem;color:var(--text3);text-decoration:none;transition:color .2s}
.ft-links a:hover{color:var(--text2)}
.ft-copy{font-size:.69rem;color:var(--text3)}

@keyframes fadeUp{from{opacity:0;transform:translateY(26px)}to{opacity:1;transform:none}}
@keyframes spin{to{transform:translate(-50%,-50%) rotate(360deg)}}
@keyframes spinR{to{transform:translate(-50%,-50%) rotate(-360deg)}}
</style>
</head>
<body>
<div class="glow g1"></div><div class="glow g2"></div><div class="glow g3"></div>

<!-- ══ NAV ══ -->
<nav id="nav">
  <a href="{{ route('landing') }}" class="logo">
    <!-- Real Acredo AC logo: 3D interlocked A + sweeping C -->
    <svg class="logo-mark" viewBox="0 0 46 46" fill="none" xmlns="http://www.w3.org/2000/svg">
      <defs>
        <linearGradient id="la-a" x1="5" y1="3" x2="20" y2="44" gradientUnits="userSpaceOnUse">
          <stop offset="0%"  stop-color="#A5AEFF"/><stop offset="48%" stop-color="#5458EA"/><stop offset="100%" stop-color="#2A30B5"/>
        </linearGradient>
        <linearGradient id="la-c" x1="22" y1="7" x2="42" y2="40" gradientUnits="userSpaceOnUse">
          <stop offset="0%"  stop-color="#80EEFF"/><stop offset="52%" stop-color="#22D3EE"/><stop offset="100%" stop-color="#0680A0"/>
        </linearGradient>
        <linearGradient id="la-sh" x1="0" y1="0" x2="1" y2="1" gradientUnits="objectBoundingBox">
          <stop offset="0%" stop-color="#0D1266" stop-opacity=".7"/><stop offset="100%" stop-color="#05082E" stop-opacity=".4"/>
        </linearGradient>
      </defs>
      <!-- C shadow depth -->
      <path d="M 38,8 A 17,17 0 1,0 38,38" stroke="#0B4F66" stroke-width="8" fill="none" stroke-linecap="round" opacity=".5" transform="translate(1,1)"/>
      <!-- C main -->
      <path d="M 38,8 A 17,17 0 1,0 38,38" stroke="url(#la-c)" stroke-width="7" fill="none" stroke-linecap="round"/>
      <!-- C highlight -->
      <path d="M 38,8 A 17,17 0 0,1 38,38" stroke="rgba(200,245,255,.2)" stroke-width="1.5" fill="none" stroke-linecap="round"/>
      <!-- A left leg shadow -->
      <polygon points="16,4 22,4 12,43 6,43" fill="url(#la-sh)" transform="translate(1,1)"/>
      <!-- A left leg -->
      <polygon points="15,3 21,3 11,42 5,42" fill="url(#la-a)"/>
      <!-- A right leg shadow -->
      <polygon points="18,4 24,4 31,43 25,43" fill="url(#la-sh)" transform="translate(1,1)"/>
      <!-- A right leg -->
      <polygon points="17,3 23,3 30,42 24,42" fill="url(#la-a)"/>
      <!-- A crossbar shadow -->
      <rect x="8" y="25" width="20" height="6" fill="url(#la-sh)" transform="translate(1,1)"/>
      <!-- A crossbar -->
      <rect x="7" y="24" width="20" height="6" fill="url(#la-a)"/>
      <!-- A peak highlight -->
      <polygon points="15,3 21,3 19.5,7 16.5,7" fill="rgba(180,190,255,.28)"/>
    </svg>
    <span class="logo-text">ACREDO</span>
  </a>

  <!-- Center nav links -->
  <div class="nav-center">
    <ul class="nav-links">
      <li><a href="#how">How it works</a></li>
      <li><a href="#features">Features</a></li>
      <li><a href="#tiers">Tiers</a></li>
      <li><a href="#docs">Docs</a></li>
    </ul>
  </div>

  <!-- Right actions -->
  <div class="nav-right">
    <a href="{{ route('dashboard') }}" class="btn-base btn-ghost">Dashboard</a>
    <a href="{{ route('dashboard') }}" class="btn-base btn-launch">
      Launch App
      <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
    </a>
    <button class="nav-toggle" id="nav-toggle" aria-label="Open menu">
      <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <line x1="3" y1="6"  x2="21" y2="6"/>
        <line x1="3" y1="12" x2="21" y2="12"/>
        <line x1="3" y1="18" x2="21" y2="18"/>
      </svg>
    </button>
  </div>
</nav>

<!-- Mobile drawer -->
<div class="nav-drawer" id="nav-drawer">
  <a href="#how"      class="nd-link" onclick="closeDrawer()">How it works</a>
  <a href="#features" class="nd-link" onclick="closeDrawer()">Features</a>
  <a href="#tiers"    class="nd-link" onclick="closeDrawer()">Tiers</a>
  <a href="#docs"     class="nd-link" onclick="closeDrawer()">Docs</a>
  <div class="nd-btns">
    <a href="{{ route('dashboard') }}" class="btn-base btn-ghost nd-link">Dashboard</a>
    <a href="{{ route('dashboard') }}" class="btn-base btn-launch nd-link">Launch App →</a>
  </div>
</div>

<!-- ══ HERO ══ -->
<section class="hero">
  <div class="hero-spot"></div>
  <div class="hero-grid-bg"></div>

  <div class="eyebrow">
    <span class="eyebrow-dot">
      <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3.5"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
    </span>
    Built on Stacks · Bitcoin-Secured DeFi
  </div>

  <h1 class="hero-h1">
    <span class="h1a">Credit moves at</span>
    <span class="h1b">the speed of trust.</span>
  </h1>

  <p class="hero-sub">Acredo is a structured credit and yield protocol on Stacks. Borrow against your on-chain reputation, NFTs, or projected yield — no overcollateralisation, no gatekeepers.</p>

  <div class="hero-cta">
    <a href="{{ route('dashboard') }}" class="btn-hp">
      Get Started Free
      <svg width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
    </a>
    <a href="#how" class="btn-ho">
      <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
      How it works
    </a>
  </div>

  <div class="hero-scene">
    <div class="scene-h">
      <!-- Orbit rings -->
      <div style="position:absolute;left:50%;top:50%;width:470px;height:470px;margin:-235px 0 0 -235px;border-radius:50%;border:1px dashed rgba(255,255,255,.045);animation:spin 55s linear infinite;transform-origin:50% 50%"></div>
      <div style="position:absolute;left:50%;top:50%;width:330px;height:330px;margin:-165px 0 0 -165px;border-radius:50%;border:1px dashed rgba(91,95,238,.1);animation:spinR 36s linear infinite;transform-origin:50% 50%">
        <div style="position:absolute;top:-5px;left:50%;width:10px;height:10px;margin-left:-5px;border-radius:50%;background:#5B5FEE;box-shadow:0 0 11px #5B5FEE"></div>
      </div>
      <div style="position:absolute;left:50%;top:50%;width:210px;height:210px;margin:-105px 0 0 -105px;border-radius:50%;border:1px dashed rgba(34,211,238,.1);animation:spin 22s linear infinite;transform-origin:50% 50%">
        <div style="position:absolute;top:-4px;left:50%;width:8px;height:8px;margin-left:-4px;border-radius:50%;background:#22D3EE;box-shadow:0 0 9px #22D3EE"></div>
      </div>
      <!-- Polyhedron -->
      <div style="position:absolute;left:50%;top:50%;transform:translate(-50%,-52%);width:250px;height:250px;animation:flt 10s ease-in-out infinite">
        <svg viewBox="0 0 250 250" fill="none" xmlns="http://www.w3.org/2000/svg">
          <defs>
            <radialGradient id="rg1" cx="38%" cy="32%"><stop offset="0%" stop-color="#818CF8" stop-opacity=".95"/><stop offset="55%" stop-color="#5B5FEE" stop-opacity=".55"/><stop offset="100%" stop-color="#22D3EE" stop-opacity=".08"/></radialGradient>
            <radialGradient id="rg2" cx="62%" cy="58%"><stop offset="0%" stop-color="#22D3EE" stop-opacity=".6"/><stop offset="100%" stop-color="#818CF8" stop-opacity="0"/></radialGradient>
            <filter id="pf" x="-15%" y="-15%" width="130%" height="130%"><feGaussianBlur stdDeviation="4.5" result="b"/><feMerge><feMergeNode in="b"/><feMergeNode in="SourceGraphic"/></feMerge></filter>
          </defs>
          <polygon points="125,21 205,78 198,163 125,198 52,163 45,78" fill="url(#rg1)" stroke="rgba(129,140,248,.43)" stroke-width="1" filter="url(#pf)"/>
          <polygon points="125,21 205,78 125,123" fill="rgba(91,95,238,.37)" stroke="rgba(129,140,248,.33)" stroke-width=".7"/>
          <polygon points="125,21 45,78  125,123" fill="rgba(34,211,238,.17)" stroke="rgba(34,211,238,.27)" stroke-width=".7"/>
          <polygon points="205,78 198,163 125,123" fill="rgba(91,95,238,.17)" stroke="rgba(91,95,238,.24)" stroke-width=".7"/>
          <polygon points="45,78  52,163 125,123" fill="rgba(124,58,237,.21)" stroke="rgba(124,58,237,.27)" stroke-width=".7"/>
          <polygon points="198,163 125,198 52,163 125,123" fill="rgba(34,211,238,.1)" stroke="rgba(34,211,238,.17)" stroke-width=".7"/>
          <circle cx="125" cy="117" r="25" fill="url(#rg2)" opacity=".63"/>
          <circle cx="125" cy="117" r="7"  fill="rgba(255,255,255,.13)"/>
          <circle cx="125" cy="125" r="74" stroke="url(#rg1)" stroke-width="1" fill="none" stroke-dasharray="3.5 12.5" style="animation:spin 20s linear infinite;transform-origin:125px 125px"/>
        </svg>
      </div>
      <!-- Float cards -->
      <div class="float-card" style="left:0%;top:22%;--dur:9s;--del:0s;--fy:-14px">
        <div class="fc-l">Reputation Score</div>
        <div class="fc-v" style="font-size:1.75rem;background:linear-gradient(135deg,#818CF8,#22D3EE);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">780</div>
        <div class="fc-s">Tier A · Top 15%</div>
      </div>
      <div class="float-card fc-side" style="right:0%;top:12%;--dur:12s;--del:1.5s;--fy:-12px">
        <div class="fc-l">Vault APY</div>
        <div class="fc-v" style="font-size:1.75rem;color:#10B981">12.4%</div>
        <div class="fc-s">USDCx Yield Vault</div>
      </div>
      <div class="float-card fc-side" style="right:3%;bottom:18%;--dur:7s;--del:3s;--fy:-10px;padding:10px 14px">
        <div style="display:flex;align-items:center;gap:7px">
          <div style="width:6px;height:6px;border-radius:50%;background:#10B981;box-shadow:0 0 7px #10B981;flex-shrink:0"></div>
          <span style="font-size:.69rem;font-weight:500;color:var(--text2)">Tx Confirmed</span>
        </div>
        <div style="font-family:'JetBrains Mono',monospace;font-size:.58rem;color:var(--text3);margin-top:3px">0x4f7a…c92b</div>
      </div>
      <div class="float-card" style="left:2%;bottom:22%;--dur:11s;--del:2s;--fy:-17px;padding:10px 14px">
        <div class="fc-l">Borrow Limit</div>
        <div style="font-family:'JetBrains Mono',monospace;font-size:.88rem;font-weight:600;color:#A78BFA">2.5 sBTC</div>
      </div>
    </div>
    <div class="scene-fade"></div>
  </div>
</section>

<!-- ══ STATS ══ -->
<div class="stats-wrap">
  <div class="stats-card" id="stats-card">
    <div class="sc"><div class="sn">$<em class="cnt" data-to="4.2" data-d="1">4.2</em>M</div><div class="sl">Total Value Locked</div></div>
    <div class="sc"><div class="sn"><em class="cnt" data-to="1847" data-d="0">1,847</em></div><div class="sl">Active Borrowers</div></div>
    <div class="sc"><div class="sn"><em class="cnt" data-to="12.4" data-d="1">12.4</em>%</div><div class="sl">Average Vault APY</div></div>
    <div class="sc"><div class="sn" style="color:#10B981"><em class="cnt" data-to="0" data-d="0">0</em></div><div class="sl">Protocol Exploits</div></div>
  </div>
</div>

<!-- ══ HOW IT WORKS ══ -->
<section id="how">
  <div class="reveal">
    <div class="chip">⚡ Protocol</div>
    <div class="sec-h">Three ways to<br><span style="color:var(--indigo-b)">access credit.</span></div>
    <p class="sec-p">Acredo unlocks capital from your on-chain identity, assets, and yield streams — without overcollateralisation traps.</p>
  </div>
  <div class="how-grid">
    <div class="how-card reveal" style="transition-delay:.07s">
      <div class="how-n">01</div>
      <div class="how-icon" style="background:rgba(91,95,238,.12)"><svg width="21" height="21" fill="none" viewBox="0 0 24 24" stroke="#818CF8" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg></div>
      <div class="how-name">Reputation Loans</div>
      <p class="how-desc">Wallet history — age, transactions, DeFi interactions, repayments — generates a 0–1000 score. Borrow up to 2.5 sBTC instantly.</p>
    </div>
    <div class="how-card reveal" style="transition-delay:.13s">
      <div class="how-n">02</div>
      <div class="how-icon" style="background:rgba(34,211,238,.1)"><svg width="21" height="21" fill="none" viewBox="0 0 24 24" stroke="#22D3EE" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><path d="M14 17h7M17.5 14v7"/></svg></div>
      <div class="how-name">NFT-Backed Loans</div>
      <p class="how-desc">Lock a Stacks NFT and borrow up to 40% of its verified floor price. NFT stays safe in escrow until repaid.</p>
    </div>
    <div class="how-card reveal" style="transition-delay:.19s">
      <div class="how-n">03</div>
      <div class="how-icon" style="background:rgba(16,185,129,.1)"><svg width="21" height="21" fill="none" viewBox="0 0 24 24" stroke="#10B981" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div>
      <div class="how-name">Yield-Backed Loans</div>
      <p class="how-desc">Deposit USDCx into the Vault and borrow up to 50% of your projected yield. Earn while you borrow — no principal at risk.</p>
    </div>
  </div>
</section>

<!-- ══ FEATURES ══ -->
<section id="features">
  <div class="reveal">
    <div class="chip">🔒 Built Different</div>
    <div class="sec-h">DeFi credit,<br><span style="color:var(--indigo-b)">done right.</span></div>
    <p class="sec-p">Every feature engineered for trust-minimised, production-grade lending on Bitcoin's most capable L2.</p>
  </div>
  <div class="bento">
    <div class="bc s2 reveal" style="background:linear-gradient(135deg,rgba(91,95,238,.07),rgba(14,14,26,.8));transition-delay:.06s">
      <div class="bc-tl" style="color:#818CF8"></div>
      <div class="bc-icon" style="background:rgba(91,95,238,.12)"><svg width="21" height="21" fill="none" viewBox="0 0 24 24" stroke="#818CF8" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg></div>
      <div class="bc-name">Live Health Factor Gauge</div>
      <p class="bc-desc">Animated semicircle updates in real time as you adjust borrow amounts. Know your liquidation risk before signing.</p>
      <div class="gauge-mini"><svg viewBox="0 0 240 96" fill="none"><defs><linearGradient id="gg3"><stop offset="0%" stop-color="#EF4444"/><stop offset="45%" stop-color="#F59E0B"/><stop offset="100%" stop-color="#10B981"/></linearGradient></defs><path d="M 20,86 A 100,100 0 0,1 220,86" stroke="rgba(255,255,255,.06)" stroke-width="10" fill="none" stroke-linecap="round"/><path d="M 20,86 A 100,100 0 0,1 220,86" stroke="url(#gg3)" stroke-width="10" fill="none" stroke-linecap="round" stroke-dasharray="314" stroke-dashoffset="82"/><line x1="120" y1="86" x2="120" y2="22" stroke="white" stroke-width="2.5" stroke-linecap="round" style="transform-origin:120px 86px;transform:rotate(30deg)"/><circle cx="120" cy="86" r="6" fill="#1C1C2E" stroke="white" stroke-width="1.8"/><text x="120" y="72" text-anchor="middle" font-size="14" font-weight="800" fill="#10B981" font-family="Syne,sans-serif">2.8×</text><text x="22" y="103" font-size="9" fill="#EF4444" font-family="DM Sans,sans-serif">Critical</text><text x="120" y="14" font-size="9" fill="#F59E0B" font-family="DM Sans,sans-serif" text-anchor="middle">Caution</text><text x="218" y="103" font-size="9" fill="#10B981" font-family="DM Sans,sans-serif" text-anchor="end">Safe</text></svg></div>
    </div>
    <div class="bc reveal" style="transition-delay:.1s"><div class="bc-tl" style="color:#22D3EE"></div><div class="bc-icon" style="background:rgba(34,211,238,.1)"><svg width="21" height="21" fill="none" viewBox="0 0 24 24" stroke="#22D3EE" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div><div class="bc-name">On-Chain Reputation</div><p class="bc-desc">Fully verifiable 0–1000 score from wallet age, BNS ownership, and repayment history.</p></div>
    <div class="bc reveal" style="transition-delay:.14s"><div class="bc-tl" style="color:#F5A623"></div><div class="bc-icon" style="background:rgba(245,166,35,.1)"><svg width="21" height="21" fill="none" viewBox="0 0 24 24" stroke="#F5A623" stroke-width="2"><path d="M3 3h18M3 9h18M3 15h18M3 21h18"/></svg></div><div class="bc-name">Open Marketplace</div><p class="bc-desc">Lenders browse verified loan requests filtered by tier, rate, and BNS name.</p></div>
    <div class="bc reveal" style="transition-delay:.18s"><div class="bc-tl" style="color:#A78BFA"></div><div class="bc-icon" style="background:rgba(167,139,250,.1)"><svg width="21" height="21" fill="none" viewBox="0 0 24 24" stroke="#A78BFA" stroke-width="2"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg></div><div class="bc-name">Swap-Ready Contracts</div><p class="bc-desc">All blockchain calls stubbed in one file. Replace with real Clarity contracts instantly.</p></div>
    <div class="bc reveal" style="transition-delay:.22s"><div class="bc-tl" style="color:#10B981"></div><div class="bc-icon" style="background:rgba(16,185,129,.1)"><svg width="21" height="21" fill="none" viewBox="0 0 24 24" stroke="#10B981" stroke-width="2"><rect x="5" y="2" width="14" height="20" rx="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg></div><div class="bc-name">Mobile-First</div><p class="bc-desc">Sidebar collapses to bottom nav. Every page works flawlessly on any screen size.</p></div>
  </div>
</section>

<!-- ══ TIERS ══ -->
<section id="tiers" style="text-align:center">
  <div class="reveal"><div class="chip" style="margin:0 auto 15px">🏆 Reputation Tiers</div><div class="sec-h" style="max-width:none">Your score. <span style="color:var(--indigo-b)">Your credit limit.</span></div><p class="sec-p" style="margin:0 auto;text-align:center">Build your on-chain reputation and unlock progressively better rates and limits.</p></div>
  <div class="tiers-grid">
    <div class="tier reveal" style="border-color:rgba(16,185,129,.22);background:linear-gradient(145deg,rgba(16,185,129,.05),var(--card));transition-delay:.06s"><div class="tier-glow" style="background:#10B981"></div><div class="tl" style="color:#34D399">A</div><div class="tn" style="color:#34D399">Elite</div><div class="tb">2.5 sBTC</div><div class="tbl">Max borrow</div><div class="tr">5% – 12% interest</div><div class="ts">Score 750 – 1000</div></div>
    <div class="tier reveal" style="border-color:rgba(245,158,11,.18);background:linear-gradient(145deg,rgba(245,158,11,.04),var(--card));transition-delay:.12s"><div class="tier-glow" style="background:#F59E0B"></div><div class="tl" style="color:#FCD34D">B</div><div class="tn" style="color:#FCD34D">Established</div><div class="tb">1.0 sBTC</div><div class="tbl">Max borrow</div><div class="tr">10% – 18% interest</div><div class="ts">Score 500 – 749</div></div>
    <div class="tier reveal" style="border-color:rgba(249,115,22,.16);background:linear-gradient(145deg,rgba(249,115,22,.04),var(--card));transition-delay:.18s"><div class="tier-glow" style="background:#F97316"></div><div class="tl" style="color:#FB923C">C</div><div class="tn" style="color:#FB923C">Emerging</div><div class="tb">0.3 sBTC</div><div class="tbl">Max borrow</div><div class="tr">15% – 22% interest</div><div class="ts">Score 250 – 499</div></div>
    <div class="tier reveal" style="border-color:rgba(239,68,68,.14);background:linear-gradient(145deg,rgba(239,68,68,.04),var(--card));transition-delay:.24s"><div class="tier-glow" style="background:#EF4444"></div><div class="tl" style="color:#F87171">D</div><div class="tn" style="color:#F87171">New</div><div class="tb">0.05 sBTC</div><div class="tbl">Max borrow</div><div class="tr">20% – 25% interest</div><div class="ts">Score 0 – 249</div></div>
  </div>
</section>

<!-- ══ CTA ══ -->
<div class="cta-outer">
  <div class="cta-box reveal">
    <div class="cta-sh cs1"></div><div class="cta-sh cs2"></div>
    <span class="cta-tag ct1">🔐 Non-custodial</span><span class="cta-tag ct2">⚡ Instant loans</span>
    <span class="cta-tag ct3">🛡 Bitcoin-secured</span><span class="cta-tag ct4">📈 12.4% APY</span>
    <div class="chip" style="margin:0 auto 16px">Ready to start?</div>
    <div class="cta-h">Credit shouldn't require<br><span style="-webkit-text-fill-color:transparent;-webkit-background-clip:text;background-clip:text;background-image:linear-gradient(105deg,#818CF8,#22D3EE)">permission.</span></div>
    <p class="cta-sub">Connect your Hiro Wallet and access your reputation score, borrow limits, and yield vaults in under 30 seconds.</p>
    <div class="cta-btns">
      <a href="{{ route('dashboard') }}" class="btn-hp" style="font-size:.98rem;padding:14px 34px">
        Launch App — Get Started
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
      </a>
      <a href="#how" class="btn-ho">Read the Docs</a>
    </div>
  </div>
</div>

<!-- ══ FOOTER ══ -->
<footer>
  <div class="ft-logo">⬡ ACREDO PROTOCOL</div>
  <div class="ft-links"><a href="#">Documentation</a><a href="#">GitHub</a><a href="#">Discord</a><a href="#">Twitter / X</a></div>
  <div class="ft-copy">© 2026 Acredo. Built on Stacks.</div>
</footer>

<script>
window.addEventListener('scroll',()=>{document.getElementById('nav').classList.toggle('scrolled',scrollY>50)},{passive:true});

const toggle=document.getElementById('nav-toggle');
const drawer=document.getElementById('nav-drawer');
toggle.addEventListener('click',e=>{e.stopPropagation();drawer.classList.toggle('open')});
function closeDrawer(){drawer.classList.remove('open')}
document.addEventListener('click',e=>{if(!e.target.closest('#nav')&&!e.target.closest('#nav-drawer'))closeDrawer()});

const io=new IntersectionObserver(es=>es.forEach(e=>{if(e.isIntersecting){e.target.classList.add('in');io.unobserve(e.target)}}),{threshold:.1});
document.querySelectorAll('.reveal').forEach(el=>io.observe(el));

function animCount(el){
  const to=parseFloat(el.dataset.to),dec=parseInt(el.dataset.d)||0;
  let s=null;
  const fmt=v=>dec?v.toFixed(dec):Math.round(v).toLocaleString();
  const step=ts=>{if(!s)s=ts;const p=Math.min((ts-s)/1600,1);const e=1-Math.pow(1-p,4);el.textContent=fmt(e*to);if(p<1)requestAnimationFrame(step)};
  requestAnimationFrame(step);
}
const cio=new IntersectionObserver(es=>es.forEach(e=>{if(e.isIntersecting){e.target.querySelectorAll('.cnt').forEach(animCount);cio.unobserve(e.target)}}),{threshold:.5});
const sc=document.getElementById('stats-card');if(sc)cio.observe(sc);
</script>
</body>
</html>
