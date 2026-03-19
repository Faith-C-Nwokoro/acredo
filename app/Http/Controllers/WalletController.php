<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MockDataService;

class WalletController extends Controller
{
    public function connect(Request $request)
    {
        $wallet = MockDataService::wallet();
        session([
            'wallet_connected'  => true,
            'wallet_address'    => $wallet['address'],
            'wallet_bns'        => $wallet['bnsName'],
            'wallet_score'      => $wallet['reputationScore'],
            'wallet_tier'       => $wallet['reputationTier'],
        ]);
        return response()->json(['success' => true, 'wallet' => $wallet]);
    }

    public function disconnect(Request $request)
    {
        session()->forget(['wallet_connected','wallet_address','wallet_bns','wallet_score','wallet_tier']);
        return response()->json(['success' => true]);
    }
}
