<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        // Ambil order milik user yang sedang login, urutkan dari yang terbaru
        $orders = $request
            ->user()
            ->orders()
            ->with('merchant') // Sertakan data warung
            ->latest()
            ->get();

        return response()->json([
            'data' => $orders,
        ]);
    }

    // Tambahkan di Api/OrderController.php
    public function store(Request $request)
    {
        $request->validate([
            'merchant_id' => 'required',
            'total_price' => 'required',
            'items' => 'required|array',
        ]);

        $order = \App\Models\Order::create([
            'user_id' => $request->user()->id,
            'merchant_id' => $request->merchant_id,
            'total_price' => $request->total_price,
            'status' => 'pending',
            'delivery_location' => $request->delivery_location,
        ]);

        foreach ($request->items as $item) {
            \App\Models\OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        return response()->json(['message' => 'Order berhasil', 'data' => $order]);
    }
}
