<?php

namespace App\Http\Controllers;

use App\Services\MockDataService;

class PoolController extends Controller
{
    public function index()
    {
        return view('pages.pool', [
            'metrics'   => MockDataService::poolMetrics(),
            'chartData' => MockDataService::chartData(),
            'connected' => session('wallet_connected', false),
        ]);
    }
}
