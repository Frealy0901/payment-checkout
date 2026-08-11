<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'merchant_order_id' => [
                'required',
                'string',
                'unique:transactions,merchant_order_id',
            ],
            'amount' => [
                'required',
                'numeric',
                'gt:0',
            ],
            'currency' => [
                'required',
                'string',
                'size:3',
            ],
            'customer_name' => [
                'required',
                'string',
            ],
            'customer_email' => [
                'required',
                'email',
            ],
            'callback_url' => [
                'required',
                'url',
            ],
            'return_url' => [
                'required',
                'url',
            ],
        ]);

        $transaction = Transaction::create([
            'transaction_id' => 'TRX' . strtoupper(Str::random(10)),
            'merchant_order_id' => $validated['merchant_order_id'],
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'amount' => $validated['amount'],
            'currency' => $validated['currency'],
            'callback_url' => $validated['callback_url'],
            'return_url' => $validated['return_url'],
            'status' => 'PENDING',
        ]);

        return response()->json([
            'transaction_id' => $transaction->transaction_id,
            'checkout_url' => url('/payment/' . $transaction->transaction_id),
            'status' => $transaction->status,
        ], 201);
    }
}