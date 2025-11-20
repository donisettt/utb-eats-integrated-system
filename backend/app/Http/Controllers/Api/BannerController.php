<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;

class BannerController extends Controller
{
    public function index()
    {
        // Ambil banner yang aktif saja
        $banners = Banner::where('is_active', true)->latest()->get();

        return response()->json([
            'data' => $banners
        ]);
    }
}