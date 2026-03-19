@extends('layouts.app')
@section('title','Borrow')
@section('page-title','Borrow')
@section('content')
@if(!$connected)
<div class="connect-prompt"><svg width="46" height="46" fill="none" viewBox="0 0 24 24" stroke="var(--primary)" stroke-width="1.5"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg><h2>Connect Wallet to Borrow</h2><p>Access reputation loans, NFT-backed loans, and yield borrowing.</p><button class="btn btn-primary" onclick="toggleWallet()">Connect Wallet</button></div>
@else
<div class="tabs">
    <button class="tab-btn {{ $activeTab==='reputation'?'active':'' }}" data-i="0">Reputation Loan</button>
    <button class="tab-btn {{ $activeTab==='nft'?'active':'' }}" data-i="1">NFT Loan</button>
    <button class="tab-btn {{ $activeTab==='yield'?'active':'' }}" data-i="2">Yield Borrow</button>
</div>

<!-- TAB 1: Reputation -->
<div class="tab-panel {{ $activeTab==='reputation'?'active':'' }}">
    <div class="two-col">
        <div class="card">
            <div class="section-title">Your Reputation</div>
            <div style="display:flex;align-items:center;gap:11px;margin-bottom:14px">
                <span class="badge tier-a" style="font-size:.83rem;padding:5px 13px">TIER {{ $wallet['reputationTier'] }}</span>
                <span style="font-size:1.35rem;font-weight:800;color:var(--primary)">{{ $wallet['reputationScore'] }}</span>
                <span class="text-muted text-sm">/ 1000</span>
            </div>
            <div class="summary-box mb-4"><div class="summary-row"><span>Borrow Limit</span><strong style="color:var(--primary)">{{ $wallet['borrowLimit'] }}</strong></div><div class="summary-row"><span>Interest Range</span><strong>5% – 12%</strong></div></div>
            <div class="divider"></div>
            <div class="input-group"><label class="input-label">Loan Amount (sBTC)</label><input type="number" class="input" id="loan-amt" min="0.01" max="2.5" step="0.01" value="0.5" oninput="updateSummary()"><input type="range" class="range-input" min="0.01" max="2.5" step="0.01" value="0.5" oninput="this.previousElementSibling.previousElementSibling.value=this.value;updateSummary()"><div class="text-xs text-muted" style="margin-top:3px">Max: 2.5 sBTC (Tier A)</div></div>
            <div class="input-group"><label class="input-label">Interest Rate (%)</label><input type="number" class="input" id="loan-rate" min="5" max="25" step="0.1" value="8" oninput="updateSummary()"></div>
            <div class="input-group"><label class="input-label">Duration</label>
                <div class="seg-control" id="dur-seg">
                    <button class="seg-btn" onclick="setDur(7,this)">7d</button><button class="seg-btn" onclick="setDur(14,this)">14d</button><button class="seg-btn active" onclick="setDur(30,this)">30d</button><button class="seg-btn" onclick="setDur(60,this)">60d</button><button class="seg-btn" onclick="setDur(90,this)">90d</button>
                </div>
            </div>
            <div class="summary-box mt-3 mb-4"><div class="summary-row"><span>Amount</span><strong class="mono" id="s-amt">0.5000 sBTC</strong></div><div class="summary-row"><span>Rate</span><strong id="s-rate">8.0%</strong></div><div class="summary-row"><span>Total Repay</span><strong class="mono" id="s-repay">0.5033 sBTC</strong></div><div class="summary-row"><span>Deadline</span><strong id="s-dl">Apr 18, 2026</strong></div></div>
            <button class="btn btn-primary btn-full" onclick="sendTx('createLoan')">Create Loan Request</button>
        </div>
        <div>
            <div class="card mb-4">
                <div class="section-title">How It Works</div>
                @foreach([['Build Reputation','On-chain activity builds your score'],['Request Loan','Set amount, rate & duration'],['Lenders Fund','Marketplace lenders review & fund'],['Repay & Grow','Repay on time to boost tier']] as $i=>$s)
                <div style="display:flex;gap:10px;padding:9px 0;{{ $i<3?'border-bottom:1px solid var(--border)':'' }}">
                    <div style="width:26px;height:26px;border-radius:50%;background:rgba(99,102,241,.2);border:1px solid var(--primary);color:var(--primary);font-size:.75rem;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0">{{ $i+1 }}</div>
                    <div><div style="font-size:.82rem;font-weight:600;color:var(--text)">{{ $s[0] }}</div><div class="text-xs text-muted" style="margin-top:2px">{{ $s[1] }}</div></div>
                </div>
                @endforeach
            </div>
            <div class="card"><div class="section-title">Tier A Benefits</div><div class="table-wrap"><table><thead><tr><th>Benefit</th><th>Value</th></tr></thead><tbody><tr><td>Max Borrow</td><td><strong>2.5 sBTC</strong></td></tr><tr><td>Interest Range</td><td><strong>5% – 12%</strong></td></tr><tr><td>Max Duration</td><td><strong>90 days</strong></td></tr><tr><td>Priority Funding</td><td><span class="badge badge-success">Yes</span></td></tr></tbody></table></div></div>
        </div>
    </div>
