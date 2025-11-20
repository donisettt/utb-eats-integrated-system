@extends('layouts.admin')

@section('title', 'Tambah Menu Baru')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <h5 class="mb-4 text-utb-blue fw-bold">Detail Menu</h5>

                        <div class="mb-3">
                            <label class="form-label">Pilih Warung (Mitra) <span class="text-danger">*</span></label>
                            <select name="merchant_id" class="form-select" required>
                                <option value="" disabled selected>-- Pilih Mitra --</option>
                                @foreach ($merchants as $merchant)
                                    <option value="{{ $merchant->id }}">{{ $merchant->shop_name }}
                                        ({{ $merchant->user->name }})</option>
                                @endforeach
                            </select>
                            <div class="form-text">Menu ini akan dimasukkan ke warung yang dipilih.</div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Menu <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" placeholder="Cth: Nasi Goreng"
                                    required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kategori <span class="text-danger">*</span></label>
                                <select name="category_id" class="form-select" required>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="price" class="form-control" placeholder="15000" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Penjelasan singkat menu..."></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Foto Menu</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.products') }}" class="btn btn-light border px-4">Batal</a>
                            <button type="submit" class="btn btn-utb px-4">Simpan Menu</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
