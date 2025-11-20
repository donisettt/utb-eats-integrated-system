@extends('layouts.admin')

@section('title', 'Upload Banner Baru')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <h5 class="mb-4 text-utb-blue fw-bold">Upload Banner Promo/Info</h5>

                        <div class="mb-3">
                            <label class="form-label">Judul Banner (Opsional)</label>
                            <input type="text" name="title" class="form-control"
                                placeholder="Contoh: Diskon Hari Kemerdekaan">
                        </div>

                        <div class="mb-4">
                            <label class="form-label">File Gambar <span class="text-danger">*</span></label>
                            <input type="file" name="image" class="form-control" accept="image/*" required>
                            <div class="form-text">Format: JPG, PNG. Max: 2MB. Rasio disarankan 16:9 (Landscape).</div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.banners') }}" class="btn btn-light border px-4">Batal</a>
                            <button type="submit" class="btn btn-utb px-4">
                                <i class="bi bi-upload me-2"></i> Upload Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
