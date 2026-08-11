<?php

namespace App\Http\Controllers;

use App\Models\Transaction;

class PaymentController extends Controller
{
    public function show($transactionId)
    {
        $transaction = Transaction::where(
            'transaction_id',
            $transactionId
        )->firstOrFail();

        return view('payment.checkout', compact('transaction'));
    }

    public function pay($transactionId)
    {
        $transaction = Transaction::where(
            'transaction_id',
            $transactionId
        )->firstOrFail();

        if ($transaction->status !== 'PENDING') {
            abort(400, 'Transaction cannot be paid.');
        }

        $transaction->update([
            'status' => 'PAID',
            'paid_at' => now(),
        ]);

        return redirect()->away($transaction->return_url);
    }
}