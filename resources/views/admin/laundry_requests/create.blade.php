@extends('layouts.admin')

@section('title', 'Buat Request Laundry')

@section('content')
<div class="page-title mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h3>Buat Permintaan Laundry</h3>
            <p class="text-subtitle text-muted">Input permintaan layanan laundry untuk tamu.</p>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('admin.laundry-requests.index') }}" class="btn btn-secondary">
                <i data-feather="arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

<section class="section">
    <div class="card">
        <div class="card-content">
            <div class="card-body">
                <form action="{{ route('admin.laundry-requests.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 col-12 mb-3">
                            <div class="form-group">
                                <label for="reservation_id">Pilih Reservasi (Tamu Check-In)</label>
                                <select id="reservation_id" class="form-control @error('reservation_id') is-invalid @enderror" name="reservation_id" required>
                                    <option value="">-- Pilih Tamu --</option>
                                    @foreach($reservations as $res)
                                        <option value="{{ $res->id }}" {{ old('reservation_id') == $res->id ? 'selected' : '' }}>
                                            {{ $res->booking_number }} - {{ $res->guest->first_name }} {{ $res->guest->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('reservation_id') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="laundry_service_id">Pilih Layanan</label>
                                <select id="laundry_service_id" class="form-control @error('laundry_service_id') is-invalid @enderror" name="laundry_service_id" required>
                                    <option value="">-- Pilih Layanan Laundry --</option>
                                    @foreach($services as $service)
                                        <option value="{{ $service->id }}" {{ old('laundry_service_id') == $service->id ? 'selected' : '' }}>
                                            {{ $service->name }} (Rp {{ number_format($service->price, 0, ',', '.') }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('laundry_service_id') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="quantity">Kuantitas (Misal: per potong atau per kg)</label>
                                <input type="number" id="quantity" class="form-control @error('quantity') is-invalid @enderror" name="quantity" min="1" value="{{ old('quantity', 1) }}" required>
                                @error('quantity') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="col-md-12 col-12">
                            <div class="form-group">
                                <label for="notes">Catatan Tambahan (Opsional)</label>
                                <textarea id="notes" class="form-control @error('notes') is-invalid @enderror" name="notes" rows="3">{{ old('notes') }}</textarea>
                                @error('notes') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="col-12 d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary mr-1 mb-1">Simpan Request</button>
                        </div>
                    </div>
                </form>
                <p class="text-muted mt-2"><small>* Tagihan layanan ini akan ditambahkan ke invoice akhir reservasi (Extra Charges).</small></p>
            </div>
        </div>
    </div>
</section>
@endsection
