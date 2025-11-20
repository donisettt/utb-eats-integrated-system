<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class PublicProductController extends Controller
{
    public function search(Request $request)
    {
        $keyword = $request->query('keyword');

        $query = Product::with('merchant') // Sertakan data warung
            ->where('is_available', true);

        if ($keyword) {
            $query->where('name', 'like', '%' . $keyword . '%');
        }

        $products = $query->latest()->get();

        return response()->json([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Data produk ditemukan',
            ],
            'data' => $products,
        ]);
    }
}
