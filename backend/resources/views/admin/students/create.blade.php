@extends('layouts.admin')

@section('title', 'Tambah User Baru')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <form action="{{ route('admin.students.store') }}" method="POST">
                        @csrf

                        <h5 class="mb-4 text-utb-blue fw-bold">Form Data User</h5>

                        <div class="mb-3">
                            <label class="form-label">Status Pengguna <span class="text-danger">*</span></label>
                            <select name="role" class="form-select" required>
                                <option value="" disabled selected>-- Pilih Status --</option>
                                <option value="student">Mahasiswa</option>
                                <option value="lecturer">Dosen</option>
                                <option value="staff">Staf / Karyawan</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="Nama Mahasiswa" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nomor Induk (NIM / NIDN / NIP)</label>
                            <input type="text" name="nim" class="form-control"
                                placeholder="Contoh: 2355... (Mahasiswa) atau 0411... (Dosen)">
                            <div class="form-text small text-muted">Opsional. Masukkan NIM untuk Mahasiswa, atau NIDN/NIP
                                untuk Dosen/Staf.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email Login <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" placeholder="email@utb.ac.id"
                                required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">No. HP / WhatsApp <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control" placeholder="08xxxxxxxx" required>
                        </div>

                        <div class="alert alert-info border small">
                            <i class="bi bi-info-circle me-1"></i> Password default akan diatur menjadi:
                            <strong>password123</strong>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.students') }}" class="btn btn-light border px-4">Batal</a>
                            <button type="submit" class="btn btn-success px-4">Simpan Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
