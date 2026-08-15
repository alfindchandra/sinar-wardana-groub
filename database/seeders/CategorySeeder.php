<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['id' => 1, 'name' => 'Minyak Goreng', 'slug' => 'minyak-goreng', 'icon' => '🫗', 'sort_order' => 1],
            ['id' => 2, 'name' => 'Gula', 'slug' => 'gula', 'icon' => '🍬', 'sort_order' => 2],
            ['id' => 3, 'name' => 'Tepung', 'slug' => 'tepung', 'icon' => '🌾', 'sort_order' => 3],
            ['id' => 4, 'name' => 'Mie', 'slug' => 'mie', 'icon' => '🍜', 'sort_order' => 4],
            ['id' => 5, 'name' => 'Bumbu', 'slug' => 'bumbu', 'icon' => '🌶️', 'sort_order' => 5],
            ['id' => 6, 'name' => 'Kopi & Susu', 'slug' => 'kopi-susu', 'icon' => '☕', 'sort_order' => 6],
            ['id' => 7, 'name' => 'Minuman', 'slug' => 'minuman', 'icon' => '🥤', 'sort_order' => 7],
            ['id' => 8, 'name' => 'Sabun & Deterjen', 'slug' => 'sabun-deterjen', 'icon' => '🧴', 'sort_order' => 8],
            ['id' => 9, 'name' => 'Shampoo', 'slug' => 'shampoo', 'icon' => '🧴', 'sort_order' => 9],
            ['id' => 10, 'name' => 'Obat Nyamuk', 'slug' => 'obat-nyamuk', 'icon' => '🦟', 'sort_order' => 10],
            ['id' => 11, 'name' => 'Lainnya', 'slug' => 'lainnya', 'icon' => '📦', 'sort_order' => 99],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['id' => $category['id']],
                array_merge($category, ['is_active' => true])
            );
        }
    }
}