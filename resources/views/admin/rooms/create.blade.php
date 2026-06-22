@extends('layouts.admin')

@section('title', 'Tambah Kamar')

@section('content')
<div class="page-title mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h3>Tambah Kamar</h3>
            <p class="text-subtitle text-muted">Tambahkan data kamar baru.</p>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('admin.rooms.index') }}" class="btn btn-secondary">
                <i data-feather="arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

<section class="section">
    <div class="card">
        <div class="card-content">
            <div class="card-body">
                <form action="{{ route('admin.rooms.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="room_number">Nomor Kamar</label>
                                <input type="text" id="room_number" class="form-control @error('room_number') is-invalid @enderror" placeholder="Contoh: 101" name="room_number" value="{{ old('room_number') }}" required>
                                @error('room_number') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="room_type_id">Tipe Kamar</label>
                                <select id="room_type_id" class="form-control @error('room_type_id') is-invalid @enderror" name="room_type_id" required>
                                    <option value="">-- Pilih Tipe Kamar --</option>
                                    @foreach($roomTypes as $type)
                                        <option value="{{ $type->id }}" {{ old('room_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }} (Rp {{ number_format($type->price_per_night, 0, ',', '.') }})</option>
                                    @endforeach
                                </select>
                                @error('room_type_id') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-12 col-12">
                            <div class="form-group">
                                <label for="status">Status Kamar</label>
                                <select id="status" class="form-control @error('status') is-invalid @enderror" name="status" required>
                                    <option value="">-- Pilih Status --</option>
                                    @foreach($statuses as $status)
                                        <option value="{{ $status }}" {{ old('status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                                    @endforeach
                                </select>
                                @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-12 d-flex justify-content-end mt-3">
                            <button type="submit" class="btn btn-primary mr-1 mb-1">Simpan</button>
                            <button type="reset" class="btn btn-light-secondary mr-1 mb-1">Reset</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
