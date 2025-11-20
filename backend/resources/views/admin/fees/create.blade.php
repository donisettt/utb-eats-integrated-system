@extends('layouts.admin')

@section('title', 'Buat Tagihan Iuran')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <form action="{{ route('admin.fees.store') }}" method="POST">
                        @csrf

                        <h5 class="mb-4 text-utb-blue fw-bold">Form Tagihan Baru</h5>

                        <div class="mb-3">
                            <label class="form-label">Target Tagihan</label>
                            <select name="merchant_id" class="form-select" required>
                                <option value="all" selected class="fw-bold">📢 Semua Mitra (Bulk Generate)</option>
                                <option disabled>--------------------------------</option>
                                @foreach ($merchants as $merchant)
                                    <option value="{{ $merchant->id }}">{{ $merchant->shop_name }}
                                        ({{ $merchant->user->name }})</option>
                                @endforeach
                            </select>
                            <div class="form-text text-primary"><i class="bi bi-info-circle"></i> Pilih "Semua Mitra" untuk
                                membuat tagihan massal sekaligus.</div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Bulan</label>
                                <select name="month" class="form-select" required>
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ date('n') == $i ? 'selected' : '' }}>
                                            {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tahun</label>
                                <input type="number" name="year" class="form-control" value="{{ date('Y') }}"
                                    required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Nominal Tagihan (Rp)</label>
                            <input type="number" name="amount" class="form-control fw-bold text-success"
                                placeholder="Contoh: 150000" required>
                            <div class="form-text">Masukkan angka saja tanpa titik/koma.</div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.fees') }}" class="btn btn-light border px-4">Batal</a>
                            <button type="submit" class="btn btn-utb px-4">
                                <i class="bi bi-send-check me-2"></i> Generate Tagihan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
