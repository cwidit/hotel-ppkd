@extends('layouts.admin')

@section('title', 'Edit Tipe Kamar')

@section('content')
<div class="page-title mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h3>Edit Tipe Kamar</h3>
            <p class="text-subtitle text-muted">Ubah data tipe kamar.</p>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('admin.room-types.index') }}" class="btn btn-secondary">
                <i data-feather="arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

<section class="section">
    <div class="card">
        <div class="card-content">
            <div class="card-body">
                <form action="{{ route('admin.room-types.update', $roomType->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="name">Nama Tipe Kamar</label>
                                <input type="text" id="name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $roomType->name) }}" required>
                                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="capacity">Kapasitas (Orang)</label>
                                <input type="number" id="capacity" class="form-control @error('capacity') is-invalid @enderror" name="capacity" value="{{ old('capacity', $roomType->capacity) }}" required>
                                @error('capacity') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="price_per_night">Harga per Malam (Rp)</label>
                                <input type="number" id="price_per_night" class="form-control @error('price_per_night') is-invalid @enderror" name="price_per_night" value="{{ old('price_per_night', floor($roomType->price_per_night)) }}" required>
                                @error('price_per_night') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group mt-4">
                                <div class="checkbox">
                                    <input type="checkbox" id="has_breakfast" class="form-check-input" name="has_breakfast" value="1" {{ old('has_breakfast', $roomType->has_breakfast) ? 'checked' : '' }}>
                                    <label for="has_breakfast">Termasuk Breakfast?</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="description">Deskripsi & Fasilitas Utama</label>
                                <textarea id="description" class="form-control @error('description') is-invalid @enderror" name="description" rows="3">{{ old('description', $roomType->description) }}</textarea>
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
