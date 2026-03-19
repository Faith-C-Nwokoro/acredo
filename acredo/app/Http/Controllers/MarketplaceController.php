<?php

namespace App\Http\Controllers;

use App\Services\MockDataService;

class MarketplaceController extends Controller
{
    public function index()
    {
        return view('pages.marketplace', [
            'loanRequests' => MockDataService::loanRequests(),
            'fundedLoans'  => MockDataService::fundedLoans(),
            'connected'    => session('wallet_connected', false),
        ]);
    }
}
