@extends('layouts.admin')

@section('title', 'Layanan Laundry')

@section('content')
<div class="page-title mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h3>Layanan Laundry</h3>
            <p class="text-subtitle text-muted">Kelola data jenis layanan laundry dan harganya.</p>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('admin.laundry-services.create') }}" class="btn btn-primary">
                <i data-feather="plus" style="width:15px;height:15px;"></i> Tambah Layanan
            </a>
        </div>
    </div>
</div>

<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Daftar Layanan</span>
            <small class="text-muted">Total: {{ $services->count() }} layanan</small>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="table1">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Layanan</th>
                            <th>Harga / Item</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($services as $i => $service)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td><strong>{{ $service->name }}</strong></td>
                            <td>Rp {{ number_format($service->price, 0, ',', '.') }}</td>
                            <td>
                                <a href="{{ route('admin.laundry-services.edit', $service->id) }}" class="btn btn-sm btn-info">Edit</a>
                                <button class="btn btn-sm btn-danger btn-delete"
                                    data-action="{{ route('admin.laundry-services.destroy', $service->id) }}"
                                    data-label="{{ $service->name }}">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">
                                <i data-feather="wind" style="width:32px;height:32px;opacity:.3;"></i>
                                <p class="mt-2 mb-0">Belum ada data layanan laundry.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

@include('components.delete-modal')
@endsection
