@extends('layouts.admin')

@section('title', 'Manajemen Menu Makanan')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="text-muted">Bantu kelola menu jualan para mitra kantin.</div>
        <a href="{{ route('admin.products.create') }}" class="btn btn-utb shadow-sm rounded-pill px-4">
            <i class="bi bi-plus-lg me-2"></i> Tambah Menu
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-secondary">
                    <tr>
                        <th class="px-4 py-3">Menu</th>
                        <th class="px-4 py-3">Warung (Pemilik)</th>
                        <th class="px-4 py-3">Kategori</th>
                        <th class="px-4 py-3">Harga</th>
                        <th class="px-4 py-3 text-center">Stok</th>
                        <th class="px-4 py-3 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td class="px-4">
                                <div class="d-flex align-items-center">
                                    <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/50' }}"
                                        class="rounded-3 object-fit-cover me-3 border" width="50" height="50">
                                    <div class="fw-bold text-dark">{{ $product->name }}</div>
                                </div>
                            </td>
                            <td class="px-4">
                                <div class="small fw-bold text-primary">{{ $product->merchant->shop_name }}</div>
                            </td>
                            <td class="px-4">
                                <span class="badge bg-light text-secondary border">{{ $product->category->name }}</span>
                            </td>
                            <td class="px-4 fw-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                            <td class="px-4 text-center">
                                @if ($product->is_available)
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill">Ready</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill">Habis</span>
                                @endif
                            </td>
                            <td class="px-4 text-end">
                                <a href="{{ route('admin.products.edit', $product->id) }}"
                                    class="btn btn-sm btn-light text-primary border"><i class="bi bi-pencil-square"></i></a>
                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST"
                                    class="d-inline" onsubmit="return confirm('Hapus menu ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-light text-danger border ms-1"><i
                                            class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">Belum ada menu.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
