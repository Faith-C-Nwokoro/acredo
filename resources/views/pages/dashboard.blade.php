@extends('layouts.app')
@section('title','Dashboard')
@section('page-title','Dashboard')
@section('content')
@if(!$connected)
<div class="connect-prompt">
    <svg width="52" height="52" viewBox="0 0 48 48" fill="none"><defs><linearGradient id="lg-a" x1="4" y1="3" x2="26" y2="46" gradientUnits="userSpaceOnUse"><stop offset="0%" stop-color="#9AAEFF"/><stop offset="100%" stop-color="#2535C0"/></linearGradient><linearGradient id="lg-c" x1="16" y1="4" x2="48" y2="45" gradientUnits="userSpaceOnUse"><stop offset="0%" stop-color="#72EEFF"/><stop offset="100%" stop-color="#0A6878"/></linearGradient></defs><polygon points="12,42 6,42 16,4 21,4" fill="url(#lg-a)"/><polygon points="29.5,42 23.5,42 21,4 26,4" fill="url(#lg-a)"/><rect x="10.5" y="23" width="21" height="5.2" fill="url(#lg-a)"/><path d="M 40,9.5 A 15,15 0 1,1 40,36.5" stroke="url(#lg-c)" stroke-width="7.5" fill="none" stroke-linecap="round"/></svg>
    <h2>Welcome to Acredo Protocol</h2>
    <p>Connect your Hiro Wallet to view positions, reputation score, and active loans.</p>
    <button class="btn btn-primary" onclick="toggleWallet()"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/></svg>Connect Hiro Wallet</button>
</div>
@else
<div class="stat-grid mb-4">
    <div class="card score-glow"><div class="card-title">Reputation Score</div><div class="card-value" style="color:var(--primary)">{{ $wallet['reputationScore'] }}</div><div class="card-sub">Tier {{ $wallet['reputationTier'] }} — Top 15%</div><div style="margin-top:9px"><div class="progress-wrap"><div class="progress-bar" style="width:{{ $wallet['reputationScore']/10 }}%"></div></div></div></div>
    <div class="card"><div class="card-title">Borrow Limit</div><div class="card-value">{{ $wallet['borrowLimit'] }}</div><div class="card-sub">Based on Tier {{ $wallet['reputationTier'] }}</div></div>
    <div class="card"><div class="card-title">Active Loans</div><div class="card-value">{{ count($activeLoans) }}</div><div class="card-sub">1 borrower · 1 lender</div></div>
    <div class="card"><div class="card-title">Total Deposited</div><div class="card-value">$10,240</div><div class="card-sub">Vault + Pool</div></div>
