<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::factory(3)->create();

        Category::all()->each(function ($category) {
            $category->children()->saveMany(Category::factory(2)->make());
        });


        $some_categories = [
            ['name' => 'Electronics', 'description' => 'Devices and gadgets'],
            ['name' => 'Books', 'description' => 'Literature and educational materials'],
            ['name' => 'Clothing', 'description' => 'Apparel and accessories'],
            ['name' => 'Home & Kitchen', 'description' => 'Household items and kitchenware'],
            ['name' => 'Sports & Outdoors', 'description' => 'Equipment for sports and outdoor activities'],
        ];
        foreach ($some_categories as $category) {
            Category::create($category);
        }
    }
}
