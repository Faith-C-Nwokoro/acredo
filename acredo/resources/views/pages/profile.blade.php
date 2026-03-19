@extends('layouts.app')
@section('title','Profile')
@section('page-title','Profile')
@section('content')
@if(!$connected)
<div class="connect-prompt"><svg width="46" height="46" fill="none" viewBox="0 0 24 24" stroke="var(--primary)" stroke-width="1.5"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg><h2>Connect Wallet</h2><p>View your on-chain reputation, loan history, and vault activity.</p><button class="btn btn-primary" onclick="toggleWallet()">Connect Wallet</button></div>
@else
<div class="card mb-4" style="background:linear-gradient(135deg,rgba(99,102,241,.06),rgba(6,182,212,.03))">
    <div style="display:flex;gap:20px;align-items:flex-start;flex-wrap:wrap">
        <div style="text-align:center;min-width:110px">
            <svg viewBox="0 0 120 120" width="110" height="110" fill="none">
                <defs><linearGradient id="prg" x1="0" y1="0" x2="1" y2="1"><stop offset="0%" stop-color="#6366F1"/><stop offset="100%" stop-color="#06B6D4"/></linearGradient></defs>
                <circle cx="60" cy="60" r="50" stroke="#2A2A3A" stroke-width="8" fill="none"/>
                <circle cx="60" cy="60" r="50" stroke="url(#prg)" stroke-width="8" fill="none" stroke-linecap="round" stroke-dasharray="314" stroke-dashoffset="314" transform="rotate(-90 60 60)" id="pr-arc"/>
                <text x="60" y="55" text-anchor="middle" font-size="18" font-weight="700" fill="#F8FAFC" font-family="Inter">{{ $wallet['reputationScore'] }}</text>
                <text x="60" y="70" text-anchor="middle" font-size="9" fill="#94A3BB" font-family="Inter">/ 1000</text>
            </svg>
        </div>
        <div style="flex:1;min-width:200px">
            <div style="font-size:1.5rem;font-weight:800;margin-bottom:4px">{{ $wallet['bnsName'] }}</div>
            <div style="display:flex;align-items:center;gap:7px;margin-bottom:10px;flex-wrap:wrap">
                <span class="mono text-sm text-muted" style="word-break:break-all">{{ $wallet['address'] }}</span>
                <button onclick="copyText('{{ $wallet['address'] }}')" style="background:none;border:none;cursor:pointer;color:var(--text-3);padding:2px;flex-shrink:0"><svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg></button>
            </div>
            <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:14px">
                <span class="badge tier-a">TIER {{ $wallet['reputationTier'] }}</span>
                <span class="badge badge-accent">Top 15%</span>
                <span class="badge badge-muted">{{ $wallet['walletAge'] }} days old</span>
            </div>
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:11px">
                <div><div class="card-title" style="font-size:.62rem">Loans Repaid</div><div style="font-size:1.15rem;font-weight:700;color:var(--success)">{{ $wallet['loansRepaid'] }}</div></div>
                <div><div class="card-title" style="font-size:.62rem">Defaults</div><div style="font-size:1.15rem;font-weight:700;color:{{ $wallet['defaults']>0?'var(--danger)':'var(--success)' }}">{{ $wallet['defaults'] }}</div></div>
                <div><div class="card-title" style="font-size:.62rem">Protocols</div><div style="font-size:1.15rem;font-weight:700">{{ $wallet['defiProtocols'] }}</div></div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="section-title">Reputation Breakdown</div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Signal</th><th>Value</th><th>Points</th><th>Share</th></tr></thead>
            <tbody>
            @php $total=array_sum(array_column($breakdown,'points')); @endphp
            @foreach($breakdown as $r)
            <tr>
                <td><strong>{{ $r['signal'] }}</strong></td>
                <td>{{ $r['value'] }}</td>
                <td><div style="display:flex;align-items:center;gap:8px"><span style="color:var(--success);font-weight:600">+{{ $r['points'] }}</span><div class="progress-wrap" style="width:60px;flex-shrink:0"><div class="progress-bar" style="width:{{ round($r['points']/$total*100) }}%"></div></div></div></td>
                <td class="text-muted">{{ round($r['points']/$total*100) }}%</td>
            </tr>
            @endforeach
            <tr style="border-top:2px solid var(--border)"><td colspan="2"><strong>Total</strong></td><td><strong style="color:var(--primary)">{{ $total }} / 1000</strong></td><td></td></tr>
            </tbody>
        </table>
    </div>
</div>

<div class="card mb-4">
    <div class="section-title">Loan History</div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Type</th><th>Amount</th><th>Rate</th><th>Duration</th><th>Status</th><th>Date</th></tr></thead>
            <tbody>
            @foreach($loanHistory as $l)
            <tr>
                <td><strong>{{ $l['type'] }}</strong></td>
                <td class="mono">{{ $l['amount'] }}</td>
                <td>{{ $l['rate'] }}%</td>
                <td>{{ $l['duration'] }}</td>
                <td>
                    @if($l['status']==='ACTIVE')<span class="badge badge-success">ACTIVE</span>
                    @elseif($l['status']==='REPAID')<span class="badge badge-muted">REPAID</span>
                    @elseif($l['status']==='DEFAULTED')<span class="badge badge-danger">DEFAULTED</span>
                    @else<span class="badge badge-warning">{{ $l['status'] }}</span>@endif
                </td>
                <td class="text-muted">{{ $l['date'] }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <div class="section-title">Vault & Pool Activity</div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Action</th><th>Amount</th><th>Date</th></tr></thead>
            <tbody>
            @foreach($vaultHistory as $h)
            <tr>
                <td><div style="display:flex;align-items:center;gap:6px">
                    @if($h['action']==='Deposit')<svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="var(--success)" stroke-width="2"><path d="M12 5v14M5 12l7-7 7 7"/></svg>@else<svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="var(--warning)" stroke-width="2"><path d="M12 19V5M19 12l-7 7-7-7"/></svg>@endif
                    <strong>{{ $h['action'] }}</strong>
                </div></td>
                <td class="mono">{{ $h['amount'] }}</td>
                <td class="text-muted">{{ $h['date'] }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
@push('scripts')
<script>
window.addEventListener('load',()=>{const a=document.getElementById('pr-arc');if(!a)return;const s={{ $wallet['reputationScore']??0 }};a.style.transition='stroke-dashoffset 1.3s cubic-bezier(.4,0,.2,1)';setTimeout(()=>{a.style.strokeDashoffset=314-(s/1000)*314},120)});
</script>
@endpush
