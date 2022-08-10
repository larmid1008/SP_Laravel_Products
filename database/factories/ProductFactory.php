<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    public function configure()
    {
        return $this->afterCreating(function (Product $product) {
            $product->categories()->attach(
                Category::inRandomOrder()
                    ->limit(rand(2, 10))
                    ->get()
            );
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->text(800),
            'price' => $this->faker->randomFloat(),
            'published_at' => rand(0, 1) ? now() : null,
        ];
    }
}
