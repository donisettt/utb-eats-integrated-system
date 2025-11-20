@extends('layouts.admin')

@section('title', 'Laporan Iuran Mitra')

@section('content')
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <form action="{{ route('admin.reports.fees') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-bold small text-muted">Dari Tanggal</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold small text-muted">Sampai Tanggal</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold small text-muted">Status Pembayaran</label>
                    <select name="status" class="form-select">
                        <option value="all" {{ $status == 'all' ? 'selected' : '' }}>Semua Status</option>
                        <option value="paid" {{ $status == 'paid' ? 'selected' : '' }}>Lunas (Paid)</option>
                        <option value="unpaid" {{ $status == 'unpaid' ? 'selected' : '' }}>Belum Bayar</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-filter"></i> Filter</button>

                    <a href="{{ route('admin.reports.fees.print', ['start_date' => $startDate, 'end_date' => $endDate, 'status' => $status]) }}"
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
                Rekap Iuran: <span class="text-muted fw-normal">{{ date('d M Y', strtotime($startDate)) }} -
                    {{ date('d M Y', strtotime($endDate)) }}</span>
            </h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3">Tanggal Buat</th>
                        <th class="px-4 py-3">Mitra Warung</th>
                        <th class="px-4 py-3">Periode Tagihan</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3">Nominal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($fees as $fee)
                        <tr>
                            <td class="px-4">{{ $fee->created_at->format('d/m/Y') }}</td>
                            <td class="px-4">
                                <div class="fw-bold">{{ $fee->merchant->shop_name }}</div>
                                <small class="text-muted">{{ $fee->merchant->user->name }}</small>
                            </td>
                            <td class="px-4">
                                {{ DateTime::createFromFormat('!m', $fee->month)->format('F') }} {{ $fee->year }}
                            </td>
                            <td class="px-4 text-center">
                                @if ($fee->status == 'paid')
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">Lunas</span>
                                    <div class="text-xs text-muted mt-1">Tgl: {{ date('d/m/y', strtotime($fee->paid_at)) }}
                                    </div>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3">Belum
                                        Bayar</span>
                                @endif
                            </td>
                            <td class="px-4">Rp {{ number_format($fee->amount) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">Tidak ada data tagihan pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-light">
                    <tr>
                        <td colspan="4" class="px-4 py-3 text-end fw-bold">TOTAL PEMASUKAN (LUNAS):</td>
                        <td class="px-4 py-3 fw-bold text-success fs-5">Rp {{ number_format($totalPemasukan) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection
