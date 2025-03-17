<p>Dear Admin,</p>
<p>A new order has been placed. Order details are as follows:</p>
<ul>
    <li>Order ID: {{ $order->id }}</li>
    <li>Customer Name: {{ $order->customer_full_name }}</li>
    <li>Total Price: ${{ $order->total_price }}</li>
    <li>Payment Method: {{ $order->payment_method }}</li>
    <li>Delivery Method: {{ $order->delivery_method }}</li>
</ul>
<h3>Products in your order:</h3>
<ul>
    @foreach($products as $product)
        <li>
            {{ $product->name }} (Quantity: {{ $product->pivot->quantity }}) - ${{ $product->pivot->price }}
        </li>
    @endforeach
</ul>
