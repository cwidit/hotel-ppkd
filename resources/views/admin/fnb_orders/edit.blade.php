@extends('layouts.admin')

@section('title', 'Update Pesanan FnB')

@section('content')
<div class="page-title mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h3>Update Status Pesanan #{{ $fnbOrder->id }}</h3>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('admin.fnb-orders.index') }}" class="btn btn-secondary">
                <i data-feather="arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

<section class="section">
    <div class="card">
        <div class="card-content">
            <div class="card-body">
                <form action="{{ route('admin.fnb-orders.update', $fnbOrder->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="status">Status Pesanan</label>
                                <select id="status" class="form-control @error('status') is-invalid @enderror" name="status" required>
                                    <option value="Pending" {{ old('status', $fnbOrder->status) == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="Processing" {{ old('status', $fnbOrder->status) == 'Processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="Delivered" {{ old('status', $fnbOrder->status) == 'Delivered' ? 'selected' : '' }}>Delivered (Sudah Diantar)</option>
                                    <option value="Canceled" {{ old('status', $fnbOrder->status) == 'Canceled' ? 'selected' : '' }}>Canceled (Dibatalkan)</option>
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
