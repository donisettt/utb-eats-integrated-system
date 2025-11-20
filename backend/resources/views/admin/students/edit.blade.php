@extends('layouts.admin')

@section('title', 'Edit Data Mahasiswa')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <form action="{{ route('admin.students.update', $student->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <h5 class="mb-4 text-utb-blue fw-bold">Informasi Akun</h5>

                        <div class="mb-3">
                            <label class="form-label">Status Pengguna</label>
                            <select name="role" class="form-select" required>
                                <option value="student" {{ $student->role == 'student' ? 'selected' : '' }}>Mahasiswa
                                </option>
                                <option value="lecturer" {{ $student->role == 'lecturer' ? 'selected' : '' }}>Dosen</option>
                                <option value="staff" {{ $student->role == 'staff' ? 'selected' : '' }}>Staf / Karyawan
                                </option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control"
                                value="{{ old('name', $student->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">NIM (Nomor Induk Mahasiswa)</label>
                            <input type="text" name="nim" class="form-control"
                                value="{{ old('nim', $student->nim) }}" placeholder="Cth: 23552011146">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control"
                                value="{{ old('email', $student->email) }}" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">No. HP / WhatsApp</label>
                            <input type="text" name="phone" class="form-control"
                                value="{{ old('phone', $student->phone) }}" required>
                        </div>

                        <div class="alert alert-light border mb-4">
                            <label class="form-label fw-bold text-danger small text-uppercase mb-1">Reset Password</label>
                            <input type="password" name="password" class="form-control form-control-sm"
                                placeholder="Isi password baru jika ingin mereset">
                            <small class="text-muted fst-italic">Kosongkan jika tidak ingin mengganti password
                                mahasiswa.</small>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.students') }}" class="btn btn-light border px-4">Batal</a>
                            <button type="submit" class="btn btn-primary px-4">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
