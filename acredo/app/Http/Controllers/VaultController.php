<?php

namespace App\Http\Controllers;

use App\Services\MockDataService;

class VaultController extends Controller
{
    public function index()
    {
        return view('pages.vault', [
            'wallet'    => MockDataService::wallet(),
            'vault'     => MockDataService::vaultPosition(),
            'chartData' => MockDataService::chartData(),
            'connected' => session('wallet_connected', false),
        ]);
    }
}
