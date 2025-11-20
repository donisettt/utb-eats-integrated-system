@extends('layouts.admin')

@section('title', 'Edit Menu')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <form action="{{ route('admin.products.update', $product->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf @method('PUT')

                        <h5 class="mb-4 text-utb-blue fw-bold">Edit Menu</h5>

                        <div class="mb-3">
                            <label class="form-label">Milik Warung</label>
                            <select name="merchant_id" class="form-select">
                                @foreach ($merchants as $merchant)
                                    <option value="{{ $merchant->id }}"
                                        {{ $product->merchant_id == $merchant->id ? 'selected' : '' }}>
                                        {{ $merchant->shop_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Menu</label>
                                <input type="text" name="name" class="form-control" value="{{ $product->name }}"
                                    required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kategori</label>
                                <select name="category_id" class="form-select">
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}"
                                            {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Harga (Rp)</label>
                            <input type="number" name="price" class="form-control" value="{{ $product->price }}"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="description" class="form-control" rows="3">{{ $product->description }}</textarea>
                        </div>

                        @if ($product->image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $product->image) }}" width="100" class="rounded border">
                            </div>
                        @endif
                        <div class="mb-3">
                            <label class="form-label">Ganti Foto</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>

                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" name="is_available" id="avail"
                                {{ $product->is_available ? 'checked' : '' }}>
                            <label class="form-check-label" for="avail">Stok Tersedia (Tampilkan di Aplikasi)</label>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.products') }}" class="btn btn-light border px-4">Batal</a>
                            <button type="submit" class="btn btn-primary px-4">Update Menu</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
