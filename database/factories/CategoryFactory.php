<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $medicalCategories = [
            'Antibiotics',
            'Pain Relievers',
            'Vitamins & Supplements',
            'First Aid',
            'Dermatology',
            'Cardiovascular',
            'Diabetes Care',
            'Respiratory',
            'Gastrointestinal',
            'Medical Equipment',
        ];

        return [
            'name' => $this->faker->randomElement($medicalCategories),
        ];
    }
}
