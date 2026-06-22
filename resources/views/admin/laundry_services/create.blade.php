@extends('layouts.admin')

@section('title', 'Tambah Layanan Laundry')

@section('content')
<div class="page-title mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h3>Tambah Layanan Laundry</h3>
            <p class="text-subtitle text-muted">Tambahkan data jenis layanan laundry baru.</p>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('admin.laundry-services.index') }}" class="btn btn-secondary">
                <i data-feather="arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

<section class="section">
    <div class="card">
        <div class="card-content">
            <div class="card-body">
                <form action="{{ route('admin.laundry-services.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="name">Nama Layanan</label>
                                <input type="text" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Contoh: Cuci Kering (Per Kg)" name="name" value="{{ old('name') }}" required>
                                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="price">Harga (Rp)</label>
                                <input type="number" id="price" class="form-control @error('price') is-invalid @enderror" placeholder="Contoh: 15000" name="price" value="{{ old('price') }}" required>
                                @error('price') <span class="text-danger">{{ $message }}</span> @enderror
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
