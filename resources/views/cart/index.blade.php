@extends('components.layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6">Your Cart</h1>

        @if (session('cart_quantity') && session('cart_quantity') > 0)
            <div class="mb-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($cart as $product)
                        <div class="bg-white shadow-md rounded-lg p-4">
                            <a href="{{ route('product.show', $product['product_id']) }}">
                                <img src="{{ asset('storage/' . $product['image']) }}" alt="{{ $product['name'] }}"
                                     class="max-w-full h-48 object-cover rounded-md">
                            </a>
                            <h2 class="text-xl font-semibold mt-4">{{ $product['name'] }}</h2>
                            <p class="text-gray-600 mt-2">${{ number_format($product['price'], 2) }}</p>

                            <div class="flex items-center mt-2">
                                <form action="{{ route('cart.update', [$product['product_id'], 'decrease']) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="px-4 py-2 bg-gray-300 text-black rounded-md">-</button>
                                </form>

                                <span class="mx-2">{{ $product['quantity'] }}</span>

                                <form action="{{ route('cart.update', [$product['product_id'], 'increase']) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="px-4 py-2 bg-gray-300 text-black rounded-md">+</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            @if (session('cart_quantity') && session('cart_quantity') > 0)
                <div class="mt-6">
                    <p class="text-xl">Total Quantity: {{ session('cart_quantity') }}</p>
                </div>

                <div class="mt-4 flex space-x-4">
                    <a href="{{ route('cart.checkout') }}"
                       class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700">
                        Checkout
                    </a>

                    <a href="{{ route('products.index') }}"
                       class="text-sm font-medium text-indigo-600 hover:text-indigo-700">
                        &larr; Return Back
                    </a>
                </div>
            @else
                <div class="mt-4">
                    <a href="{{ route('products.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-700">
                        &larr; Return Back
                    </a>
                </div>
                <p class="text-xl">Your cart is empty.</p>
            @endif

        @endif
    </div>
@endsection
