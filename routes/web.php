<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::get('/', function () {
    return view('welcome');
});

Route::get(
    '/payment/{transaction_id}',
    [PaymentController::class, 'show']
);

Route::post(
    '/payment/{transaction_id}/pay',
    [PaymentController::class, 'pay']
);