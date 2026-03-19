<?php

namespace App\Http\Controllers;

use App\Services\MockDataService;
use Illuminate\Http\Request;

class BorrowController extends Controller
{
    public function index(Request $request)
    {
        return view('pages.borrow', [
            'wallet'    => MockDataService::wallet(),
            'nfts'      => MockDataService::nfts(),
            'vault'     => MockDataService::vaultPosition(),
            'activeTab' => $request->query('tab', 'reputation'),
            'connected' => session('wallet_connected', false),
        ]);
    }
}
