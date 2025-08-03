<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use App\Models\Product;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         User::factory(10)->create();

        $categories = [
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

        foreach ($categories as $name) {
            Category::create(['name' => $name]);
        }

        $allCategories = Category::all();

        Product::factory(50)->make()->each(function ($product) use ($allCategories) {
            $product->category_id = $allCategories->random()->id;
            $product->save();
        });
    }
}
