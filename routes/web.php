<?php

use App\Http\Controllers\LandingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\VaultController;
use App\Http\Controllers\PoolController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;

// Landing page
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Wallet
Route::post('/wallet/connect', [WalletController::class, 'connect'])->name('wallet.connect');
Route::post('/wallet/disconnect', [WalletController::class, 'disconnect'])->name('wallet.disconnect');

// App pages
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/borrow', [BorrowController::class, 'index'])->name('borrow');
Route::get('/marketplace', [MarketplaceController::class, 'index'])->name('marketplace');
Route::get('/vault', [VaultController::class, 'index'])->name('vault');
Route::get('/pool', [PoolController::class, 'index'])->name('pool');
Route::get('/profile', [ProfileController::class, 'index'])->name('profile');

// AJAX / Contract mock endpoints
Route::post('/api/transaction', function () {
    $txHash = 'mock-tx-' . substr(md5(rand()), 0, 16);
    return response()->json(['success' => true, 'txHash' => $txHash]);
})->name('api.transaction');
