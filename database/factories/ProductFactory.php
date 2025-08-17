<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
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
        return [
            'sku' => $this->faker->unique()->bothify('SKU-###??'),
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->optional()->paragraph,
            'price' => $this->faker->randomFloat(2, 10, 500),
            'quantity' => $this->faker->numberBetween(0, 100),
            'total' => function (array $attributes) {
                return $attributes['price'] * $attributes['quantity'];
            },
            'manufacture_date' => null,
            'expiry_date' => null,
            'category_id' => Category::factory(), // creates a new category if not specified
        ];
    }
}
