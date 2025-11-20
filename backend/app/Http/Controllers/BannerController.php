<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    /**
     * Menampilkan daftar semua banner
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $banners = Banner::latest()->get();
        return view('admin.banners.index', compact('banners'));
    }

    /**
     * Menampilkan form untuk menambah banner baru
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.banners.create');
    }

    /**
     * Menyimpan banner baru ke database
     * Menangani upload gambar banner (maksimal 2MB)
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'title' => 'nullable|string|max:255',
        ]);

        $path = $request->file('image')->store('banners', 'public');

        Banner::create([
            'title' => $request->title,
            'image_path' => $path,
            'is_active' => true,
        ]);

        return redirect()->route('admin.banners')->with('success', 'Banner berhasil diupload!');
    }

    /**
     * Menghapus banner dari database
     * Menghapus file gambar terkait untuk menghemat storage
     * 
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);
        
        if(Storage::disk('public')->exists($banner->image_path)){
            Storage::disk('public')->delete($banner->image_path);
        }
        
        $banner->delete();
        return back()->with('success', 'Banner dihapus.');
    }

    /**
     * Mengubah status aktif/nonaktif banner
     * 
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggle($id)
    {
        $banner = Banner::findOrFail($id);
        $banner->update(['is_active' => !$banner->is_active]);
        return back()->with('success', 'Status banner diubah.');
    }
}