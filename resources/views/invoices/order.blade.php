<h2>Invoice</h2>

<p>
    Order ID:
    {{ $order->id }}
</p>

<p>
    Payment Status:
    {{ $order->payment_status }}
</p>

<p>
    Total:
    ₹{{ $order->total }}
</p>