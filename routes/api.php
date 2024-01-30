<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WalletController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v1')->group(function () {

    // auth routes:
    Route::prefix('auth')->as('auth.')->group(function () {
        Route::post('login', [AuthController::class, 'login'])->name('login');
    });

    Route::middleware('auth:api')->group(function () {
        // wallet routes:
        Route::prefix('wallet')->as('wallet.')->group(function () {
            Route::get('/', [WalletController::class, 'all'])->name('all');
            Route::get('/{wallet}/show', [WalletController::class, 'show'])->can('own', 'wallet')->name('show');
            Route::post('/{wallet}/increase', [WalletController::class, 'increase'])->can('own', 'wallet')->name('increase');
            Route::post('/{wallet}/decrease', [WalletController::class, 'decrease'])->can('own', 'wallet')->name('decrease');
        });
        // asset routes:
        Route::prefix('asset')->as('asset')->group(function () {
            Route::post('/{numeratorAsset}/{denominatorAsset}/conversion', [AssetController::class, 'conversion'])->name('conversion');
        });
        // transaction routes:
        Route::prefix('transaction')->as('transaction.')->group(function () {
            Route::get('/{asset}/', [TransactionController::class, 'show'])->name('show');
        });
    });


});
