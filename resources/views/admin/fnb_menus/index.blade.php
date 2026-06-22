@extends('layouts.admin')

@section('title', 'Menu FnB')

@section('content')
<div class="page-title mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h3>Menu Food & Beverage</h3>
            <p class="text-subtitle text-muted">Kelola data menu makanan dan minuman.</p>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('admin.fnb-menus.create') }}" class="btn btn-primary">
                <i data-feather="plus" style="width:15px;height:15px;"></i> Tambah Menu
            </a>
        </div>
    </div>
</div>

<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Daftar Menu</span>
            <small class="text-muted">Total: {{ $menus->count() }} item</small>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="table1">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Menu</th>
                            <th>Harga</th>
                            <th>Ketersediaan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($menus as $i => $menu)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td><strong>{{ $menu->name }}</strong></td>
                            <td>Rp {{ number_format($menu->price, 0, ',', '.') }}</td>
                            <td>
                                @if($menu->status === 'Available')
                                    <span class="badge badge-success">Available</span>
                                @else
                                    <span class="badge badge-danger">Unavailable</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.fnb-menus.edit', $menu->id) }}" class="btn btn-sm btn-info">Edit</a>
                                <button class="btn btn-sm btn-danger btn-delete"
                                    data-action="{{ route('admin.fnb-menus.destroy', $menu->id) }}"
                                    data-label="{{ $menu->name }}">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <i data-feather="coffee" style="width:32px;height:32px;opacity:.3;"></i>
                                <p class="mt-2 mb-0">Belum ada data menu.</p>
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
