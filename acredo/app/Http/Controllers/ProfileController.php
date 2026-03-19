<?php

namespace App\Http\Controllers;

use App\Services\MockDataService;

class ProfileController extends Controller
{
    public function index()
    {
        return view('pages.profile', [
            'wallet'      => MockDataService::wallet(),
            'breakdown'   => MockDataService::reputationBreakdown(),
            'loanHistory' => MockDataService::loanHistory(),
            'vaultHistory'=> MockDataService::vaultHistory(),
            'connected'   => session('wallet_connected', false),
        ]);
    }
}
