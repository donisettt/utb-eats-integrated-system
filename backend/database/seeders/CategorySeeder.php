<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Menambahkan data kategori produk default ke database
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Makanan Berat'],
            ['name' => 'Minuman'],
            ['name' => 'Snack & Cemilan'],
            ['name' => 'Aneka Mie'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}