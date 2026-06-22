@extends('layouts.admin')

@section('title', 'Pengaturan Hotel')

@section('content')
<div class="page-title mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h3>Pengaturan Hotel</h3>
            <p class="text-subtitle text-muted">Kelola identitas hotel dan persentase pajak.</p>
        </div>
    </div>
</div>

<section class="section">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                @method('PUT')
                
                <h5 class="mb-3">Informasi Umum</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Nama Hotel</label>
                        <input type="text" name="hotel_name" class="form-control" value="{{ $settings['hotel_name'] ?? 'PPKD HOTEL' }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Email Hotel</label>
                        <input type="email" name="hotel_email" class="form-control" value="{{ $settings['hotel_email'] ?? 'info@ppkdhotel.com' }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Telepon Hotel</label>
                        <input type="text" name="hotel_phone" class="form-control" value="{{ $settings['hotel_phone'] ?? '(021) 1234567' }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Alamat Hotel</label>
                        <textarea name="hotel_address" class="form-control" rows="2">{{ $settings['hotel_address'] ?? 'Jl. Raya PPKD No. 1, Jakarta' }}</textarea>
                    </div>
                </div>

                <hr>
                <h5 class="mb-3">Pajak & Operasional</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Persentase Pajak Hotel (%)</label>
                        <input type="number" step="0.01" name="tax_percentage" class="form-control" value="{{ $settings['tax_percentage'] ?? '10' }}" required>
                        <small class="text-muted">Misal: 10 untuk 10%</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Persentase Service Charge (%)</label>
                        <input type="number" step="0.01" name="service_charge_percentage" class="form-control" value="{{ $settings['service_charge_percentage'] ?? '5' }}" required>
                        <small class="text-muted">Misal: 5 untuk 5%</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Waktu Check-In Standar</label>
                        <input type="time" name="check_in_time" class="form-control" value="{{ $settings['check_in_time'] ?? '14:00' }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Waktu Check-Out Standar</label>
                        <input type="time" name="check_out_time" class="form-control" value="{{ $settings['check_out_time'] ?? '12:00' }}">
                    </div>
                </div>

                <div class="text-right mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i data-feather="save"></i> Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
