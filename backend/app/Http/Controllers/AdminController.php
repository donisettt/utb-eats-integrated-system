<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Merchant;
use App\Models\Order;
use App\Models\MerchantFee;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /**
     * Menampilkan halaman dashboard admin dengan statistik utama
     * 
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        $totalMerchants = Merchant::count();
        $totalStudents = User::where('role', 'student')->count();
        $recentOrders = Order::with('user', 'merchant')->latest()->take(5)->get();
        $totalRevenue = MerchantFee::where('status', 'paid')->sum('amount');

        return view(
            'admin.dashboard',
            compact(
                'totalMerchants',
                'totalStudents',
                'recentOrders',
                'totalRevenue',
            ),
        );
    }

    /**
     * Menampilkan daftar semua mitra/merchant
     * 
     * @return \Illuminate\View\View
     */
    public function merchants()
    {
        $merchants = Merchant::with('user')->get();
        return view('admin.merchants.index', compact('merchants'));
    }

    /**
     * Menampilkan form untuk menambah mitra baru
     * 
     * @return \Illuminate\View\View
     */
    public function createMerchant()
    {
        return view('admin.merchants.create');
    }

    /**
     * Menyimpan data mitra baru ke database
     * Membuat user account dan merchant record secara bersamaan
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeMerchant(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'shop_name' => 'required',
            'phone' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('merchants', 'public');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make('password123'),
            'role' => 'merchant',
            'phone' => $request->phone,
        ]);

        Merchant::create([
            'user_id' => $user->id,
            'shop_name' => $request->shop_name,
            'is_open' => false,
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.merchants')->with('success', 'Mitra berhasil ditambahkan!');
    }

    /**
     * Menampilkan form edit untuk mitra tertentu
     * 
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function editMerchant($id)
    {
        $merchant = Merchant::with('user')->findOrFail($id);
        return view('admin.merchants.edit', compact('merchant'));
    }

    /**
     * Memperbarui data mitra yang sudah ada
     * Menangani update user account, merchant data, dan upload gambar baru
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateMerchant(Request $request, $id)
    {
        $merchant = Merchant::findOrFail($id);
        $user = $merchant->user;

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'shop_name' => 'required',
            'phone' => 'required',
            'password' => 'nullable|min:6',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imagePath = $merchant->image;
        if ($request->hasFile('image')) {
            if ($merchant->image && Storage::disk('public')->exists($merchant->image)) {
                Storage::disk('public')->delete($merchant->image);
            }
            $imagePath = $request->file('image')->store('merchants', 'public');
        }

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ];
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }
        $user->update($userData);

        $merchant->update([
            'shop_name' => $request->shop_name,
            'is_open' => $request->has('is_open'),
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.merchants')->with('success', 'Data Mitra berhasil diperbarui!');
    }

    /**
     * Menghapus data mitra dan user account terkait
     * 
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyMerchant($id)
    {
        $merchant = Merchant::findOrFail($id);
        $user = $merchant->user;

        $merchant->delete();
        $user->delete();

        return redirect()->route('admin.merchants')->with('success', 'Mitra berhasil dihapus.');
    }

    /**
     * Menampilkan daftar semua mahasiswa, dosen, dan staff
     * 
     * @return \Illuminate\View\View
     */
    public function students()
    {
        $students = User::whereIn('role', ['student', 'lecturer', 'staff'])
            ->latest()
            ->get();

        return view('admin.students.index', compact('students'));
    }

    /**
     * Menampilkan form edit untuk mahasiswa tertentu
     * 
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function editStudent($id)
    {
        $student = User::where('role', 'student')->findOrFail($id);
        return view('admin.students.edit', compact('student'));
    }

    /**
     * Memperbarui data mahasiswa
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStudent(Request $request, $id)
    {
        $student = User::where('role', 'student')->findOrFail($id);

        $request->validate([
            'name' => 'required',
            'nim' => 'nullable|string|unique:users,nim,' . $student->id,
            'email' => 'required|email|unique:users,email,' . $student->id,
            'phone' => 'required',
            'password' => 'nullable|min:6',
            'role' => 'required|in:student,lecturer,staff',
        ]);

        $data = [
            'name' => $request->name,
            'nim' => $request->nim,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $student->update($data);

        return redirect()->route('admin.students')->with('success', 'Data Mahasiswa berhasil diperbarui.');
    }

    /**
     * Menghapus data mahasiswa
     * 
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyStudent($id)
    {
        $student = User::where('role', 'student')->findOrFail($id);
        $student->delete();

        return redirect()->route('admin.students')->with('success', 'Mahasiswa berhasil dihapus.');
    }

    /**
     * Menampilkan form untuk menambah mahasiswa baru
     * 
     * @return \Illuminate\View\View
     */
    public function createStudent()
    {
        return view('admin.students.create');
    }

    /**
     * Menyimpan data mahasiswa baru ke database
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeStudent(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'nim' => 'nullable|unique:users,nim',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required',
            'role' => 'required|in:student,lecturer,staff',
        ]);

        User::create([
            'name' => $request->name,
            'nim' => $request->nim,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make('password123'),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.students')->with('success', 'Data Pelanggan berhasil ditambahkan.');
    }
}
