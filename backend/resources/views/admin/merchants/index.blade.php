@extends('layouts.admin')

@section('title', 'Manajemen Mitra Warung')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="text-muted">
            Kelola daftar kantin dan status operasional mitra UTB Eats.
        </div>
        <a href="{{ route('admin.merchants.create') }}" class="btn btn-utb shadow-sm px-4 rounded-pill">
            <i class="bi bi-plus-circle me-2"></i> Tambah Mitra Baru
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-secondary">
                    <tr>
                        <th class="py-3 px-4 border-0">Warung</th>
                        <th class="py-3 px-4 border-0">Pemilik & Kontak</th>
                        <th class="py-3 px-4 border-0 text-center">Status Toko</th>
                        <th class="py-3 px-4 border-0 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @forelse($merchants as $merchant)
                        <tr>
                            <td class="px-4">
                                <div class="d-flex align-items-center">
                                    <div class="me-3 flex-shrink-0">
                                        @if ($merchant->image)
                                            <img src="{{ asset('storage/' . $merchant->image) }}" alt="Foto"
                                                class="rounded-3 shadow-sm object-fit-cover"
                                                style="width: 60px; height: 60px;">
                                        @else
                                            <div class="bg-light rounded-3 d-flex align-items-center justify-content-center text-secondary"
                                                style="width: 60px; height: 60px;">
                                                <i class="bi bi-shop fs-4"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-bold text-utb-blue fs-5">{{ $merchant->shop_name }}</div>
                                        <small class="text-muted d-block">
                                            <i class="bi bi-geo-alt-fill me-1 text-danger"></i>
                                            {{ $merchant->address ?? 'Lokasi belum diatur' }}
                                        </small>
                                    </div>
                                </div>
                            </td>

                            <td class="px-4">
                                <div class="fw-semibold text-dark">{{ $merchant->user->name }}</div>
                                <div class="small text-muted">{{ $merchant->user->email }}</div>
                                <div class="small text-muted">
                                    <i class="bi bi-telephone me-1"></i> {{ $merchant->user->phone ?? '-' }}
                                </div>
                            </td>

                            <td class="px-4 text-center">
                                @if ($merchant->is_open)
                                    <span class="badge rounded-pill bg-success bg-opacity-10 text-success px-3 py-2">
                                        <i class="bi bi-shop me-1"></i> BUKA
                                    </span>
                                @else
                                    <span class="badge rounded-pill bg-danger bg-opacity-10 text-danger px-3 py-2">
                                        <i class="bi bi-shop-window me-1"></i> TUTUP
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 text-end">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.merchants.edit', $merchant->id) }}"
                                        class="btn btn-sm btn-light text-primary border" title="Edit Data">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('admin.merchants.destroy', $merchant->id) }}" method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus mitra {{ $merchant->shop_name }}? Data user dan menu juga akan terhapus permanen.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light text-danger border rounded-end"
                                            title="Hapus Mitra">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <img src="https://cdn-icons-png.flaticon.com/512/7486/7486747.png" alt="Empty"
                                    width="80" class="mb-3 opacity-50">
                                <h5 class="text-muted">Belum ada mitra terdaftar</h5>
                                <p class="small text-muted">Silakan tambahkan mitra warung baru untuk memulai.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer bg-white border-0 py-3">
            <div class="small text-muted text-center">
                Menampilkan seluruh data mitra aktif.
            </div>
        </div>
    </div>
@endsection
