@extends('layouts.app')
@section('title','Liquidity Pool')
@section('page-title','Liquidity Pool')
@section('content')
<div class="stat-grid mb-4" style="grid-template-columns:repeat(3,1fr)">
    <div class="card"><div class="card-title">Total Liquidity</div><div class="card-value mono">{{ $metrics['totalLiquidity'] }} <span style="font-size:.85rem;color:var(--text-2)">USDCx</span></div><div class="card-sub">Across all pools</div></div>
    <div class="card"><div class="card-title">Pool APY</div><div class="card-value" style="color:var(--success)">{{ $metrics['poolApy'] }}%</div><div class="card-sub">For liquidity providers</div></div>
    <div class="card"><div class="card-title">Utilization Rate</div><div class="card-value" style="color:var(--accent)">{{ $metrics['utilizationRate'] }}%</div><div style="margin-top:8px"><div class="progress-wrap"><div class="progress-bar" style="width:{{ $metrics['utilizationRate'] }}%;background:linear-gradient(90deg,var(--accent),var(--primary))"></div></div></div></div>
</div>
<div class="tabs"><button class="tab-btn active">Lending Pool</button><button class="tab-btn">Yield Pool</button></div>
@foreach([['Lending Pool','Fund reputation & NFT loans. Earn interest from repayments.',$metrics['poolApy'],'lending-apy-chart'],['Yield Pool','Fund yield-backed borrows. Stable returns.',6.8,'yield-apy-chart']] as $i=>[$name,$desc,$apy,$cid])
<div class="tab-panel {{ $i===0?'active':'' }}">
    <div class="two-col">
        <div class="card mb-4">
            <div class="section-title">{{ $name }}</div>
            <p class="text-sm text-muted mb-4" style="line-height:1.65">{{ $desc }}</p>
            <div class="summary-box mb-4"><div class="summary-row"><span>Pool APY</span><strong style="color:var(--success)">{{ $apy }}%</strong></div><div class="summary-row"><span>Your Deposit</span><strong class="mono">{{ $i===0?'5,000':'0' }} USDCx</strong></div><div class="summary-row"><span>Utilization</span><strong>{{ $i===0?'67.4%':'82.1%' }}</strong></div></div>
            <div class="input-group"><label class="input-label">Deposit Amount (USDCx)</label><input type="number" class="input" placeholder="0.00" min="1"></div>
            <button class="btn btn-primary btn-full" onclick="sendTx('depositPool')"><svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12l7-7 7 7"/></svg>Deposit to {{ $name }}</button>
        </div>
        <div class="card"><div class="card-title">Historical APY (30 days)</div><div class="chart-container" style="height:175px"><canvas id="{{ $cid }}"></canvas></div></div>
    </div>
</div>
@endforeach
<div class="card mt-4">
    <div class="section-title">Your LP Positions</div>
    @if(!$connected)
    <div style="text-align:center;padding:24px"><p class="text-muted">Connect wallet to view your positions</p><button class="btn btn-primary" style="margin-top:11px" onclick="toggleWallet()">Connect Wallet</button></div>
    @else
    <div class="table-wrap"><table><thead><tr><th>Pool</th><th>Deposited</th><th>Current Value</th><th>Earned</th><th>APY</th><th>Action</th></tr></thead>
    <tbody><tr><td><strong>Lending Pool</strong></td><td class="mono">5,000 USDCx</td><td class="mono" style="color:var(--success)">5,034.20 USDCx</td><td class="mono" style="color:var(--success)">+34.20 USDCx</td><td>{{ $metrics['poolApy'] }}%</td><td><button class="btn btn-ghost btn-sm" onclick="sendTx('withdraw')">Withdraw</button></td></tr></tbody></table></div>
    @endif
</div>
@endsection
@push('scripts')
<script>
const aL=@json($chartData['labels']);const aD=@json($chartData['apys']);
document.querySelectorAll('.tab-btn').forEach((b,i)=>{b.addEventListener('click',()=>{document.querySelectorAll('.tab-btn').forEach(x=>x.classList.remove('active'));document.querySelectorAll('.tab-panel').forEach(x=>x.classList.remove('active'));b.classList.add('active');document.querySelectorAll('.tab-panel')[i].classList.add('active')})});
function mkChart(id,col){const c=document.getElementById(id);if(!c)return;new Chart(c,{type:'line',data:{labels:aL,datasets:[{data:aD,borderColor:col,backgroundColor:col+'18',borderWidth:2,fill:true,tension:0.4,pointRadius:0}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{x:{ticks:{color:'#475569',font:{size:9},maxTicksLimit:5},grid:{color:'rgba(42,42,58,.4)'}},y:{ticks:{color:'#475569',font:{size:9},callback:v=>v+'%'},grid:{color:'rgba(42,42,58,.4)'}}}}})}
window.addEventListener('load',()=>{mkChart('lending-apy-chart','#6366F1');mkChart('yield-apy-chart','#06B6D4')});
</script>
@endpush
