<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class MerchantReportController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        if (!$user->merchant) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $merchantId = $user->merchant->id;

        // 1. Hitung Total Omzet (Hanya yang completed)
        $totalRevenue = Order::where('merchant_id', $merchantId)->where('status', 'completed')->sum('total_price');

        // 2. Hitung Total Pesanan Selesai
        $totalOrders = Order::where('merchant_id', $merchantId)->where('status', 'completed')->count();

        // 3. Ambil 10 Transaksi Terakhir (Selesai)
        $recentTransactions = Order::where('merchant_id', $merchantId)
            ->where('status', 'completed')
            ->with('user') // Ambil nama pembeli
            ->latest()
            ->take(10)
            ->get();

        return response()->json([
            'data' => [
                'total_revenue' => $totalRevenue,
                'total_orders' => $totalOrders,
                'recent_transactions' => $recentTransactions,
            ],
        ]);
    }
}
