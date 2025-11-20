@extends('layouts.admin')

@section('title', 'Manajemen Banner Aplikasi')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="text-muted">Gambar ini akan muncul di slider halaman utama aplikasi mobile.</div>
        <a href="{{ route('admin.banners.create') }}" class="btn btn-utb shadow-sm rounded-pill px-4">
            <i class="bi bi-plus-circle me-2"></i> Tambah Banner
        </a>
    </div>

    <div class="row g-4">
        @forelse($banners as $banner)
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 overflow-hidden rounded-4 group-hover">
                    <div style="height: 180px; overflow: hidden; position: relative;">
                        <img src="{{ asset('storage/' . $banner->image_path) }}" class="w-100 h-100 object-fit-cover"
                            alt="Banner">

                        <div class="position-absolute top-0 end-0 m-2">
                            @if ($banner->is_active)
                                <span class="badge bg-success bg-opacity-75 backdrop-blur">Aktif</span>
                            @else
                                <span class="badge bg-secondary bg-opacity-75 backdrop-blur">Non-Aktif</span>
                            @endif
                        </div>
                    </div>

                    <div class="card-body">
                        <h6 class="fw-bold text-dark mb-1">{{ $banner->title ?? 'Tanpa Judul' }}</h6>
                        <small class="text-muted">Diupload: {{ $banner->created_at->diffForHumans() }}</small>
                    </div>

                    <div class="card-footer bg-white border-top-0 d-flex justify-content-between pb-3">
                        <form action="{{ route('admin.banners.toggle', $banner->id) }}" method="POST">
                            @csrf
                            <button
                                class="btn btn-sm {{ $banner->is_active ? 'btn-outline-secondary' : 'btn-outline-success' }} rounded-pill px-3">
                                {{ $banner->is_active ? 'Sembunyikan' : 'Tampilkan' }}
                            </button>
                        </form>

                        <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST"
                            class="form-confirm-delete">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-light text-danger rounded-circle" title="Hapus">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5 text-muted">
                <i class="bi bi-images display-4 d-block mb-3 text-secondary opacity-25"></i>
                Belum ada banner yang diupload.
            </div>
        @endforelse
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteForms = document.querySelectorAll('.form-confirm-delete');
            deleteForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Hapus Banner?',
                        text: "Gambar akan dihapus permanen.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Hapus!'
                    }).then((result) => {
                        if (result.isConfirmed) form.submit();
                    });
                });
            });
        });
    </script>
@endsection
