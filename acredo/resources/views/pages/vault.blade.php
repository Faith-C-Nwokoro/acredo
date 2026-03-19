@extends('layouts.app')
@section('title','Yield Vault')
@section('page-title','Yield Vault')
@section('content')
@if(!$connected)
<div class="connect-prompt"><svg width="46" height="46" fill="none" viewBox="0 0 24 24" stroke="var(--primary)" stroke-width="1.5"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg><h2>Connect Wallet</h2><p>Deposit USDCx and earn yield, then borrow against your projected earnings.</p><button class="btn btn-primary" onclick="toggleWallet()">Connect Wallet</button></div>
@else
<div class="two-col">
    <div>
        <div class="card mb-4 score-glow">
            <div class="flex items-center justify-between mb-4"><div class="section-title" style="margin-bottom:0">Your Position</div><span class="badge badge-success">Active</span></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:18px">
                <div><div class="card-title">Deposited</div><div style="font-size:1.35rem;font-weight:800" class="mono">{{ $vault['deposited'] }} USDCx</div></div>
                <div><div class="card-title">Current APY</div><div style="font-size:1.35rem;font-weight:800;color:var(--success)">{{ $vault['apy'] }}%</div></div>
                <div><div class="card-title">Accrued Yield</div><div style="font-size:1rem;font-weight:700;color:var(--success)" class="mono">+{{ $vault['accruedYield'] }} USDCx</div><div class="text-xs text-muted">last 3 days</div></div>
                <div><div class="card-title">Projected 90d</div><div style="font-size:1rem;font-weight:700;color:var(--accent)" class="mono">{{ $vault['projected90d'] }} USDCx</div></div>
            </div>
            <div class="chart-container"><canvas id="vault-chart"></canvas></div>
        </div>
        <div class="card mb-4">
            <div class="section-title">Deposit to Vault</div>
            <div class="input-group"><label class="input-label">Amount (USDCx)</label><div style="position:relative"><input type="number" class="input" id="dep-amt" placeholder="0.00" min="1" oninput="calcDep()"><button onclick="document.getElementById('dep-amt').value=10000;calcDep()" style="position:absolute;right:9px;top:50%;transform:translateY(-50%);background:rgba(99,102,241,.2);color:var(--primary);border:none;border-radius:6px;padding:3px 7px;font-size:.7rem;font-weight:600;cursor:pointer">MAX</button></div></div>
            <div class="input-group"><label class="input-label">Lock Duration</label><div class="seg-control" id="dep-dur"><button class="seg-btn" onclick="setSegActive(this,'dep-dur');calcDep()">30d</button><button class="seg-btn" onclick="setSegActive(this,'dep-dur');calcDep()">60d</button><button class="seg-btn active" onclick="setSegActive(this,'dep-dur');calcDep()">90d</button><button class="seg-btn" onclick="setSegActive(this,'dep-dur');calcDep()">180d</button></div></div>
            <div class="summary-box mb-4" id="dep-prev" style="display:none"><div class="summary-row"><span>Projected Yield</span><strong class="mono" id="dp-y" style="color:var(--success)">—</strong></div><div class="summary-row"><span>Borrow Capacity</span><strong class="mono" id="dp-b" style="color:var(--primary)">—</strong></div></div>
            <button class="btn btn-primary btn-full" onclick="sendTx('depositVault')"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12l7-7 7 7"/></svg>Deposit to Vault</button>
        </div>
        <div class="card">
            <button class="collapsible-btn btn-full" onclick="toggleCollapsible('wd-panel')" style="font-size:.88rem;font-weight:600;color:var(--text);justify-content:space-between;width:100%"><span>Withdraw</span><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg></button>
            <div class="collapsible-content" id="wd-panel">
                <div class="divider"></div>
                <div class="summary-box mb-4" style="background:rgba(245,158,11,.05);border-color:rgba(245,158,11,.3)"><div style="display:flex;gap:8px;padding:7px 0"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#F59E0B" stroke-width="2" style="flex-shrink:0;margin-top:1px"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg><span class="text-sm" style="color:var(--warning)">Active yield borrow of 100 USDCx may affect health factor.</span></div></div>
                <div class="input-group"><label class="input-label">Amount to Withdraw</label><input type="number" class="input" placeholder="0.00" max="10000"></div>
                <button class="btn btn-ghost btn-full" onclick="sendTx('withdrawVault')">Withdraw USDCx</button>
            </div>
        </div>
    </div>
    <div>
        <div class="card mb-4">
            <div class="section-title">Borrow Against Yield</div>
            <div class="summary-box mb-4"><div class="summary-row"><span>Vault Balance</span><strong class="mono">10,000 USDCx</strong></div><div class="summary-row"><span>APY</span><strong style="color:var(--success)">12.4%</strong></div><div class="summary-row"><span>Projected Yield (90d)</span><strong class="mono">305 USDCx</strong></div><div class="summary-row"><span>Borrow Limit (50%)</span><strong class="mono">152.50 USDCx</strong></div></div>
            <div class="input-group"><label class="input-label">Duration</label><div class="seg-control" id="vb-dur"><button class="seg-btn" onclick="setSegActive(this,'vb-dur')">30d</button><button class="seg-btn" onclick="setSegActive(this,'vb-dur')">60d</button><button class="seg-btn active" onclick="setSegActive(this,'vb-dur')">90d</button><button class="seg-btn" onclick="setSegActive(this,'vb-dur')">180d</button></div></div>
            <div class="input-group"><label class="input-label">Borrow Amount (USDCx)</label><input type="number" class="input" id="vb-amt" value="50" min="1" max="52.5" oninput="updateVHF()"></div>
            <div class="card" style="background:var(--elevated);text-align:center;margin-bottom:14px">
                <div class="card-title">Health Factor</div>
                <div class="gauge-wrap"><svg viewBox="0 0 200 108" fill="none"><defs><linearGradient id="vhg"><stop offset="0%" stop-color="#EF4444"/><stop offset="45%" stop-color="#F59E0B"/><stop offset="100%" stop-color="#10B981"/></linearGradient></defs><path d="M 20,100 A 80,80 0 0,1 180,100" stroke="#2A2A3A" stroke-width="11" fill="none" stroke-linecap="round"/><path d="M 20,100 A 80,80 0 0,1 180,100" stroke="url(#vhg)" stroke-width="11" fill="none" stroke-linecap="round" stroke-dasharray="251" stroke-dashoffset="63"/><line id="vb-needle" x1="100" y1="100" x2="100" y2="28" stroke="white" stroke-width="3" stroke-linecap="round" style="transform-origin:100px 100px;transform:rotate(42deg);transition:transform .6s"/><circle cx="100" cy="100" r="6" fill="#1A1A24" stroke="white" stroke-width="2"/><text x="100" y="88" text-anchor="middle" font-size="14" font-weight="800" fill="#10B981" font-family="Inter" id="vhf-txt">2.8x</text></svg>
                    <div class="gauge-val" id="vhf-big" style="color:var(--success)">2.8x</div>
                    <div class="gauge-label">Current: <span id="vhf-st" style="color:var(--success)">Safe</span></div>
                </div>
            </div>
            <button class="btn btn-primary btn-full" onclick="sendTx('borrowYield')">Borrow from Pool</button>
        </div>
        <div class="card">
            <div class="section-title">Active Borrow</div>
            <div class="summary-box mb-4"><div class="summary-row"><span>Borrowed</span><strong class="mono">100 USDCx</strong></div><div class="summary-row"><span>Repay by</span><strong>Jun 19, 2026</strong></div><div class="summary-row"><span>Interest owed</span><strong class="mono">1.03 USDCx</strong></div></div>
            <button class="btn btn-success btn-full" onclick="sendTx('repayYield')">Repay 101.03 USDCx</button>
        </div>
    </div>
