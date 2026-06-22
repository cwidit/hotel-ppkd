@extends('layouts.admin')

@section('title', 'Buku Tamu')

@section('content')
<div class="page-title mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h3>Buku Tamu</h3>
            <p class="text-subtitle text-muted">Kelola data tamu hotel.</p>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('admin.guests.create') }}" class="btn btn-primary">
                <i data-feather="plus" style="width:15px;height:15px;"></i> Tambah Tamu
            </a>
        </div>
    </div>
</div>

<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Daftar Tamu</span>
            <small class="text-muted">Total: {{ $guests->count() }} tamu</small>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="table1">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>No. Identitas</th>
                            <th>Nama Lengkap</th>
                            <th>No. HP</th>
                            <th>Email</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($guests as $i => $guest)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td><small class="text-muted">{{ $guest->identity_number }}</small></td>
                            <td><strong>{{ $guest->first_name }} {{ $guest->last_name }}</strong></td>
                            <td>{{ $guest->phone ?? '-' }}</td>
                            <td>{{ $guest->email ?? '-' }}</td>
                            <td>
                                <a href="{{ route('admin.guests.edit', $guest->id) }}" class="btn btn-sm btn-info">Edit</a>
                                <button class="btn btn-sm btn-danger btn-delete"
                                    data-action="{{ route('admin.guests.destroy', $guest->id) }}"
                                    data-label="{{ $guest->first_name }} {{ $guest->last_name }}">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i data-feather="users" style="width:32px;height:32px;opacity:.3;"></i>
                                <p class="mt-2 mb-0">Belum ada data tamu.</p>
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
