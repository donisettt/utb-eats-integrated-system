@extends('layouts.admin')

@section('title', 'Edit Data Mitra')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body p-4">
                    <form action="{{ route('admin.merchants.update', $merchant->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <h5 class="mb-3 text-utb-blue fw-bold border-bottom pb-2">1. Informasi Pemilik</h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama Pemilik <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control"
                                    value="{{ old('name', $merchant->user->name) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email Login <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control"
                                    value="{{ old('email', $merchant->user->email) }}" required>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">No. Handphone <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control"
                                value="{{ old('phone', $merchant->user->phone) }}" required>
                        </div>

                        <div class="mb-4 bg-light p-3 rounded border">
                            <label class="form-label fw-bold text-muted small text-uppercase">Ubah Password
                                (Opsional)</label>
                            <input type="password" name="password" class="form-control form-control-sm"
                                placeholder="Isi hanya jika ingin mengganti password">
                            <div class="form-text small">Biarkan kosong jika password tidak ingin diubah.</div>
                        </div>

                        <h5 class="mb-3 text-utb-blue fw-bold border-bottom pb-2">2. Informasi Warung & Foto</h5>

                        <div class="mb-3">
                            <label class="form-label">Nama Warung <span class="text-danger">*</span></label>
                            <input type="text" name="shop_name" class="form-control"
                                value="{{ old('shop_name', $merchant->shop_name) }}" required>
                        </div>

                        @if ($merchant->image)
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Foto Saat Ini</label>
                                <img src="{{ asset('storage/' . $merchant->image) }}" alt="Foto Warung"
                                    class="img-fluid rounded-lg shadow-sm" style="max-height: 150px;">
                            </div>
                        @endif

                        <div class="mb-4">
                            <label class="form-label">Ganti Foto Warung</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <div class="form-text">Upload foto baru untuk mengganti yang lama. Max: 2MB.</div>
                            @error('image')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4 form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_open" id="isOpenSwitch" value="1"
                                {{ $merchant->is_open ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="isOpenSwitch">Status Warung: <span
                                    class="text-success">Buka (Aktif)</span></label>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.merchants') }}" class="btn btn-light border">Batal</a>
                            <button type="submit" class="btn btn-primary w-25">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
