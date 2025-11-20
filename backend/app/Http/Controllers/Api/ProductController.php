<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Mulai query produk
        $query = Product::with(['category', 'merchant']);

        // Filter: Jika Flutter mengirim parameter ?merchant_id=1
        if ($request->has('merchant_id')) {
            $query->where('merchant_id', $request->merchant_id);
        }

        // Filter: Hanya tampilkan menu yang tersedia (stok ada)
        $query->where('is_available', true);

        $products = $query->get();

        return response()->json([
            'message' => 'List Menu Ditemukan',
            'data' => $products
        ]);
    }
}