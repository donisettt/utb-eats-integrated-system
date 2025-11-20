<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MerchantController extends Controller
{
    public function index()
    {
        // Ambil semua merchant yang sedang BUKA (is_open = true)
        // Sertakan data user juga biar namanya muncul
        $merchants = Merchant::with('user')->where('is_open', true)->get();

        return response()->json([
            'message' => 'List Warung Ditemukan',
            'data' => $merchants,
        ]);
    }

    // Method Update Profil Toko
    public function update(Request $request)
    {
        $user = $request->user();
        $merchant = $user->merchant;

        if (!$merchant) {
            return response()->json(['message' => 'Merchant not found'], 404);
        }

        $request->validate([
            'shop_name' => 'required|string',
            'address' => 'required|string',
            'description' => 'nullable|string',
            'opening_time' => 'nullable|date_format:H:i', // Format 08:00
            'closing_time' => 'nullable|date_format:H:i',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = [
            'shop_name' => $request->shop_name,
            'address' => $request->address,
            'description' => $request->description,
            'opening_time' => $request->opening_time,
            'closing_time' => $request->closing_time,
        ];

        // Handle Upload Foto Baru
        if ($request->hasFile('image')) {
            // Hapus foto lama
            if ($merchant->image && Storage::disk('public')->exists($merchant->image)) {
                Storage::disk('public')->delete($merchant->image);
            }
            $data['image'] = $request->file('image')->store('merchants', 'public');
        }

        $merchant->update($data);

        return response()->json([
            'message' => 'Profil toko berhasil diperbarui',
            'data' => $merchant,
        ]);
    }

    // Method Get Profil Toko
    public function show(Request $request)
    {
        $user = $request->user();
        
        if (!$user->merchant) {
            return response()->json(['message' => 'Merchant not found'], 404);
        }

        return response()->json([
            'data' => $user->merchant
        ]);
    }
}
