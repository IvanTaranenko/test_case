<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantity = rand(0, 10);

        return [
            'name' => 'iphone '.rand(1, 5),
            'sku' => Str::random(16),
            'price' => fake()->randomFloat(2, 10, 1000),
            'description' => fake()->sentence(),
            'image' => function () {
                $files = Storage::disk('public')->files('images/products');
                if ($files && count($files)) {
                    return $files[array_rand($files)];
                }

                return null;
            },
            'quantity' => $quantity,
            'stock' => $quantity > 0 ? 'in-stock' : 'out-of-stock',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
