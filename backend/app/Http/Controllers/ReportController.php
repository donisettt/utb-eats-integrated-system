<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\MerchantFee;

class ReportController extends Controller
{
    /**
     * Menampilkan laporan transaksi order
     * Filter berdasarkan tanggal dan status order
     * Default: dari awal bulan ini sampai hari ini
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', date('Y-m-01'));
        $endDate = $request->input('end_date', date('Y-m-d'));
        $status = $request->input('status', 'all');

        $query = Order::with(['user', 'merchant'])
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        if ($status != 'all') {
            $query->where('status', $status);
        }

        $orders = $query->latest()->get();
        $totalOmzet = $query->sum('total_price');

        return view('admin.reports.index', compact('orders', 'startDate', 'endDate', 'status', 'totalOmzet'));
    }

    /**
     * Menampilkan laporan transaksi order dalam format print
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function print(Request $request)
    {
        $startDate = $request->input('start_date', date('Y-m-01'));
        $endDate = $request->input('end_date', date('Y-m-d'));
        $status = $request->input('status', 'all');

        $query = Order::with(['user', 'merchant'])
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        if ($status != 'all') {
            $query->where('status', $status);
        }

        $orders = $query->latest()->get();
        $totalOmzet = $query->sum('total_price');

        return view('admin.reports.print', compact('orders', 'startDate', 'endDate', 'totalOmzet'));
    }

    /**
     * Menampilkan laporan iuran mitra
     * Filter berdasarkan tanggal dan status pembayaran (paid/unpaid)
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function fees(Request $request)
    {
        $startDate = $request->input('start_date', date('Y-m-01'));
        $endDate = $request->input('end_date', date('Y-m-d'));
        $status = $request->input('status', 'all');

        $query = MerchantFee::with('merchant.user')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        if ($status != 'all') {
            $query->where('status', $status);
        }

        $fees = $query->latest()->get();
        $totalPemasukan = $query->where('status', 'paid')->sum('amount');

        return view('admin.reports.fees_index', compact('fees', 'startDate', 'endDate', 'status', 'totalPemasukan'));
    }

    /**
     * Menampilkan laporan iuran mitra dalam format print
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function printFees(Request $request)
    {
        $startDate = $request->input('start_date', date('Y-m-01'));
        $endDate = $request->input('end_date', date('Y-m-d'));
        $status = $request->input('status', 'all');

        $query = MerchantFee::with('merchant.user')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        if ($status != 'all') {
            $query->where('status', $status);
        }

        $fees = $query->latest()->get();
        $totalPemasukan = $query->where('status', 'paid')->sum('amount');

        return view('admin.reports.fees_print', compact('fees', 'startDate', 'endDate', 'totalPemasukan'));
    }
}
