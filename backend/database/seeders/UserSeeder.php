<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Menambahkan data user contoh ke database
     * Membuat akun untuk admin, merchant, dan student
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin UTB',
            'email' => 'admin@utb.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'phone' => '081234567890',
        ]);

        User::create([
            'name' => 'Ibu Siti',
            'email' => 'busiti@utb.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'merchant',
            'phone' => '081298765432',
        ]);

        User::create([
            'name' => 'Mahasiswa UTB',
            'email' => 'mhs@utb.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'student',
            'phone' => '081211223344',
        ]);
    }
}