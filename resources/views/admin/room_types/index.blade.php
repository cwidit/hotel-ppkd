@extends('layouts.admin')

@section('title', 'Tipe Kamar')

@section('content')
<div class="page-title mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h3>Tipe Kamar</h3>
            <p class="text-subtitle text-muted">Kelola data master tipe kamar hotel.</p>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('admin.room-types.create') }}" class="btn btn-primary">
                <i data-feather="plus" style="width:15px;height:15px;"></i> Tambah Tipe Kamar
            </a>
        </div>
    </div>
</div>

<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Daftar Tipe Kamar</span>
            <small class="text-muted">Total: {{ $roomTypes->count() }} tipe</small>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="table1">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Kapasitas</th>
                            <th>Harga / Malam</th>
                            <th>Sarapan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roomTypes as $i => $type)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td><strong>{{ $type->name }}</strong></td>
                            <td>{{ $type->capacity }} Orang</td>
                            <td>Rp {{ number_format($type->price_per_night, 0, ',', '.') }}</td>
                            <td>
                                @if($type->has_breakfast)
                                    <span class="badge badge-success">Termasuk</span>
                                @else
                                    <span class="badge badge-secondary">Tidak</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.room-types.edit', $type->id) }}" class="btn btn-sm btn-info">Edit</a>
                                <button class="btn btn-sm btn-danger btn-delete"
                                    data-action="{{ route('admin.room-types.destroy', $type->id) }}"
                                    data-label="Tipe {{ $type->name }}">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i data-feather="home" style="width:32px;height:32px;opacity:.3;"></i>
                                <p class="mt-2 mb-0">Belum ada data tipe kamar.</p>
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
