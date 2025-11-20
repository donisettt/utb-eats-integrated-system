<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Menambahkan data produk contoh ke database
     * Produk-produk ini terhubung dengan merchant ID 1 (Warung Bu Siti)
     */
    public function run(): void
    {
        Product::create([
            'merchant_id' => 1,
            'category_id' => 1,
            'name' => 'Nasi Goreng Spesial UTB',
            'description' => 'Nasi goreng dengan telur mata sapi, sosis, dan kerupuk.',
            'price' => 15000,
            'image' => 'https://via.placeholder.com/400x300?text=Nasi+Goreng',
            'is_available' => true,
        ]);

        Product::create([
            'merchant_id' => 1,
            'category_id' => 1,
            'name' => 'Ayam Geprek Level Mampus',
            'description' => 'Ayam crispy digeprek dengan sambal bawang super pedas + Nasi.',
            'price' => 18000,
            'image' => 'https://via.placeholder.com/400x300?text=Ayam+Geprek',
            'is_available' => true,
        ]);

        Product::create([
            'merchant_id' => 1,
            'category_id' => 2,
            'name' => 'Es Teh Manis Jumbo',
            'description' => 'Es teh manis segar ukuran gelas besar.',
            'price' => 5000,
            'image' => 'https://via.placeholder.com/400x300?text=Es+Teh',
            'is_available' => true,
        ]);

        Product::create([
            'merchant_id' => 1,
            'category_id' => 3,
            'name' => 'Roti Bakar Coklat Keju',
            'description' => 'Roti tawar bakar dengan topping melimpah.',
            'price' => 12000,
            'image' => 'https://via.placeholder.com/400x300?text=Roti+Bakar',
            'is_available' => true,
        ]);
    }
}