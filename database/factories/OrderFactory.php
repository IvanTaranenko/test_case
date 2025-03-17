<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        return [
            'status' => $this->faker->randomElement(['new', 'processing', 'completed']),
            'total_price' => $this->faker->randomFloat(2, 10, 1000),
            'customer_full_name' => $this->faker->name,
            'customer_email' => $this->faker->email,
            'customer_phone' => $this->faker->phoneNumber,
            'payment_method' => $this->faker->randomElement(['c.o.d', 'online_payment']),
            'delivery_method' => $this->faker->randomElement(['pickup', 'postal_service']),
        ];
    }

    // Связь с продуктами (через pivot таблицу)
    public function configure()
    {
        return $this->afterCreating(function (Order $order) {
            $products = Product::inRandomOrder()->take(3)->get();
            foreach ($products as $product) {
                $order->products()->attach($product->id, [
                    'quantity' => rand(1, 5),
                    'price' => $product->price,
                ]);
            }
        });
    }
}
