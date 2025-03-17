@extends('components.layouts.app')

@section('content')
    <div class="bg-white">
        <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
            <div class="lg:grid lg:grid-cols-2 lg:items-start lg:gap-x-8">
                <div class="flex flex-col-reverse">
                    <div class="mx-auto mt-6 hidden w-full max-w-2xl sm:block lg:max-w-none">
                        <div class="grid grid-cols-4 gap-6" aria-orientation="horizontal" role="tablist">
                            <button id="tabs-1-tab-1" class="relative flex h-24 cursor-pointer items-center justify-center rounded-md bg-white text-sm font-medium uppercase text-gray-900 hover:bg-gray-50 focus:outline-none focus:ring focus:ring-indigo-500/50 focus:ring-offset-4" aria-controls="tabs-1-panel-1" role="tab" type="button">
                                <span class="sr-only">Product Image</span>
                                <span class="absolute inset-0 overflow-hidden rounded-md">
                                <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" class="size-full object-cover">
                            </span>
                            </button>
                        </div>
                    </div>

                    <div>
                        <div id="tabs-1-panel-1" aria-labelledby="tabs-1-tab-1" role="tabpanel" tabindex="0">
                            <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" class="aspect-square w-full object-cover sm:rounded-lg">
                        </div>
                    </div>
                </div>

                <div class="mt-10 px-4 sm:mt-16 sm:px-0 lg:mt-0">
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900">{{ $product->name }}</h1>

                    <div class="mt-3">
                        <h2 class="sr-only">Product information</h2>
                        <p class="text-3xl tracking-tight text-gray-900">${{ $product->price }}</p>
                    </div>

                    <div class="mt-6">
                        <h3 class="sr-only">Description</h3>

                        <div class="space-y-6 text-base text-gray-700">
                            <p>{!! $product->description !!}</p>
                        </div>
                    </div>
                    <div class="pb-6 pt-3" id="disclosure-1">
                        <ul role="list" class="list-disc space-y-1 pl-5 text-sm/6 text-gray-700 marker:text-gray-300">
                            @foreach($product->attributes as $attribute)
                                <li class="pl-2">{{ $attribute->name }}: {{ $attribute->value }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <form class="mt-6">
                        <button type="button" id="add-to-cart-button" class="flex max-w-xs flex-1 items-center justify-center rounded-md border border-transparent bg-indigo-600 px-8 py-3 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-50 sm:w-full" data-product-id="{{ $product->id }}">
                            Add to Cart
                        </button>
                    </form>
                    <div class="mt-4">
                        <a href="{{  route('products.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-700">
                            &larr; Return Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Cart Icon and Quantity -->
        <div class="absolute top-4 right-4 flex items-center space-x-2">
            <a href="/cart" class="relative">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-8 h-8 text-blue-500">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18l-1.5 9H4.5L3 3zM3 13h18M3 17h18" />
                </svg>
                <span id="cart-quantity" class="absolute top-0 right-0 rounded-full bg-red-500 text-white text-xs px-2 py-1">{{ session('cart_quantity', 0) }}</span>
            </a>
        </div>
    </div>

    <script>
        document.querySelector('#add-to-cart-button').addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            const quantity = 1;

            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ product_id: productId, quantity: quantity })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        let currentQuantity = parseInt(localStorage.getItem('cart_quantity') || '0');
                        currentQuantity += quantity;
                        localStorage.setItem('cart_quantity', currentQuantity);

                        document.getElementById('cart-quantity').innerText = currentQuantity;

                        window.location.href = '/cart';
                    }
                })
                .catch(error => {
                    console.error('Error adding to cart:', error);
                });
        });

        document.addEventListener('DOMContentLoaded', () => {
            let cartQuantity = localStorage.getItem('cart_quantity') || '0';
            document.getElementById('cart-quantity').innerText = cartQuantity;
        });
    </script>

@endsection