</div>
@endif
@endsection
@push('scripts')
<script>
const vLabels=@json($chartData['labels']);const vYields=@json($chartData['yields']);
window.addEventListener('load',()=>{const ctx=document.getElementById('vault-chart');if(!ctx)return;new Chart(ctx,{type:'line',data:{labels:vLabels,datasets:[{label:'Accrued Yield',data:vYields,borderColor:'#10B981',backgroundColor:'rgba(16,185,129,.07)',borderWidth:2,fill:true,tension:0.4,pointRadius:0}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{x:{ticks:{color:'#475569',font:{size:9},maxTicksLimit:6},grid:{color:'rgba(42,42,58,.4)'}},y:{ticks:{color:'#475569',font:{size:9}},grid:{color:'rgba(42,42,58,.4)'}}}}})});
function calcDep(){const a=parseFloat(document.getElementById('dep-amt').value)||0;if(!a)return;const py=(a*0.124*90/365).toFixed(2);const bc=(py*0.5).toFixed(2);document.getElementById('dp-y').textContent=py+' USDCx';document.getElementById('dp-b').textContent=bc+' USDCx';document.getElementById('dep-prev').style.display=''}
function updateVHF(){const b=parseFloat(document.getElementById('vb-amt').value)||0;const hf=(152.5/(100+b));const hs=hf.toFixed(2)+'x';document.getElementById('vhf-txt').textContent=hs;document.getElementById('vhf-big').textContent=hs;const n=document.getElementById('vb-needle'),st=document.getElementById('vhf-st'),big=document.getElementById('vhf-big');if(hf>=1.5){big.style.color='var(--success)';st.textContent='Safe';st.style.color='var(--success)';n.style.transform=`rotate(${Math.min(130,45+((hf-1.5)/1.5)*80)}deg)`}else if(hf>=1){big.style.color='var(--warning)';st.textContent='Caution';st.style.color='var(--warning)';n.style.transform=`rotate(${-25+((hf-1)/.5)*70}deg)`}else{big.style.color='var(--danger)';st.textContent='At Risk';st.style.color='var(--danger)';n.style.transform='rotate(-80deg)'}}
</script>
@endpush
