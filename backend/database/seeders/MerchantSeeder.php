<?php

namespace Database\Seeders;

use App\Models\Merchant;
use Illuminate\Database\Seeder;

class MerchantSeeder extends Seeder
{
    /**
     * Menambahkan data merchant contoh ke database
     * Merchant ini terhubung dengan user yang dibuat di UserSeeder (user_id = 2)
     */
    public function run(): void
    {
        Merchant::create([
            'user_id' => 2, 
            'shop_name' => 'Kantin Barokah Bu Siti',
            'address' => 'Gedung A, Lantai 1, Kantin No. 3',
            'description' => 'Menyediakan masakan rumahan yang sehat dan murah meriah untuk mahasiswa.',
            'opening_time' => '08:00:00',
            'closing_time' => '16:00:00',
            'is_open' => true,
            'image' => 'https://via.placeholder.com/640x480.png/00dd22?text=Warung+Bu+Siti',
        ]);
    }
}