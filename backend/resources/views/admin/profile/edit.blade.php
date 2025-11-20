@extends('layouts.admin')

@section('title', 'Pengaturan Akun')

@section('content')
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 text-center p-4">
                <div class="mb-3 position-relative d-inline-block">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=305089&color=fff&size=128"
                        class="rounded-circle shadow-sm" width="120" height="120" alt="Avatar">
                    <span class="position-absolute bottom-0 end-0 bg-success border border-light rounded-circle p-2">
                        <span class="visually-hidden">Active</span>
                    </span>
                </div>
                <h5 class="fw-bold text-utb-blue mb-1">{{ $user->name }}</h5>
                <p class="text-muted mb-3">{{ $user->email }}</p>

                <div class="d-flex justify-content-center gap-2 mb-3">
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>

                <div class="border-top pt-3 text-start">
                    <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Info Kontak</small>
                    <div class="mt-2 d-flex align-items-center text-muted">
                        <i class="bi bi-telephone me-3"></i> {{ $user->phone ?? 'Belum diatur' }}
                    </div>
                    <div class="mt-2 d-flex align-items-center text-muted">
                        <i class="bi bi-calendar-check me-3"></i> Member sejak {{ $user->created_at->format('M Y') }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-utb-blue"><i class="bi bi-person-gear me-2"></i> Edit Informasi Profil</h6>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control"
                                    value="{{ old('name', $user->name) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control"
                                    value="{{ old('email', $user->email) }}" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Nomor Handphone</label>
                            <input type="text" name="phone" class="form-control"
                                value="{{ old('phone', $user->phone) }}" placeholder="08xxxxxxxx">
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-utb px-4">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h6 class="m-0 fw-bold text-danger"><i class="bi bi-shield-lock me-2"></i> Ganti Password</h6>
                </div>
                <div class="card-body p-4 pt-0">
                    <form action="{{ route('profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Password Saat Ini</label>
                            <input type="password" name="current_password"
                                class="form-control @error('current_password') is-invalid @enderror" required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Password Baru</label>
                                <input type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Konfirmasi Password Baru</label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-light text-danger border-danger border-opacity-25">Update
                                Password</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
