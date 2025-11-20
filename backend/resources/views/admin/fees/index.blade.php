@extends('layouts.admin')

@section('title', 'Iuran Mitra Bulanan')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="text-muted">Pantau pembayaran sewa dan iuran kebersihan mitra.</div>
        <a href="{{ route('admin.fees.create') }}" class="btn btn-utb shadow-sm rounded-pill px-4">
            <i class="bi bi-receipt me-2"></i> Buat Tagihan Baru
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-secondary">
                    <tr>
                        <th class="py-3 px-4 border-0">Mitra Warung</th>
                        <th class="py-3 px-4 border-0">Periode</th>
                        <th class="py-3 px-4 border-0">Nominal</th>
                        <th class="py-3 px-4 border-0 text-center">Status</th>
                        <th class="py-3 px-4 border-0 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @forelse($fees as $fee)
                        <tr>
                            <td class="px-4">
                                <div class="fw-bold text-utb-blue">{{ $fee->merchant->shop_name }}</div>
                                <small class="text-muted">{{ $fee->merchant->user->name }}</small>
                            </td>
                            <td class="px-4">
                                {{ DateTime::createFromFormat('!m', $fee->month)->format('F') }} {{ $fee->year }}
                            </td>
                            <td class="px-4 fw-bold">
                                Rp {{ number_format($fee->amount, 0, ',', '.') }}
                            </td>
                            <td class="px-4 text-center">
                                @if ($fee->status == 'paid')
                                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                                        <i class="bi bi-check-circle-fill me-1"></i> LUNAS
                                    </span>
                                    <div class="text-xs text-muted mt-1">
                                        {{ $fee->paid_at ? date('d/m/y', strtotime($fee->paid_at)) : '' }}</div>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">
                                        BELUM BAYAR
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 text-end">
                                @if ($fee->status == 'unpaid')
                                    <form action="{{ route('admin.fees.pay', $fee->id) }}" method="POST"
                                        class="d-inline form-confirm-pay">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success rounded-pill px-3"
                                            title="Tandai Lunas">
                                            <i class="bi bi-cash-coin me-1"></i> Bayar
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-sm btn-light text-muted border rounded-pill px-3" disabled>
                                        <i class="bi bi-lock-fill"></i> Selesai
                                    </button>
                                @endif

                                <form action="{{ route('admin.fees.destroy', $fee->id) }}" method="POST"
                                    class="d-inline ms-1 form-confirm-delete">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light text-danger" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">Belum ada data tagihan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {
        
        // 1. Konfirmasi BAYAR
        const payForms = document.querySelectorAll('.form-confirm-pay');
        
        payForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                Swal.fire({
                    title: 'Konfirmasi Pembayaran',
                    text: "Apakah mitra ini benar-benar sudah membayar?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#67B342',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Verifikasi Lunas',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit(); // Baru kirim data kalau user klik Ya
                    }
                });
            });
        });

        // 2. Konfirmasi HAPUS
        const deleteForms = document.querySelectorAll('.form-confirm-delete');
        
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                Swal.fire({
                    title: 'Hapus Tagihan?',
                    text: "Data ini akan hilang permanen.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        });

    });
</script>
