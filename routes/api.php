<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\TransactionController;

Route::middleware('api.key')->group(function () {
    Route::post('/checkout', [CheckoutController::class, 'store']);

    Route::get(
        '/transactions/{transaction_id}',
        [TransactionController::class, 'show']
    );
});