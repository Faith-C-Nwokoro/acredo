@extends('layouts.app')
@section('title','Marketplace')
@section('page-title','Marketplace')
@section('content')
<style>
.filter-bar{display:flex;flex-direction:column;gap:10px}
.filter-row{display:flex;align-items:center;gap:8px;flex-wrap:wrap}
.fl{font-size:.72rem;color:var(--text-2);white-space:nowrap;min-width:68px}
.tier-btns{display:flex;gap:5px;flex-wrap:wrap}
.tbtn{padding:5px 12px;border-radius:8px;font-size:.75rem;font-weight:700;cursor:pointer;background:transparent;border:1px solid var(--border);color:var(--text-2);font-family:inherit;transition:all .18s}
.tbtn.active,.tbtn:hover{background:var(--primary);border-color:var(--primary);color:#fff}
.tbtn[data-t="A"]{color:#34d399}.tbtn[data-t="A"].active{background:#10B981;border-color:#10B981;color:#fff}
.tbtn[data-t="B"]{color:#fbbf24}.tbtn[data-t="B"].active{background:#F59E0B;border-color:#F59E0B;color:#fff}
.tbtn[data-t="C"]{color:#fb923c}.tbtn[data-t="C"].active{background:#F97316;border-color:#F97316;color:#fff}
.lrc-top{display:flex;align-items:flex-start;justify-content:space-between;gap:10px;flex-wrap:wrap;margin-bottom:10px}
.lrc-tl{display:flex;align-items:center;gap:9px;min-width:0;flex:1}
.bname{font-weight:600;color:var(--text);font-size:.87rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:180px}
.baddr{font-family:'JetBrains Mono',monospace;font-size:.67rem;color:var(--text-2)}
.lrc-tr{display:flex;align-items:center;gap:8px;flex-shrink:0}
@media(max-width:460px){.baddr{display:none}.lrc-tr{flex-direction:column;align-items:flex-end;gap:5px}}
.lrc-body{display:flex;flex-wrap:wrap;gap:14px;align-items:flex-end}
.lrd .lbl{font-size:.67rem;color:var(--text-3);margin-bottom:2px}
.lrd .val{font-size:.81rem;font-weight:600;color:var(--text)}
.lrc-expand{display:none;padding-top:11px;border-top:1px solid var(--border);margin-top:10px}
.lrc-expand.open{display:block}
.exp-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:10px}
@media(max-width:540px){.exp-grid{grid-template-columns:repeat(2,1fr)}}
</style>

<div id="mp-tabs">
    <div class="tabs">
        <button class="tab-btn active">Open Requests</button>
        <button class="tab-btn">My Funded Loans</button>
    </div>

    <div class="tab-panel active">
        <div class="card mb-4" style="padding:14px 16px">
            <div class="filter-bar">
                <div class="filter-row">
                    <span class="fl">Filter by Tier</span>
                    <div class="tier-btns" id="tier-filter">
                        <button class="tbtn active" data-t="all" onclick="filterT('all',this)">All</button>
                        <button class="tbtn" data-t="A" onclick="filterT('A',this)">A</button>
                        <button class="tbtn" data-t="B" onclick="filterT('B',this)">B</button>
                        <button class="tbtn" data-t="C" onclick="filterT('C',this)">C</button>
                    </div>
                </div>
                <div class="filter-row">
                    <span class="fl">Sort</span>
                    <select class="input" style="width:auto;padding:6px 10px;font-size:.77rem;flex-shrink:0" onchange="sortCards(this.value)">
                        <option value="newest">Newest</option>
                        <option value="rate">Interest Rate</option>
                        <option value="amount">Amount</option>
                    </select>
                    <input type="text" class="input" style="flex:1;min-width:100px;padding:7px 11px;font-size:.8rem" placeholder="Search BNS name…" oninput="searchCards(this.value)">
                </div>
            </div>
        </div>

        <div id="lr-list" style="display:flex;flex-direction:column;gap:10px">
            @foreach($loanRequests as $req)
            <div class="loan-req-card" data-tier="{{ $req['tier'] }}" data-bns="{{ strtolower($req['borrower']) }}" data-rate="{{ $req['rate'] }}" data-amount="{{ $req['amount'] }}" data-id="{{ $req['id'] }}">
                <div class="lrc-top">
                    <div class="lrc-tl">
                        <span class="badge tier-{{ strtolower($req['tier']) }}" style="font-size:.7rem;flex-shrink:0">TIER {{ $req['tier'] }}</span>
                        <div style="min-width:0">
                            <div class="bname">{{ $req['borrower'] }}</div>
                            <div class="baddr">{{ $req['address'] }}</div>
                        </div>
                    </div>
                    <div class="lrc-tr">
                        <span style="font-size:.71rem;color:var(--text-2)">{{ $req['postedAt'] }}</span>
                        <button class="btn btn-primary btn-sm" onclick="openFundModal({{ json_encode($req) }})">Fund Loan</button>
                    </div>
                </div>
                <div class="lrc-body">
                    <div class="lrd"><div class="lbl">Amount</div><div class="val mono">{{ number_format($req['amount'],4) }} sBTC</div></div>
                    <div class="lrd"><div class="lbl">Interest</div><div class="val" style="color:var(--success)">{{ $req['rate'] }}%</div></div>
                    <div class="lrd"><div class="lbl">Duration</div><div class="val">{{ $req['duration'] }} days</div></div>
                    <div class="lrd"><div class="lbl">Return</div><div class="val mono">{{ number_format($req['amount'] + $req['amount']*$req['rate']/100*$req['duration']/365, 4) }} sBTC</div></div>
                    <div style="margin-left:auto">
                        <button class="btn btn-ghost btn-sm" onclick="toggleExpand('exp-{{ $req['id'] }}',this)">Details ↓</button>
                    </div>
                </div>
                <div class="lrc-expand" id="exp-{{ $req['id'] }}">
                    <div class="exp-grid">
                        <div class="lrd"><div class="lbl">Rep Score</div><div class="val" style="color:var(--primary)">{{ $req['score'] }}</div></div>
                        <div class="lrd"><div class="lbl">Wallet Age</div><div class="val">{{ $req['walletAge'] }}d</div></div>
                        <div class="lrd"><div class="lbl">Repaid</div><div class="val" style="color:var(--success)">{{ $req['loansRepaid'] }}</div></div>
                        <div class="lrd"><div class="lbl">Defaults</div><div class="val" style="{{ $req['defaults']>0?'color:var(--danger)':'color:var(--success)' }}">{{ $req['defaults'] }}</div></div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="tab-panel">
        @if(!$connected)
        <div class="connect-prompt" style="padding:36px 16px">
            <h2>Connect Wallet</h2><p>Connect to see loans you've funded.</p>
            <button class="btn btn-primary" onclick="toggleWallet()">Connect Wallet</button>
        </div>
        @else
        <div class="card">
            <div class="section-title">My Funded Loans</div>
            <div class="table-wrap"><table>
                <thead><tr><th>Borrower</th><th>Amount</th><th>Rate</th><th>Days Left</th><th>Status</th><th>Return</th></tr></thead>
                <tbody>@foreach($fundedLoans as $l)
                <tr>
                    <td><strong>{{ $l['borrower'] }}</strong></td>
                    <td class="mono">{{ $l['amount'] }}</td>
                    <td>{{ $l['rate'] }}%</td>
                    <td>{{ $l['daysLeft'] }}d</td>
                    <td><span class="badge badge-{{ $l['status']==='ACTIVE'?'success':'warning' }}">{{ $l['status'] }}</span></td>
                    <td class="mono" style="color:var(--success)">+{{ number_format(floatval(preg_replace('/[^0-9.]/','',explode(' ',$l['amount'])[0]))*$l['rate']/100*$l['daysLeft']/365,4) }} sBTC</td>
                </tr>
                @endforeach</tbody>
            </table></div>
        </div>
        @endif
    </div>
</div>

<!-- Fund Modal -->
<div id="fund-modal" class="modal-overlay hidden">
    <div class="modal">
        <div class="modal-title">Fund Loan Request</div>
        <div id="fund-body"></div>
        <div class="modal-actions">
            <button class="btn btn-ghost" style="flex:1" onclick="closeFund()">Cancel</button>
            <button class="btn btn-primary" style="flex:2" onclick="confirmFund()">Confirm & Fund</button>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
document.querySelectorAll('.tab-btn').forEach((b,i)=>{b.addEventListener('click',()=>{document.querySelectorAll('.tab-btn').forEach(x=>x.classList.remove('active'));document.querySelectorAll('.tab-panel').forEach(x=>x.classList.remove('active'));b.classList.add('active');document.querySelectorAll('.tab-panel')[i].classList.add('active')})});
function filterT(t,el){document.querySelectorAll('.tbtn').forEach(b=>b.classList.remove('active'));el.classList.add('active');document.querySelectorAll('.loan-req-card').forEach(c=>{c.style.display=(t==='all'||c.dataset.tier===t)?'':'none'})}
function searchCards(q){document.querySelectorAll('.loan-req-card').forEach(c=>{c.style.display=c.dataset.bns.includes(q.toLowerCase())?'':'none'})}
function sortCards(by){const l=document.getElementById('lr-list');[...l.querySelectorAll('.loan-req-card')].sort((a,b)=>by==='rate'?parseFloat(b.dataset.rate)-parseFloat(a.dataset.rate):by==='amount'?parseFloat(b.dataset.amount)-parseFloat(a.dataset.amount):parseInt(b.dataset.id)-parseInt(a.dataset.id)).forEach(c=>l.appendChild(c))}
function toggleExpand(id,btn){const el=document.getElementById(id);el.classList.toggle('open');btn.textContent=el.classList.contains('open')?'Details ↑':'Details ↓'}
let _fr=null;
function openFundModal(req){_fr=req;document.getElementById('fund-body').innerHTML=`<div class="summary-box mb-4"><div class="summary-row"><span>Borrower</span><strong>${req.borrower}</strong></div><div class="summary-row"><span>Amount</span><strong class="mono">${parseFloat(req.amount).toFixed(4)} sBTC</strong></div><div class="summary-row"><span>Interest</span><strong>${req.rate}%</strong></div><div class="summary-row"><span>Duration</span><strong>${req.duration} days</strong></div><div class="summary-row"><span>Your Return</span><strong class="mono" style="color:var(--success)">+${(req.amount*req.rate/100*req.duration/365).toFixed(4)} sBTC</strong></div></div>`;document.getElementById('fund-modal').classList.remove('hidden')}
function closeFund(){document.getElementById('fund-modal').classList.add('hidden')}
async function confirmFund(){closeFund();await sendTx('fund')}
</script>
@endpush
