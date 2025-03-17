<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmation;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CartController extends Controller
{
    public function showCart()
    {
        $cart = session()->get('cart', []);
        $cartQuantity = session('cart_quantity', 0);
        $totalPrice = 0;

        foreach ($cart as $productId => &$product) {
            $productDetails = Product::find($productId);
            if ($productDetails) {
                $product['name'] = $productDetails->name;
                $product['price'] = $productDetails->price;
                $product['image'] = $productDetails->image;
                $totalPrice += $product['quantity'] * $product['price'];
            }
        }

        return view('cart.index', compact('cart', 'cartQuantity', 'totalPrice'));
    }

    public function updateQuantity(Request $request, $productId, $operation)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            if ($operation == 'increase') {
                $cart[$productId]['quantity']++;
            } elseif ($operation == 'decrease' && $cart[$productId]['quantity'] > 1) {
                $cart[$productId]['quantity']--;
            }

            if ($cart[$productId]['quantity'] == 0) {
                unset($cart[$productId]);
            }
        }

        session(['cart' => $cart]);
        session(['cart_quantity' => array_sum(array_column($cart, 'quantity'))]);

        if (session('cart_quantity') == 0) {
            return redirect()->route('products.index')->with('message', 'Your cart is empty.');
        }

        return redirect()->route('cart.show');
    }

    public function addToCart(Request $request)
    {
        $cart = session()->get('cart', []);
        $productId = $request->input('product_id');
        $quantity = (int) $request->input('quantity', 1);

        $product = Product::find($productId);

        if ($product) {
            if (isset($cart[$productId])) {
                $cart[$productId]['quantity'] += $quantity;
            } else {
                $cart[$productId] = [
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'name' => $product->name,
                    'price' => $product->price,
                    'image' => $product->image,
                ];
            }

            session(['cart' => $cart]);
            session(['cart_quantity' => array_sum(array_column($cart, 'quantity'))]);

            return response()->json(['success' => true]);
        } else {
            return response()->json(['error' => 'Product not found'], 404);
        }
    }

    public function showCheckoutForm()
    {
        $cart = session()->get('cart', []);
        $cartQuantity = session('cart_quantity', 0);
        $totalPrice = array_sum(array_map(fn ($product) => $product['quantity'] * $product['price'], $cart));

        return view('cart.checkout', compact('cart', 'cartQuantity', 'totalPrice'));
    }

    public function processCheckout(Request $request)
    {
        $validated = $request->validate([
            'customer_full_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'customer_phone' => 'required|string|max:15',
            'payment_method' => 'required|in:c.o.d,online_payment',
            'delivery_method' => 'required|in:pickup,postal_service',
        ]);

        $cart = session()->get('cart', []);
        $totalPrice = array_sum(array_map(fn ($product) => $product['quantity'] * $product['price'], $cart));

        $order = Order::create([
            'status' => 'new',
            'total_price' => $totalPrice,
            'customer_full_name' => $validated['customer_full_name'],
            'customer_email' => $validated['customer_email'],
            'customer_phone' => $validated['customer_phone'],
            'payment_method' => $validated['payment_method'],
            'delivery_method' => $validated['delivery_method'],
        ]);

        foreach ($cart as $productId => $product) {
            DB::table('order_product')->insert([
                'order_id' => $order->id,
                'product_id' => $productId,
                'quantity' => $product['quantity'],
                'price' => $product['price'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        session()->forget('cart');
        session()->forget('cart_quantity');

        $orderProducts = $order->products;

        Mail::to($order->customer_email)->send(new OrderConfirmation($order, $orderProducts, 'customer'));

        $users = User::all();
        foreach ($users as $user) {
            Mail::to($user->email)->send(new OrderConfirmation($order, $orderProducts, 'admin'));
        }

        return redirect()->route('cart.checkout')->with('success', 'Your order has been placed successfully!');
    }
}
