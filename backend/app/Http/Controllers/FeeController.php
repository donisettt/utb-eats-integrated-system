<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MerchantFee;
use App\Models\Merchant;
use Carbon\Carbon;

class FeeController extends Controller
{
    /**
     * Menampilkan daftar semua tagihan iuran mitra
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $fees = MerchantFee::with('merchant.user')->latest()->get();
        return view('admin.fees.index', compact('fees'));
    }

    /**
     * Menampilkan form untuk membuat tagihan baru
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $merchants = Merchant::all();
        return view('admin.fees.create', compact('merchants'));
    }

    /**
     * Menyimpan tagihan iuran ke database
     * Mendukung pembuatan tagihan untuk satu mitra atau semua mitra sekaligus
     * Mencegah duplikasi tagihan untuk bulan dan tahun yang sama
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'merchant_id' => 'required',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer',
            'amount' => 'required|numeric|min:0',
        ]);

        if ($request->merchant_id == 'all') {
            $merchants = Merchant::where('is_open', true)->get();
            foreach ($merchants as $merchant) {
                MerchantFee::firstOrCreate(
                    [
                        'merchant_id' => $merchant->id,
                        'month' => $request->month,
                        'year' => $request->year
                    ],
                    [
                        'amount' => $request->amount,
                        'status' => 'unpaid'
                    ]
                );
            }
            return redirect()->route('admin.fees')->with('success', 'Tagihan berhasil dibuat untuk semua mitra!');
        } else {
            MerchantFee::firstOrCreate(
                [
                    'merchant_id' => $request->merchant_id,
                    'month' => $request->month,
                    'year' => $request->year
                ],
                [
                    'amount' => $request->amount,
                    'status' => 'unpaid'
                ]
            );
            return redirect()->route('admin.fees')->with('success', 'Tagihan berhasil dibuat.');
        }
    }

    /**
     * Menandai tagihan sebagai sudah dibayar
     * Mencatat waktu pembayaran secara otomatis
     * 
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAsPaid($id)
    {
        $fee = MerchantFee::findOrFail($id);
        $fee->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        return back()->with('success', 'Pembayaran iuran berhasil dicatat.');
    }

    /**
     * Menghapus tagihan dari database
     * 
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        MerchantFee::findOrFail($id)->delete();
        return back()->with('success', 'Tagihan dihapus.');
    }
}
