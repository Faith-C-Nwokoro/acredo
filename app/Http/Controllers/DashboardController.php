<?php

namespace App\Http\Controllers;

use App\Services\MockDataService;

class DashboardController extends Controller
{
    public function index()
    {
        return view('pages.dashboard', [
            'wallet'      => MockDataService::wallet(),
            'activeLoans' => MockDataService::activeLoans(),
            'vault'       => MockDataService::vaultPosition(),
            'connected'   => session('wallet_connected', false),
        ]);
    }
}
