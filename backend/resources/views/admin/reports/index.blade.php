@extends('layouts.admin')

@section('title', 'Laporan Transaksi')

@section('content')
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <form action="{{ route('admin.reports') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-bold small text-muted">Dari Tanggal</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold small text-muted">Sampai Tanggal</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold small text-muted">Status Pesanan</label>
                    <select name="status" class="form-select">
                        <option value="all" {{ $status == 'all' ? 'selected' : '' }}>Semua Status</option>
                        <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>Selesai (Completed)
                        </option>
                        <option value="canceled" {{ $status == 'canceled' ? 'selected' : '' }}>Dibatalkan</option>
                        <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-filter"></i> Filter</button>

                    <a href="{{ route('admin.reports.print', ['start_date' => $startDate, 'end_date' => $endDate, 'status' => $status]) }}"
                        target="_blank" class="btn btn-utb w-100">
                        <i class="bi bi-printer"></i> Cetak
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 fw-bold text-utb-blue">
                Hasil Laporan: <span class="text-muted fw-normal">{{ date('d M Y', strtotime($startDate)) }} -
                    {{ date('d M Y', strtotime($endDate)) }}</span>
            </h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3">ID Order</th>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Pelanggan</th>
                        <th class="px-4 py-3">Mitra Warung</th>
                        <th class="px-4 py-3">Total</th>
                        <th class="px-4 py-3 text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td class="px-4 fw-bold">#{{ $order->id }}</td>
                            <td class="px-4">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4">{{ $order->user->name }}</td>
                            <td class="px-4">{{ $order->merchant->shop_name }}</td>
                            <td class="px-4">Rp {{ number_format($order->total_price) }}</td>
                            <td class="px-4 text-center">
                                <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">Tidak ada data transaksi pada periode
                                ini.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-light">
                    <tr>
                        <td colspan="4" class="px-4 py-3 text-end fw-bold">TOTAL OMZET PERIODE INI:</td>
                        <td colspan="2" class="px-4 py-3 fw-bold text-success fs-5">Rp {{ number_format($totalOmzet) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection
