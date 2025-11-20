<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class MerchantProductController extends Controller
{
    // 1. Ambil Semua Produk Milik Merchant yang Login
    public function index(Request $request)
    {
        $user = $request->user();
        if (!$user->merchant) {
            return response()->json(['message' => 'Anda bukan merchant'], 403);
        }

        $products = Product::where('merchant_id', $user->merchant->id)->latest()->get();

        return response()->json(['data' => $products]);
    }

    // 2. Tambah Produk Baru
    public function store(Request $request)
    {
        $user = $request->user();
        if (!$user->merchant) {
            return response()->json(['message' => 'Anda bukan merchant'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            // 'is_available' dihapus dari validasi required karena kadang string '1'/'0' dari flutter perlu handling khusus, tapi validasi boolean biasanya aman.
            // Kita sederhanakan validasinya:
            'is_available' => 'required',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        // KONVERSI STATUS DARI FLUTTER (1/0) KE BOOLEAN
        $isAvailable = filter_var($request->is_available, FILTER_VALIDATE_BOOLEAN);

        $product = Product::create([
            'merchant_id' => $user->merchant->id,
            'category_id' => 1, // <--- TAMBAHAN PENTING: Default ke Kategori ID 1
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'image' => $imagePath,
            'is_available' => $isAvailable,
        ]);

        return response()->json(['message' => 'Produk berhasil ditambahkan', 'data' => $product], 201);
    }

    // 3. Update Produk
    public function update(Request $request, Product $product)
    {
        // Product $product akan otomatis menemukan produk berdasarkan ID
        // Pastikan hanya merchant pemilik yang bisa update produk ini
        if ($product->merchant_id != $request->user()->merchant->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'is_available' => 'required|boolean',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'is_available' => $request->is_available,
        ];

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return response()->json(['message' => 'Produk berhasil diperbarui', 'data' => $product]);
    }

    // 4. Hapus Produk
    public function destroy(Request $request, Product $product)
    {
        // Pastikan hanya merchant pemilik yang bisa menghapus produk ini
        if ($product->merchant_id != $request->user()->merchant->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Hapus gambar dari storage
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return response()->json(['message' => 'Produk berhasil dihapus']);
    }
}
