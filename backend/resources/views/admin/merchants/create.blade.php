@extends('layouts.admin')

@section('title', 'Tambah Mitra Baru')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <form action="{{ route('admin.merchants.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body p-4">

                        <h5 class="mb-4 text-utb-blue fw-bold border-bottom pb-2">1. Informasi Akun Pemilik</h5>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Nama Pemilik <span
                                        class="text-danger">*</span></label>
                                <input type="text" id="name" name="name" class="form-control"
                                    placeholder="Contoh: Pak Budi" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email Login <span
                                        class="text-danger">*</span></label>
                                <input type="email" id="email" name="email" class="form-control"
                                    placeholder="email@utb.ac.id" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="phone" class="form-label">Nomor HP <span class="text-danger">*</span></label>
                            <input type="text" id="phone" name="phone" class="form-control" placeholder="0812..."
                                value="{{ old('phone') }}" required>
                            @error('phone')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Password default akun adalah <strong>password123</strong></div>
                        </div>

                        <hr class="my-4 border-t border-gray-200">

                        <h5 class="mb-4 text-utb-blue fw-bold border-bottom pb-2">2. Informasi Warung & Foto</h5>

                        <div class="mb-3">
                            <label for="shop_name" class="form-label">Nama Warung <span class="text-danger">*</span></label>
                            <input type="text" id="shop_name" name="shop_name" class="form-control"
                                placeholder="Contoh: Kantin Barokah Bu Siti" value="{{ old('shop_name') }}" required>
                            @error('shop_name')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="image" class="form-label">Foto Warung (Spanduk)</label>
                            <input type="file" id="image" name="image" class="form-control" accept="image/*">
                            <div class="form-text">Format: JPG, PNG. Max: 2MB.</div>
                            @error('image')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('admin.merchants') }}" class="btn btn-light border px-4">
                                Batal
                            </a>
                            <button type="submit" class="btn btn-utb px-4 shadow-sm">
                                <i class="bi bi-save me-2"></i>
                                Simpan Mitra
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
