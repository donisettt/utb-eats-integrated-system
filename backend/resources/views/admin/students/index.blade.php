@extends('layouts.admin')

@section('title', 'Data Pengguna')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="text-muted">
            Kelola data akun pengguna yang terdaftar di aplikasi.
        </div>
        <a href="{{ route('admin.students.create') }}" class="btn btn-primary shadow-sm rounded-pill px-4">
            <i class="bi bi-plus-circle me-2"></i> Tambah Pengguna
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-secondary">
                    <tr>
                        <th class="py-3 px-4 border-0">Nama Pengguna</th>
                        <th class="py-3 px-4 border-0">Kontak</th>
                        <th class="py-3 px-4 border-0">Terdaftar Pada</th>
                        <th class="py-3 px-4 border-0 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @forelse($students as $student)
                        <tr>
                            <td class="px-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-placeholder me-3 bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold"
                                        style="width: 40px; height: 40px;">
                                        {{ substr($student->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $student->name }}</div>

                                        <div class="d-flex gap-1 mt-1">
                                            @if ($student->role == 'student')
                                                <span
                                                    class="badge bg-blue-100 text-primary border border-primary border-opacity-10 text-xs">Mahasiswa</span>
                                            @elseif($student->role == 'lecturer')
                                                <span
                                                    class="badge bg-purple-100 text-purple-700 border border-purple-200 text-xs">Dosen</span>
                                            @else
                                                <span
                                                    class="badge bg-orange-100 text-orange-700 border border-orange-200 text-xs">Staf</span>
                                            @endif

                                            @if ($student->nim)
                                                <span
                                                    class="badge bg-light text-secondary border text-xs">{{ $student->nim }}</span>
                                            @endif
                                        </div>

                                    </div>
                                </div>
                            </td>
                            <td class="px-4">
                                <div class="text-dark">{{ $student->email }}</div>
                                <small class="text-muted">
                                    <i class="bi bi-telephone me-1"></i> {{ $student->phone ?? '-' }}
                                </small>
                            </td>
                            <td class="px-4">
                                <span class="badge bg-light text-dark border">
                                    {{ $student->created_at->format('d M Y') }}
                                </span>
                            </td>
                            <td class="px-4 text-end">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.students.edit', $student->id) }}"
                                        class="btn btn-sm btn-light text-primary border" title="Edit / Reset Password">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Hapus akun mahasiswa ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light text-danger border rounded-end"
                                            title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <div class="text-muted">Belum ada pengguna yang mendaftar.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white border-0 py-3">
            <div class="small text-muted text-center">Total {{ $students->count() }} Pengguna Terdaftar</div>
        </div>
    </div>
@endsection
