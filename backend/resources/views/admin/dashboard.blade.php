@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="row g-4 mb-5">

        <div class="col-md-3">
            <div class="card h-100 border-start border-5 border-success shadow-sm">
                <div class="card-body">
                    <div class="text-muted small text-uppercase fw-bold">Total Mitra</div>
                    <div class="d-flex align-items-center mt-2">
                        <h2 class="mb-0 fw-bold text-utb-blue me-3">{{ $totalMerchants }}</h2>
                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill">+ Active</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card h-100 border-start border-5 border-primary shadow-sm">
                <div class="card-body">
                    <div class="text-muted small text-uppercase fw-bold">Pengguna Terdaftar</div>
                    <div class="d-flex align-items-center mt-2">
                        <h2 class="mb-0 fw-bold text-utb-blue me-3">{{ $totalStudents }}</h2>
                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill">Users</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card h-100 border-start border-5 border-warning shadow-sm">
                <div class="card-body">
                    <div class="text-muted small text-uppercase fw-bold">Pesanan Hari Ini</div>
                    <div class="d-flex align-items-center mt-2">
                        <h2 class="mb-0 fw-bold text-utb-blue me-3">0</h2>
                        <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill">Pending</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card h-100 border-start border-5 border-info shadow-sm">
                <div class="card-body">
                    <div class="text-muted small text-uppercase fw-bold">Total Pendapatan Iuran</div>
                    <div class="d-flex align-items-center mt-2">
                        <h3 class="mb-0 fw-bold text-utb-blue me-2" style="font-size: 1.5rem;">
                            Rp {{ number_format($totalRevenue / 1000, 0) }}k
                        </h3>
                        <span class="badge bg-info bg-opacity-10 text-info rounded-pill">Lunas</span>
                    </div>
                    <small class="text-muted" style="font-size: 0.7rem;">*Total dari iuran mitra yang lunas</small>
                </div>
            </div>
        </div>

    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom-0">
            <h5 class="mb-0 fw-bold text-utb-blue">Pesanan Terbaru</h5>
            <a href="#" class="btn btn-sm btn-outline-primary rounded-pill px-4">Lihat Semua</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-secondary">
                    <tr>
                        <th class="py-3 px-4 border-0">ID Pesanan</th>
                        <th class="py-3 px-4 border-0">Pengguna</th>
                        <th class="py-3 px-4 border-0">Warung</th>
                        <th class="py-3 px-4 border-0">Total</th>
                        <th class="py-3 px-4 border-0 text-center">Status</th>
                        <th class="py-3 px-4 border-0 text-end">Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                        <tr>
                            <td class="px-4 fw-bold text-utb-blue">#{{ $order->id }}</td>
                            <td class="px-4">{{ $order->user->name }}</td>
                            <td class="px-4">{{ $order->merchant->shop_name }}</td>
                            <td class="px-4 fw-bold">Rp {{ number_format($order->total_price) }}</td>
                            <td class="px-4 text-center">
                                @php
                                    $badgeClass = match ($order->status) {
                                        'completed' => 'bg-success bg-opacity-10 text-success',
                                        'pending' => 'bg-warning bg-opacity-10 text-warning',
                                        'cooking' => 'bg-info bg-opacity-10 text-info',
                                        'delivering' => 'bg-primary bg-opacity-10 text-primary',
                                        'canceled' => 'bg-secondary bg-opacity-10 text-secondary',
                                        default => 'bg-light text-dark border',
                                    };
                                @endphp
                                <span class="badge rounded-pill {{ $badgeClass }} px-3 py-2">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="px-4 text-end text-muted small">
                                {{ $order->created_at->diffForHumans() }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox display-4 d-block mb-3 opacity-25"></i>
                                Belum ada data pesanan masuk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
