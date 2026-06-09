<!DOCTYPE html>
<html>

<head>
    <title>Order Confirmation</title>
</head>

<body>

    <h2>Order Confirmation</h2>

    <p>Your order has been successfully placed.</p>

    <p>
        <strong>Order ID:</strong>
        {{ $order->id }}
    </p>

    <p>
        <strong>Total Amount:</strong>
        ₹{{ $order->total_amount }}
    </p>

    <h3>Products</h3>

    <ul>
        @foreach ($order->items as $item)
            <li>
                {{ $item->product->name }}
                (Qty: {{ $item->quantity }})
            </li>
        @endforeach
    </ul>

</body>

</html>
