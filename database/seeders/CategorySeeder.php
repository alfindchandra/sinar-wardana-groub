<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Minyak Goreng', 'slug' => 'minyak-goreng', 'icon' => '🫗', 'sort_order' => 1],
            ['name' => 'Beras', 'slug' => 'beras', 'icon' => '🍚', 'sort_order' => 2],
            ['name' => 'Gula', 'slug' => 'gula', 'icon' => '🍬', 'sort_order' => 3],
            ['name' => 'Tepung', 'slug' => 'tepung', 'icon' => '🌾', 'sort_order' => 4],
            ['name' => 'Mie Instan', 'slug' => 'mie-instan', 'icon' => '🍜', 'sort_order' => 5],
            ['name' => 'Kopi & Teh', 'slug' => 'kopi-teh', 'icon' => '☕', 'sort_order' => 6],
            ['name' => 'Susu', 'slug' => 'susu', 'icon' => '🥛', 'sort_order' => 7],
            ['name' => 'Minuman', 'slug' => 'minuman', 'icon' => '🥤', 'sort_order' => 8],
            ['name' => 'Sabun & Deterjen', 'slug' => 'sabun-deterjen', 'icon' => '🧴', 'sort_order' => 9],
            ['name' => 'Bumbu & Saos', 'slug' => 'bumbu-saos', 'icon' => '🌶️', 'sort_order' => 10],
            ['name' => 'Lainnya', 'slug' => 'lainnya', 'icon' => '📦', 'sort_order' => 99],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['slug' => $category['slug']],
                array_merge($category, ['is_active' => true])
            );
        }
    }
}
