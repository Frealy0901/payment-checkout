<?php

namespace App\Http\Controllers;

use App\Models\Transaction;

class TransactionController extends Controller
{
    public function show($transactionId)
    {
        $transaction = Transaction::where(
            'transaction_id',
            $transactionId
        )->firstOrFail();

        return response()->json([
            'transaction_id' => $transaction->transaction_id,
            'merchant_order_id' => $transaction->merchant_order_id,
            'amount' => $transaction->amount,
            'customer_name' => $transaction->customer_name,
            'payment_status' => $transaction->status,
            'created_at' => $transaction->created_at,
            'paid_at' => $transaction->paid_at,
        ]);
    }
}