<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class MerchantOrderController extends Controller
{
    // 1. Ambil Daftar Pesanan
    public function index(Request $request)
    {
        $user = $request->user();

        // Cek apakah user ini punya warung?
        if (!$user->merchant) {
            return response()->json(['message' => 'Anda bukan merchant'], 403);
        }

        $merchantId = $user->merchant->id;

        // Filter status dari parameter ?status=pending
        $status = $request->query('status');

        $query = Order::where('merchant_id', $merchantId)
            ->with(['user', 'items.product']) // Load data pembeli & detail item
            ->latest();

        if ($status) {
            $query->where('status', $status);
        }

        $orders = $query->get();

        return response()->json(['data' => $orders]);
    }

    // 2. Update Status Pesanan (Terima/Tolak/Selesai)
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:cooking,delivering,completed,canceled',
        ]);

        $order = Order::findOrFail($id);

        // Validasi kepemilikan (Jangan sampai merchant A update order merchant B)
        if ($order->merchant_id != $request->user()->merchant->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $order->update(['status' => $request->status]);

        return response()->json(['message' => 'Status berhasil diupdate', 'data' => $order]);
    }
}
