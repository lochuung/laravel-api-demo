<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $some_categories = [
            ['name' => 'Bánh Kẹo', 'description' => 'Các loại bánh ngọt, kẹo, snack, và đồ ăn vặt'],
            ['name' => 'Bánh quy', 'description' => 'Các loại bánh quy giòn, mềm, có nhân...'],
            ['name' => 'Kẹo', 'description' => 'Kẹo ngọt, kẹo cao su, kẹo dẻo, kẹo cứng...'],
            ['name' => 'Snack', 'description' => 'Snack khoai tây, snack rong biển, bim bim...'],
            ['name' => 'Socola', 'description' => 'Các loại chocolate: đen, sữa, hạt nhân...'],
        ];

        foreach ($some_categories as $category) {
            Category::create($category);
        }
    }
}