</div>
<div class="col-6040 mb-4">
    <div class="card">
        <div class="section-title">Reputation</div>
        <div class="rep-ring-wrap">
            <svg viewBox="0 0 150 150" fill="none"><defs><linearGradient id="rg" x1="0" y1="0" x2="1" y2="1"><stop offset="0%" stop-color="#6366F1"/><stop offset="100%" stop-color="#06B6D4"/></linearGradient></defs>
            <circle cx="75" cy="75" r="60" stroke="#2A2A3A" stroke-width="9" fill="none"/>
            <circle cx="75" cy="75" r="60" stroke="url(#rg)" stroke-width="9" fill="none" stroke-linecap="round" stroke-dasharray="377" stroke-dashoffset="377" transform="rotate(-90 75 75)" id="rep-arc"/>
            <text x="75" y="70" text-anchor="middle" font-size="22" font-weight="700" fill="#F8FAFC" font-family="Inter">{{ $wallet['reputationScore'] }}</text>
            <text x="75" y="86" text-anchor="middle" font-size="11" fill="#94A3BB" font-family="Inter">/ 1000</text></svg>
        </div>
        <div style="text-align:center;margin-bottom:13px"><span class="badge tier-a" style="font-size:.77rem;padding:4px 12px">TIER {{ $wallet['reputationTier'] }}</span></div>
        <div class="summary-box">
            <div class="summary-row"><span>Wallet Age</span><strong>{{ $wallet['walletAge'] }} days</strong></div>
            <div class="summary-row"><span>BNS Name</span><strong>{{ $wallet['bnsName'] }} ✓</strong></div>
            <div class="summary-row"><span>Loans Repaid</span><strong>{{ $wallet['loansRepaid'] }} · Defaults: {{ $wallet['defaults'] }}</strong></div>
            <div class="summary-row"><span>Borrow Limit</span><strong style="color:var(--primary)">{{ $wallet['borrowLimit'] }}</strong></div>
        </div>
        <div style="margin-top:11px">
            <button class="collapsible-btn" onclick="toggleCollapsible('rep-exp')"><svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>How is this calculated?</button>
            <div class="collapsible-content" id="rep-exp"><p class="text-sm text-muted" style="line-height:1.65;margin-top:6px">Score (0–1000) from wallet age, BNS ownership, transaction volume, DeFi interactions, and repayment history. Higher scores unlock better limits and lower rates.</p></div>
        </div>
    </div>
    <div class="card">
        <div class="section-title">Quick Actions</div>
        <div style="display:flex;flex-direction:column;gap:9px">
            <a href="{{ route('borrow',['tab'=>'reputation']) }}" class="card" style="padding:12px;text-decoration:none;border-color:rgba(99,102,241,.18);transition:border-color .2s" onmouseover="this.style.borderColor='var(--primary)'" onmouseout="this.style.borderColor='rgba(99,102,241,.18)'">
                <div style="display:flex;align-items:center;gap:10px"><div style="width:35px;height:35px;background:rgba(99,102,241,.15);border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0"><svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#6366F1" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div><div style="min-width:0"><div style="font-size:.82rem;font-weight:600;color:var(--text)">Borrow Against Reputation</div><div style="font-size:.7rem;color:var(--text-2);margin-top:1px">Instant loans from on-chain history</div></div><svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="var(--text-3)" stroke-width="2" style="margin-left:auto;flex-shrink:0"><path d="M9 18l6-6-6-6"/></svg></div>
            </a>
            <a href="{{ route('vault') }}" class="card" style="padding:12px;text-decoration:none;border-color:rgba(16,185,129,.18);transition:border-color .2s" onmouseover="this.style.borderColor='var(--success)'" onmouseout="this.style.borderColor='rgba(16,185,129,.18)'">
                <div style="display:flex;align-items:center;gap:10px"><div style="width:35px;height:35px;background:rgba(16,185,129,.12);border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0"><svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#10B981" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg></div><div style="min-width:0"><div style="font-size:.82rem;font-weight:600;color:var(--text)">Deposit to Yield Vault</div><div style="font-size:.7rem;color:var(--text-2);margin-top:1px">Earn 12.4% APY on USDCx</div></div><svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="var(--text-3)" stroke-width="2" style="margin-left:auto;flex-shrink:0"><path d="M9 18l6-6-6-6"/></svg></div>
            </a>
            <a href="{{ route('pool') }}" class="card" style="padding:12px;text-decoration:none;border-color:rgba(6,182,212,.18);transition:border-color .2s" onmouseover="this.style.borderColor='var(--accent)'" onmouseout="this.style.borderColor='rgba(6,182,212,.18)'">
                <div style="display:flex;align-items:center;gap:10px"><div style="width:35px;height:35px;background:rgba(6,182,212,.1);border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0"><svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#06B6D4" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div><div style="min-width:0"><div style="font-size:.82rem;font-weight:600;color:var(--text)">Provide Liquidity</div><div style="font-size:.7rem;color:var(--text-2);margin-top:1px">Earn 8.2% APY as LP</div></div><svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="var(--text-3)" stroke-width="2" style="margin-left:auto;flex-shrink:0"><path d="M9 18l6-6-6-6"/></svg></div>
            </a>
        </div>
    </div>
</div>
<div class="card">
    <div class="flex items-center justify-between mb-4"><div class="section-title" style="margin-bottom:0">Active Loans</div><a href="{{ route('borrow') }}" class="btn btn-ghost btn-sm">+ New</a></div>
    <div class="table-wrap"><table><thead><tr><th>Type</th><th>Amount</th><th>Rate</th><th>Duration</th><th>Status</th><th>Action</th></tr></thead>
    <tbody>@foreach($activeLoans as $l)<tr><td><strong>{{ $l['type'] }}</strong></td><td class="mono">{{ $l['amount'] }}</td><td>{{ $l['rate'] }}%</td><td>{{ $l['daysLeft'] }}d left</td><td><span class="badge badge-{{ $l['statusClass'] }}">{{ $l['status'] }}</span></td><td><button class="btn btn-ghost btn-sm" onclick="sendTx('repay')">Repay</button></td></tr>@endforeach</tbody></table></div>
</div>
@endif
@endsection
@push('scripts')
<script>
window.addEventListener('load',()=>{const arc=document.getElementById('rep-arc');if(!arc)return;const s={{ $wallet['reputationScore']??0 }};arc.style.transition='stroke-dashoffset 1.3s cubic-bezier(.4,0,.2,1)';setTimeout(()=>{arc.style.strokeDashoffset=377-(s/1000)*377},120)});
</script>
@endpush
