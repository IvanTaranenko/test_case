@extends('components.layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6">Products</h1>

    <form method="GET" action="{{ route('products.index') }}" class="mb-6 flex items-center space-x-4">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Seach by name or sku"
               class="px-4 py-2 border rounded-md">
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Search</button>
    </form>
    <div class="absolute top-4 right-4 flex items-center space-x-2">
        <a href="/cart" class="relative">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-8 h-8 text-blue-500">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18l-1.5 9H4.5L3 3zM3 13h18M3 17h18" />
            </svg>
            <span id="cart-quantity" class="absolute top-0 right-0 rounded-full bg-red-500 text-white text-xs px-2 py-1">{{ session('cart_quantity', 0) }}</span>
        </a>
    </div>
    <div class="mb-8">
        <form method="GET" action="{{ route('products.index') }}" class="flex items-center space-x-4">
            <select name="sort" onchange="this.form.submit()" class="lg=:px-8 py-2 border rounded-md">
                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Alphabetical (A-Z)</option>
                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Alphabetical (Z-A)</option>
                <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>Price (Low to High)</option>
                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price (High to Low)</option>
                <option value="stock" {{ request('sort') == 'stock' ? 'selected' : '' }}>Stock (In Stock)</option>
                <option value="stock_desc" {{ request('sort') == 'stock_desc' ? 'selected' : '' }}>Stock (Out of Stock)</option>
            </select>
        </form>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($products as $product)
            <div class="bg-white shadow-md rounded-lg p-4">
                <a href="{{ route('product.show', $product->id) }}">
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                         class="max-w-full h-48 object-cover rounded-md">
                </a>
                <a href="{{ route('product.show', $product->id) }}">
                    <h2 class="text-xl font-semibold mt-4">{{ $product->name }}</h2>
                </a>
                <p class="text-gray-600 mt-2">${{ number_format($product->price, 2) }}</p>
                <p class="text-gray-600 mt-2">{{ $product->sku}}</p>
                <p class="text-gray-500 text-sm">Stock: {{ $product->stock }}</p>
                <button type="button" class="add-to-cart-button px-6 py-3 bg-blue-500 text-white rounded-lg" data-product-id="{{ $product->id }}">
                    Add to Cart
                </button>
            </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $products->appends(request()->query())->links() }}
    </div>
</div>
<script>
    document.querySelectorAll('.add-to-cart-button').forEach(button => {
        button.addEventListener('click', function() {
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
                    }
                })
                .catch(error => {
                    console.error('Error adding to cart:', error);
                });
        });
    });

    document.addEventListener('DOMContentLoaded', () => {
        let cartQuantity = localStorage.getItem('cart_quantity') || '0';
        document.getElementById('cart-quantity').innerText = cartQuantity;
    });

</script>

@endsection