</div>

<!-- TAB 2: NFT -->
<div class="tab-panel {{ $activeTab==='nft'?'active':'' }}">
    <div class="two-col">
        <div class="card">
            <div class="section-title">Select NFT from Wallet</div>
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:11px;margin-bottom:18px">
                @foreach($nfts as $nft)
                <div class="nft-card" onclick="selectNFT({{ $nft['id'] }},this)" id="nc-{{ $nft['id'] }}" data-nft="{{ json_encode($nft) }}">
                    <svg class="nft-thumb" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg"><defs><linearGradient id="ng{{ $nft['id'] }}" x1="0" y1="0" x2="1" y2="1"><stop offset="0%" stop-color="{{ $nft['color1'] }}"/><stop offset="100%" stop-color="{{ $nft['color2'] }}"/></linearGradient></defs><rect width="100" height="100" fill="url(#ng{{ $nft['id'] }})"/><circle cx="{{ 28+$nft['id']*15 }}" cy="{{ 32+$nft['id']*7 }}" r="20" fill="rgba(255,255,255,.14)"/><rect x="14" y="54" width="72" height="7" rx="4" fill="rgba(255,255,255,.18)"/><rect x="24" y="68" width="52" height="5" rx="3" fill="rgba(255,255,255,.13)"/><text x="50" y="32" text-anchor="middle" font-size="18" fill="rgba(255,255,255,.75)">{{ $loop->iteration===1?'🎭':($loop->iteration===2?'🐸':'⚡') }}</text><text x="50" y="94" text-anchor="middle" font-size="7" fill="rgba(255,255,255,.85)" font-family="monospace">#{{ explode('#',$nft['name'])[1] }}</text></svg>
                    <div class="nft-info"><div class="nft-name">{{ explode(' ',$nft['name'])[0] }} {{ explode(' ',$nft['name'])[1] }}</div><div class="nft-floor">Floor: {{ $nft['floor'] }} sBTC</div></div>
                </div>
                @endforeach
            </div>
            <div id="nft-detail" class="hidden">
                <div class="divider"></div>
                <div class="summary-box mb-4"><div class="summary-row"><span>Collection</span><strong id="nd-col">—</strong></div><div class="summary-row"><span>Floor Price</span><strong id="nd-floor" class="mono">—</strong></div><div class="summary-row"><span>30d Volume</span><strong id="nd-vol" class="mono">—</strong></div><div class="summary-row"><span>Liquidity</span><strong id="nd-liq">—</strong></div><div class="summary-row"><span>Max Borrow (40%)</span><strong id="nd-max" class="mono" style="color:var(--primary);font-size:.95rem">—</strong></div></div>
                <div class="input-group"><label class="input-label">Duration</label><div class="seg-control" id="nft-dur"><button class="seg-btn active" onclick="setSegActive(this,'nft-dur')">7d</button><button class="seg-btn" onclick="setSegActive(this,'nft-dur')">14d</button><button class="seg-btn" onclick="setSegActive(this,'nft-dur')">30d</button></div></div>
                <button class="btn btn-primary btn-full" onclick="sendTx('lockNFT')">Lock NFT & Borrow</button>
            </div>
        </div>
        <div>
            <div class="card mb-4"><div class="section-title">How NFT Lending Works</div>@foreach([['Lock','NFT locked in escrow smart contract'],['Borrow','Receive up to 40% of floor value'],['Hold','Keep borrowed funds until repayment'],['Repay','Repay principal + interest to unlock'],['Default','NFT liquidated if loan expires']] as $i=>$s)<div style="display:flex;gap:10px;padding:8px 0;{{ $i<4?'border-bottom:1px solid var(--border)':'' }}"><div style="width:25px;height:25px;border-radius:50%;background:rgba(99,102,241,.2);border:1px solid var(--primary);color:var(--primary);font-size:.72rem;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0">{{ $i+1 }}</div><div><div style="font-size:.81rem;font-weight:600;color:var(--text)">{{ $s[0] }}</div><div class="text-xs text-muted">{{ $s[1] }}</div></div></div>@endforeach</div>
            <div class="card"><div class="section-title">LTV Info</div><p class="text-sm text-muted" style="line-height:1.7">Max LTV is <strong style="color:var(--text)">40%</strong> of floor at lock time. If loan expires unpaid, the NFT transfers to liquidation and the lender is made whole. Floor price is verified via the Stacks NFT oracle.</p></div>
        </div>
    </div>
</div>

<!-- TAB 3: Yield -->
<div class="tab-panel {{ $activeTab==='yield'?'active':'' }}">
    <div class="two-col">
        <div class="card">
            <div class="section-title">Borrow Against Vault Yield</div>
            <div class="summary-box mb-4"><div class="summary-row"><span>Vault Balance</span><strong class="mono">10,000 USDCx</strong></div><div class="summary-row"><span>APY</span><strong style="color:var(--success)">12.4%</strong></div><div class="summary-row"><span>Accrued Yield</span><strong class="mono" style="color:var(--success)">+34.52 USDCx</strong></div></div>
            <div class="input-group"><label class="input-label">Duration</label><div class="seg-control" id="y-dur"><button class="seg-btn" onclick="setSegActive(this,'y-dur');calcY(30)">30d</button><button class="seg-btn" onclick="setSegActive(this,'y-dur');calcY(60)">60d</button><button class="seg-btn active" onclick="setSegActive(this,'y-dur');calcY(90)">90d</button><button class="seg-btn" onclick="setSegActive(this,'y-dur');calcY(180)">180d</button></div></div>
            <div class="summary-box mb-4" id="y-calc"><div class="summary-row"><span>Projected Yield</span><strong class="mono" id="yy">305 USDCx</strong></div><div class="summary-row"><span>Borrow Limit (50%)</span><strong class="mono" id="yl">152.50 USDCx</strong></div><div class="summary-row"><span>Available</span><strong class="mono" id="ya" style="color:var(--primary)">52.50 USDCx</strong></div></div>
            <div class="input-group"><label class="input-label">Borrow Amount (USDCx)</label><input type="number" class="input" id="y-amt" value="50" min="1" max="52.5" oninput="calcHF()"></div>
            <div class="card" style="background:var(--elevated);text-align:center;margin-bottom:14px">
                <div class="card-title">Health Factor</div>
                <div class="gauge-wrap">
                    <svg viewBox="0 0 200 108" fill="none"><path d="M 20,100 A 80,80 0 0,1 180,100" stroke="#2A2A3A" stroke-width="11" fill="none" stroke-linecap="round"/><path d="M 20,100 A 80,80 0 0,1 100,20 A 80,80 0 0,1 180,100" stroke="#EF4444" stroke-width="4" fill="none" stroke-linecap="round" opacity=".4"/><path d="M 20,100 A 80,80 0 0,1 180,100" stroke="url(#hg)" stroke-width="11" fill="none" stroke-linecap="round" stroke-dasharray="251" stroke-dashoffset="63"/><defs><linearGradient id="hg"><stop offset="0%" stop-color="#EF4444"/><stop offset="45%" stop-color="#F59E0B"/><stop offset="100%" stop-color="#10B981"/></linearGradient></defs><line id="hf-needle" x1="100" y1="100" x2="100" y2="28" stroke="white" stroke-width="3" stroke-linecap="round" style="transform-origin:100px 100px;transform:rotate(42deg);transition:transform .6s"/><circle cx="100" cy="100" r="6" fill="#1A1A24" stroke="white" stroke-width="2"/><text x="100" y="88" text-anchor="middle" font-size="14" font-weight="800" fill="#10B981" font-family="Inter" id="hf-val">2.8x</text><text x="22" y="108" font-size="8" fill="#EF4444" font-family="Inter">0</text><text x="92" y="19" font-size="8" fill="#F59E0B" font-family="Inter">1.5</text><text x="172" y="108" font-size="8" fill="#10B981" font-family="Inter">3+</text></svg>
                    <div class="gauge-val" id="hf-big" style="color:var(--success)">2.8x</div>
                    <div class="gauge-label">Health Factor — <span id="hf-status" style="color:var(--success)">Safe</span></div>
                </div>
            </div>
            <button class="btn btn-primary btn-full" onclick="sendTx('borrowYield')">Borrow from Pool</button>
        </div>
        <div class="card">
            <div class="section-title">Active Borrow</div>
            <div class="summary-box mb-4"><div class="summary-row"><span>Borrowed</span><strong class="mono">100 USDCx</strong></div><div class="summary-row"><span>Repay By</span><strong>Jun 19, 2026</strong></div><div class="summary-row"><span>Health Factor</span><strong style="color:var(--success)">2.8x</strong></div></div>
            <button class="btn btn-success btn-full" onclick="sendTx('repayYield')">Repay 100 USDCx</button>
        </div>
    </div>
</div>
@endif
@endsection
@push('scripts')
<script>
// Tab switching
document.querySelectorAll('.tab-btn').forEach((b,i)=>{b.addEventListener('click',()=>{document.querySelectorAll('.tab-btn').forEach(x=>x.classList.remove('active'));document.querySelectorAll('.tab-panel').forEach(x=>x.classList.remove('active'));b.classList.add('active');document.querySelectorAll('.tab-panel')[i].classList.add('active')})});
let dur=30;
function setDur(d,el){dur=d;document.querySelectorAll('#dur-seg .seg-btn').forEach(b=>b.classList.remove('active'));el.classList.add('active');updateSummary()}
function updateSummary(){
    const a=parseFloat(document.getElementById('loan-amt').value)||0;
    const r=parseFloat(document.getElementById('loan-rate').value)||0;
    const rep=a+(a*r/100*dur/365);
    const dl=new Date(Date.now()+dur*86400000);
    document.getElementById('s-amt').textContent=a.toFixed(4)+' sBTC';
    document.getElementById('s-rate').textContent=r.toFixed(1)+'%';
    document.getElementById('s-repay').textContent=rep.toFixed(4)+' sBTC';
    document.getElementById('s-dl').textContent=dl.toLocaleDateString('en-US',{month:'short',day:'numeric',year:'numeric'});
}
function selectNFT(id,el){document.querySelectorAll('.nft-card').forEach(c=>c.classList.remove('selected'));el.classList.add('selected');const nft=JSON.parse(el.dataset.nft);const mb=(nft.floor*0.4).toFixed(4);document.getElementById('nd-col').textContent=nft.collection;document.getElementById('nd-floor').textContent=nft.floor+' sBTC';document.getElementById('nd-vol').textContent=nft.volume30d+' sBTC';document.getElementById('nd-liq').textContent=nft.liquidity;document.getElementById('nd-max').textContent=mb+' sBTC';document.getElementById('nft-detail').classList.remove('hidden')}
function calcY(d){const dep=10000,apy=0.124;const py=(dep*apy*d/365).toFixed(2);const bl=(py*0.5).toFixed(2);const av=Math.max(0,bl-100).toFixed(2);document.getElementById('yy').textContent=py+' USDCx';document.getElementById('yl').textContent=bl+' USDCx';document.getElementById('ya').textContent=av+' USDCx';document.getElementById('y-amt').max=av;calcHF()}
function calcHF(){const b=parseFloat(document.getElementById('y-amt').value)||0;const bl=152.5;const debt=100+b;const hf=debt>0?bl/debt:9.99;const hs=hf.toFixed(2)+'x';document.getElementById('hf-val').textContent=hs;document.getElementById('hf-big').textContent=hs;const needle=document.getElementById('hf-needle');const status=document.getElementById('hf-status');const big=document.getElementById('hf-big');if(hf>=1.5){big.style.color='var(--success)';status.textContent='Safe';status.style.color='var(--success)';needle.style.transform=`rotate(${Math.min(130,45+((hf-1.5)/1.5)*80)}deg)`}else if(hf>=1){big.style.color='var(--warning)';status.textContent='Caution';status.style.color='var(--warning)';needle.style.transform=`rotate(${-25+((hf-1)/0.5)*70}deg)`}else{big.style.color='var(--danger)';status.textContent='At Risk';status.style.color='var(--danger)';needle.style.transform='rotate(-80deg)'}}
updateSummary();calcY(90);
</script>
@endpush
