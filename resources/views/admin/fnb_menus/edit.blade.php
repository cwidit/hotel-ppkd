@extends('layouts.admin')

@section('title', 'Edit Menu FnB')

@section('content')
<div class="page-title mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h3>Edit Menu FnB</h3>
            <p class="text-subtitle text-muted">Ubah data menu makanan dan minuman.</p>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('admin.fnb-menus.index') }}" class="btn btn-secondary">
                <i data-feather="arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

<section class="section">
    <div class="card">
        <div class="card-content">
            <div class="card-body">
                <form action="{{ route('admin.fnb-menus.update', $fnbMenu->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="name">Nama Menu</label>
                                <input type="text" id="name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $fnbMenu->name) }}" required>
                                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="price">Harga (Rp)</label>
                                <input type="number" id="price" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ old('price', floor($fnbMenu->price)) }}" required>
                                @error('price') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-12 col-12">
                            <div class="form-group">
                                <label for="status">Status Ketersediaan</label>
                                <select id="status" class="form-control @error('status') is-invalid @enderror" name="status" required>
                                    <option value="Available" {{ old('status', $fnbMenu->status) == 'Available' ? 'selected' : '' }}>Available</option>
                                    <option value="Unavailable" {{ old('status', $fnbMenu->status) == 'Unavailable' ? 'selected' : '' }}>Unavailable</option>
                                </select>
                                @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="description">Deskripsi</label>
                                <textarea id="description" class="form-control @error('description') is-invalid @enderror" name="description" rows="3">{{ old('description', $fnbMenu->description) }}</textarea>
                                @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-12 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary mr-1 mb-1">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
