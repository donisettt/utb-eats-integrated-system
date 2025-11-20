<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Merchant;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Menampilkan daftar semua produk dengan relasi merchant dan category
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $products = Product::with(['merchant', 'category'])
            ->latest()
            ->get();
        return view('admin.products.index', compact('products'));
    }

    /**
     * Menampilkan form untuk menambah produk baru
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $merchants = Merchant::all();
        $categories = Category::all();
        return view('admin.products.create', compact('merchants', 'categories'));
    }

    /**
     * Menyimpan produk baru ke database
     * Menangani upload gambar produk jika ada
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'merchant_id' => 'required|exists:merchants,id',
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        Product::create([
            'merchant_id' => $request->merchant_id,
            'category_id' => $request->category_id,
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'image' => $imagePath,
            'is_available' => true,
        ]);

        return redirect()->route('admin.products')->with('success', 'Menu berhasil ditambahkan!');
    }

    /**
     * Menampilkan form edit untuk produk tertentu
     * 
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $merchants = Merchant::all();
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'merchants', 'categories'));
    }

    /**
     * Memperbarui data produk yang sudah ada
     * Menangani update gambar dan status ketersediaan produk
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'merchant_id' => 'required',
            'category_id' => 'required',
            'name' => 'required',
            'price' => 'required|numeric',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $data['is_available'] = $request->has('is_available');

        $product->update($data);

        return redirect()->route('admin.products')->with('success', 'Menu berhasil diperbarui!');
    }

    /**
     * Menghapus produk dari database
     * Menghapus file gambar terkait jika ada
     * 
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return back()->with('success', 'Menu berhasil dihapus.');
    }
}
