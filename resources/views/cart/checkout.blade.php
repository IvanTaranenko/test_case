@extends('components.layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6">Checkout</h1>

        @if (session('message'))
            <div class="bg-red-100 text-red-800 p-4 mb-4 rounded-md">
                {{ session('message') }}
            </div>
        @endif
        @if(session('cart_quantity') && session('cart_quantity') > 0)
            <div class="mb-6">
                <h2 class="text-2xl font-semibold">Your Cart</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($cart as $product)
                        <div class="bg-white shadow-md rounded-lg p-4">
                            <img src="{{ asset('storage/' . $product['image']) }}" alt="{{ $product['name'] }}"
                                 class="max-w-full h-48 object-cover rounded-md">
                            <h2 class="text-xl font-semibold mt-4">{{ $product['name'] }}</h2>
                            <p class="text-gray-600 mt-2">${{ number_format($product['price'], 2) }}</p>
                            <p class="text-gray-500 text-sm">Quantity: {{ $product['quantity'] }}</p>
                        </div>
                    @endforeach
                </div>
                <div class="mt-6">
                    <p class="text-xl">Total Price: ${{ number_format($totalPrice, 2) }}</p>
                </div>
            </div>

            <form method="POST" action="{{ route('cart.processCheckout') }}">
                @csrf
                <div class="mb-4">
                    <label for="customer_full_name" class="block text-lg font-medium">Full Name</label>
                    <input type="text" id="customer_full_name" name="customer_full_name"
                           class="mt-2 p-2 border rounded-md w-full" required>
                </div>

                <div class="mb-4">
                    <label for="customer_email" class="block text-lg font-medium">Email</label>
                    <input type="email" id="customer_email" name="customer_email"
                           class="mt-2 p-2 border rounded-md w-full" required>
                </div>

                <div class="mb-4">
                    <label for="customer_phone" class="block text-lg font-medium">Phone Number</label>
                    <input type="text" id="customer_phone" name="customer_phone"
                           class="mt-2 p-2 border rounded-md w-full" required>
                </div>

                <div class="mb-4">
                    <label for="payment_method" class="block text-lg font-medium">Payment Method</label>
                    <select id="payment_method" name="payment_method" class="mt-2 p-2 border rounded-md w-full">
                        <option value="c.o.d">Cash on Delivery</option>
                        <option value="online_payment">Online Payment</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="delivery_method" class="block text-lg font-medium">Delivery Method</label>
                    <select id="delivery_method" name="delivery_method" class="mt-2 p-2 border rounded-md w-full">
                        <option value="pickup">Pickup</option>
                        <option value="postal_service">Postal Service</option>
                    </select>
                </div>

                <div class="mt-6">
                    <button type="submit" class="bg-blue-500 text-white px-6 py-3 rounded-md">Place Order</button>
                </div>
            </form>

        @else
            <p class="text-xl">Your cart is empty.</p>
        @endif
    </div>
@endsection
