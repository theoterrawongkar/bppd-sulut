<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class ProductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create users with roles
        $admin = User::factory()->create([
            'name' => 'Admin BPPD',
            'email' => 'admin@bppd.com',
            'role' => 'Admin',
            'is_active' => true,
        ]);

        // 2. Create categories and subcategories
        $categoryData = [
            'Kuliner' => ['Makanan Berat', 'Minuman', 'Camilan'],
            'Wisata' => ['Desa Pariwisata', 'Pulau', 'Pantai', 'Gunung', 'Taman'],
            'Event' => ['Konser', 'Pameran', 'Pertunjukan', 'Bazar']
        ];

        $subCategoriesMap = [];

        foreach ($categoryData as $categoryName => $subNames) {
            $category = Category::factory()->create([
                'name' => $categoryName,
                'slug' => Str::slug($categoryName),
            ]);

            foreach ($subNames as $subName) {
                $subCategoriesMap[$categoryName][] = SubCategory::factory()->create([
                    'category_id' => $category->id,
                    'name' => $subName,
                    'slug' => Str::slug($subName),
                ]);
            }
        }
    }
}
