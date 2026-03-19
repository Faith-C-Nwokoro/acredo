@extends('layouts.app')
@section('title','Marketplace')
@section('page-title','Marketplace')

@section('content')
<style>
/* ── MARKETPLACE-SPECIFIC RESPONSIVE STYLES ── */

/* Filter bar stacks on mobile */
.filter-bar{display:flex;flex-direction:column;gap:12px}
.filter-row{display:flex;align-items:center;gap:10px;flex-wrap:wrap}
.filter-label{font-size:.72rem;font-weight:600;color:var(--text-3);white-space:nowrap;text-transform:uppercase;letter-spacing:.5px}
.tier-btns{display:flex;gap:5px;flex-wrap:wrap}
.tier-btn{
    padding:6px 14px;border-radius:8px;font-size:.78rem;font-weight:600;
    cursor:pointer;background:var(--elevated);border:1px solid var(--border);
    color:var(--text-2);font-family:inherit;transition:all .2s;
}
.tier-btn.active,.tier-btn:hover{border-color:var(--primary);color:var(--primary);background:rgba(99,102,241,.1)}
.tier-btn[data-tier="A"]{color:#34d399}
.tier-btn[data-tier="A"].active,.tier-btn[data-tier="A"]:hover{border-color:#34d399;background:rgba(16,185,129,.1);color:#34d399}
.tier-btn[data-tier="B"]{color:#fbbf24}
.tier-btn[data-tier="B"].active,.tier-btn[data-tier="B"]:hover{border-color:#fbbf24;background:rgba(245,158,11,.1);color:#fbbf24}
.tier-btn[data-tier="C"]{color:#fb923c}
.tier-btn[data-tier="C"].active,.tier-btn[data-tier="C"]:hover{border-color:#fb923c;background:rgba(249,115,22,.1);color:#fb923c}
.sort-select{background:var(--elevated);border:1px solid var(--border);border-radius:8px;padding:7px 10px;font-size:.78rem;color:var(--text);font-family:inherit;outline:none;cursor:pointer}
.sort-select:focus{border-color:var(--primary)}
.search-input{flex:1;min-width:140px;background:var(--elevated);border:1px solid var(--border);border-radius:8px;padding:8px 12px;font-size:.82rem;color:var(--text);font-family:inherit;outline:none;transition:border-color .2s}
.search-input:focus{border-color:var(--primary)}
.search-input::placeholder{color:var(--text-3)}

/* ── LOAN CARD (mobile-first) ── */
.lrc-head-inner{display:flex;align-items:center;gap:10px;flex:1;min-width:0}
.lrc-meta{min-width:0;flex:1}
.lrc-fund-btn{flex-shrink:0}

@media(max-width:440px){
    .lrc-time{display:none} /* hide "X hours ago" on very small screens to save space */
}

/* My Funded Loans — card layout on mobile instead of table */
.funded-table-wrap{overflow-x:auto;-webkit-overflow-scrolling:touch;border-radius:12px}
.funded-table{width:100%;border-collapse:collapse;font-size:.82rem;min-width:520px}
.funded-cards{display:none}
.funded-card{background:var(--elevated);border:1px solid var(--border);border-radius:12px;padding:14px;margin-bottom:10px}
.funded-card-row{display:flex;justify-content:space-between;align-items:center;margin-bottom:6px}
.funded-card-row:last-child{margin-bottom:0}
@media(max-width:600px){
    .funded-table-wrap{display:none}
    .funded-cards{display:block}
}
</style>

<div id="marketplace-tabs">
    <div class="tabs">
        <button class="tab-btn active">Open Requests</button>
        <button class="tab-btn">My Funded Loans</button>
    </div>

    <!-- ── TAB 1: OPEN REQUESTS ── -->
    <div class="tab-panel active" id="tab-open">

        <!-- Filter card -->
        <div class="card mb-4">
            <div class="filter-bar">
                <div class="filter-row">
                    <span class="filter-label">Tier</span>
                    <div class="tier-btns" id="tier-filter">
                        <button class="tier-btn active" data-tier="all" onclick="filterRequests('all',this)">All</button>
                        <button class="tier-btn" data-tier="A" onclick="filterRequests('A',this)">A</button>
                        <button class="tier-btn" data-tier="B" onclick="filterRequests('B',this)">B</button>
                        <button class="tier-btn" data-tier="C" onclick="filterRequests('C',this)">C</button>
                    </div>
                    <span class="filter-label" style="margin-left:4px">Sort</span>
                    <select class="sort-select" onchange="sortRequests(this.value)">
                        <option value="newest">Newest</option>
                        <option value="rate">Interest Rate</option>
                        <option value="amount">Amount</option>
                    </select>
                </div>
                <input type="text" class="search-input" placeholder="Search by BNS name…" oninput="searchRequests(this.value)">
            </div>
        </div>

        <!-- Loan request cards -->
        <div id="loan-requests-list" style="display:flex;flex-direction:column;gap:10px">
            @foreach($loanRequests as $req)
            @php
                $returnAmt = number_format($req['amount'] + $req['amount'] * $req['rate'] / 100 * $req['duration'] / 365, 4);
            @endphp
            <div class="lrc"
                 data-tier="{{ $req['tier'] }}"
                 data-bns="{{ strtolower($req['borrower']) }}"
                 data-rate="{{ $req['rate'] }}"
                 data-amount="{{ $req['amount'] }}"
                 data-posted="{{ $req['id'] }}">

                <!-- Head row: badge + name/addr | time | fund button -->
                <div class="lrc-head">
                    <div class="lrc-head-inner">
                        <span class="badge tier-{{ strtolower($req['tier']) }}" style="font-size:.7rem;padding:4px 8px;flex-shrink:0">TIER {{ $req['tier'] }}</span>
                        <div class="lrc-meta">
                            <div class="lrc-name">{{ $req['borrower'] }}</div>
                            <span class="lrc-addr mono">{{ $req['address'] }}</span>
                        </div>
                        <span class="lrc-time">{{ $req['postedAt'] }}</span>
                    </div>
                    <div class="lrc-fund-btn">
                        <button class="btn btn-primary btn-sm" onclick="openFundModal({{ json_encode($req) }}, '{{ $returnAmt }}')">Fund Loan</button>
                    </div>
                </div>

                <!-- Stats row -->
                <div class="lrc-stats">
                    <div class="lrc-stat">
                        <label>Amount</label>
                        <div class="v mono">{{ number_format($req['amount'],4) }} sBTC</div>
                    </div>
                    <div class="lrc-stat">
                        <label>Interest</label>
                        <div class="v green">{{ $req['rate'] }}%</div>
                    </div>
                    <div class="lrc-stat">
                        <label>Duration</label>
                        <div class="v">{{ $req['duration'] }} days</div>
                    </div>
                    <div class="lrc-stat">
                        <label>Return</label>
                        <div class="v mono">{{ $returnAmt }} sBTC</div>
                    </div>
                </div>

                <!-- Expandable details -->
                <div style="margin-top:10px;display:flex;justify-content:flex-end">
                    <button class="btn btn-ghost btn-sm" onclick="toggleExpand('exp-{{ $req['id'] }}',this)">Details ↓</button>
                </div>
                <div class="lrc-expand" id="exp-{{ $req['id'] }}">
                    <div class="lrc-expand-grid">
                        <div class="lrc-stat">
                            <label>Rep Score</label>
                            <div class="v" style="color:var(--primary)">{{ $req['score'] }}</div>
                        </div>
                        <div class="lrc-stat">
                            <label>Wallet Age</label>
                            <div class="v">{{ $req['walletAge'] }}d</div>
                        </div>
                        <div class="lrc-stat">
                            <label>Repaid</label>
                            <div class="v text-success">{{ $req['loansRepaid'] }}</div>
                        </div>
                        <div class="lrc-stat">
                            <label>Defaults</label>
                            <div class="v" style="{{ $req['defaults'] > 0 ? 'color:var(--danger)' : 'color:var(--success)' }}">{{ $req['defaults'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- ── TAB 2: MY FUNDED LOANS ── -->
    <div class="tab-panel" id="tab-funded">
        @if(!$connected)
        <div class="connect-prompt" style="padding:40px 20px">
            <svg width="44" height="44" fill="none" viewBox="0 0 24 24" stroke="var(--primary)" stroke-width="1.5"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/></svg>
            <h2>Connect Wallet</h2>
            <p>Connect your wallet to see loans you've funded.</p>
            <button class="btn btn-primary" onclick="toggleWallet()">Connect Wallet</button>
        </div>
        @else
        <div class="card">
            <div class="section-title">My Funded Loans</div>

            <!-- Desktop table -->
            <div class="funded-table-wrap">
                <table class="funded-table">
                    <thead>
                        <tr>
                            <th>Borrower</th><th>Amount</th><th>Rate</th><th>Days Left</th><th>Status</th><th>Expected Return</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fundedLoans as $loan)
                        @php $amt = floatval(str_replace([' sBTC',','],'', $loan['amount'])); @endphp
                        <tr>
                            <td><strong>{{ $loan['borrower'] }}</strong></td>
                            <td class="mono">{{ $loan['amount'] }}</td>
                            <td>{{ $loan['rate'] }}%</td>
                            <td>{{ $loan['daysLeft'] }} days</td>
                            <td><span class="badge badge-{{ $loan['status'] === 'ACTIVE' ? 'success' : 'warning' }}">{{ $loan['status'] }}</span></td>
                            <td class="mono text-success">+{{ number_format($amt * $loan['rate'] / 100 * $loan['daysLeft'] / 365, 4) }} sBTC</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile cards -->
            <div class="funded-cards">
                @foreach($fundedLoans as $loan)
                @php $amt = floatval(str_replace([' sBTC',','],'', $loan['amount'])); @endphp
                <div class="funded-card">
                    <div class="funded-card-row">
                        <strong style="font-size:.9rem">{{ $loan['borrower'] }}</strong>
                        <span class="badge badge-{{ $loan['status'] === 'ACTIVE' ? 'success' : 'warning' }}">{{ $loan['status'] }}</span>
                    </div>
                    <div class="funded-card-row">
                        <span class="text-xs text-muted">Amount</span>
                        <span class="mono text-sm">{{ $loan['amount'] }}</span>
                    </div>
                    <div class="funded-card-row">
                        <span class="text-xs text-muted">Rate / Duration</span>
                        <span class="text-sm">{{ $loan['rate'] }}% · {{ $loan['daysLeft'] }} days left</span>
                    </div>
                    <div class="funded-card-row">
                        <span class="text-xs text-muted">Expected Return</span>
                        <span class="mono text-sm text-success">+{{ number_format($amt * $loan['rate'] / 100 * $loan['daysLeft'] / 365, 4) }} sBTC</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<!-- ── FUND MODAL ── -->
<div id="fund-modal" class="modal-overlay hidden">
    <div class="modal">
        <div class="modal-title">Fund Loan Request</div>
        <div id="fund-modal-body"></div>
        <div class="modal-actions">
            <button class="btn btn-ghost" onclick="closeFundModal()">Cancel</button>
            <button class="btn btn-primary" style="flex:2" onclick="confirmFund()">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                Confirm Fund Loan
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// ── TABS ──
document.querySelectorAll('.tab-btn').forEach((btn,i)=>{
    btn.addEventListener('click',()=>{
        document.querySelectorAll('.tab-btn').forEach(b=>b.classList.remove('active'));
        document.querySelectorAll('.tab-panel').forEach(p=>p.classList.remove('active'));
        btn.classList.add('active');
        document.querySelectorAll('.tab-panel')[i].classList.add('active');
    });
});

// ── FILTER ──
function filterRequests(tier,el){
    document.querySelectorAll('.tier-btn').forEach(b=>b.classList.remove('active'));
    el.classList.add('active');
    document.querySelectorAll('.lrc').forEach(c=>{
        c.style.display=(tier==='all'||c.dataset.tier===tier)?'':'none';
    });
}

// ── SEARCH ──
function searchRequests(val){
    const q=val.toLowerCase();
    document.querySelectorAll('.lrc').forEach(c=>{
        c.style.display=c.dataset.bns.includes(q)?'':'none';
    });
}

// ── SORT ──
function sortRequests(by){
    const list=document.getElementById('loan-requests-list');
    const cards=[...list.querySelectorAll('.lrc')];
    cards.sort((a,b)=>{
        if(by==='rate')  return parseFloat(b.dataset.rate)-parseFloat(a.dataset.rate);
        if(by==='amount')return parseFloat(b.dataset.amount)-parseFloat(a.dataset.amount);
        return parseInt(b.dataset.posted)-parseInt(a.dataset.posted);
    });
    cards.forEach(c=>list.appendChild(c));
}

// ── EXPAND ──
function toggleExpand(id,btn){
    const el=document.getElementById(id);
    const open=el.classList.toggle('open');
    btn.textContent=open?'Details ↑':'Details ↓';
}

// ── FUND MODAL ──
let currentFundReq=null;
function openFundModal(req,returnAmt){
    currentFundReq=req;
    document.getElementById('fund-modal-body').innerHTML=`
        <div class="summary-box mb-4">
            <div class="summary-row"><span>Borrower</span><strong>${req.borrower}</strong></div>
            <div class="summary-row"><span>Tier</span><span class="badge tier-${req.tier.toLowerCase()}">${req.tier}</span></div>
            <div class="summary-row"><span>Amount</span><strong class="mono">${parseFloat(req.amount).toFixed(4)} sBTC</strong></div>
            <div class="summary-row"><span>Interest Rate</span><strong>${req.rate}%</strong></div>
            <div class="summary-row"><span>Duration</span><strong>${req.duration} days</strong></div>
            <div class="summary-row"><span>Your Return</span><strong class="mono text-success">+${(req.amount*req.rate/100*req.duration/365).toFixed(4)} sBTC</strong></div>
        </div>
        <p class="text-sm text-muted">By funding this loan your sBTC will be locked until the loan is repaid or expires.</p>`;
    document.getElementById('fund-modal').classList.remove('hidden');
}
function closeFundModal(){document.getElementById('fund-modal').classList.add('hidden')}
async function confirmFund(){closeFundModal();await sendTx('fundLoan')}
</script>
@endpush
