<!DOCTYPE html>
<html>
    <style>
        .paid-message {
            text-align: center;
            padding: 14px;
            border-radius: 10px;
            background: #dcfce7;
            color: #166534;
            font-weight: 600;
        }
    </style>
<head>
    <title>Payment Checkout</title>
</head>
<body>

    <h1>Payment Checkout</h1>

    <p>Order ID: {{ $transaction->merchant_order_id }}</p>

    <p>Customer: {{ $transaction->customer_name }}</p>

    <p>
        Amount:
        {{ $transaction->currency }}
        {{ number_format($transaction->amount, 0, ',', '.') }}
    </p>

    <p>Status: {{ $transaction->status }}</p>

    @if ($transaction->status === 'PENDING')
    <form
        method="POST"
        action="{{ url('/payment/' . $transaction->transaction_id . '/pay') }}"
    >
        @csrf

        <button type="submit">
            Pay Now
        </button>
    </form>
    @else
        <div class="paid-message">
            ✓ This transaction has already been paid.
        </div>
    @endif

</body>
</html>