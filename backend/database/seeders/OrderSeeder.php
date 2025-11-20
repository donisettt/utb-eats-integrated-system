<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Merchant;
use App\Models\Product;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    /**
     * Menambahkan data order contoh ke database
     * Membuat 15 transaksi dummy dengan tanggal acak dalam bulan ini
     */
    public function run(): void
    {
        $student = User::where('role', 'student')->first();
        $merchants = Merchant::all();

        if (!$student || $merchants->isEmpty()) {
            $this->command->info('Pastikan User Mahasiswa dan Merchant sudah ada dulu!');
            return;
        }

        for ($i = 0; $i < 15; $i++) {
            $merchant = $merchants->random();
            $products = $merchant->products()->inRandomOrder()->take(rand(1, 2))->get();

            if ($products->isEmpty()) {
                continue;
            }

            $date = Carbon::now()
                ->startOfMonth()
                ->addDays(rand(0, Carbon::now()->day - 1))
                ->addHours(rand(8, 18));

            $order = Order::create([
                'user_id' => $student->id,
                'merchant_id' => $merchant->id,
                'total_price' => 0,
                'status' => $this->getRandomStatus(),
                'delivery_location' => 'Gedung B, Lantai 2',
                'created_at' => $date,
                'updated_at' => $date,
            ]);

            $totalPrice = 0;

            foreach ($products as $product) {
                $qty = rand(1, 3);
                $price = $product->price * $qty;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'price' => $product->price,
                ]);

                $totalPrice += $price;
            }

            $order->update(['total_price' => $totalPrice]);
        }
    }

    /**
     * Mengembalikan status order secara acak
     * 60% kemungkinan completed, 20% pending, 20% canceled
     * 
     * @return string
     */
    private function getRandomStatus()
    {
        $statuses = ['completed', 'completed', 'completed', 'pending', 'canceled'];
        return $statuses[rand(0, 4)];
    }
}
