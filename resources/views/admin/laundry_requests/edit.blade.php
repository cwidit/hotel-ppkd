@extends('layouts.admin')

@section('title', 'Update Laundry Request')

@section('content')
<div class="page-title mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h3>Update Status Laundry #{{ $laundryRequest->id }}</h3>
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
                <form action="{{ route('admin.laundry-requests.update', $laundryRequest->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="status">Status Pengerjaan</label>
                                <select id="status" class="form-control @error('status') is-invalid @enderror" name="status" required>
                                    <option value="Pending" {{ old('status', $laundryRequest->status) == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="Processing" {{ old('status', $laundryRequest->status) == 'Processing' ? 'selected' : '' }}>Processing (Sedang Dicuci)</option>
                                    <option value="Completed" {{ old('status', $laundryRequest->status) == 'Completed' ? 'selected' : '' }}>Completed (Selesai & Dikembalikan)</option>
                                    <option value="Canceled" {{ old('status', $laundryRequest->status) == 'Canceled' ? 'selected' : '' }}>Canceled (Dibatalkan)</option>
                                </select>
                                @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="col-12 d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary mr-1 mb-1">Simpan Perubahan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
