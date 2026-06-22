@extends('layouts.admin')

@section('title', 'Users & Roles')

@section('content')
<div class="page-title mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h3>Users & Roles</h3>
            <p class="text-subtitle text-muted">Kelola akun pengguna dan role-nya.</p>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <i data-feather="plus" style="width:15px;height:15px;"></i> Tambah User
            </a>
        </div>
    </div>
</div>

<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Daftar Pengguna</span>
            <small class="text-muted">Total: {{ $users->count() }} user</small>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="table1">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $i => $user)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>
                                <strong>{{ $user->name }}</strong>
                                @if($user->id === auth()->id())
                                    <span class="badge badge-light ml-1" style="font-size:9px;">Anda</span>
                                @endif
                            </td>
                            <td><small class="text-muted">{{ $user->email }}</small></td>
                            <td>
                                @foreach($user->roles as $role)
                                    @php
                                        $rc = match($role->name) {
                                            'Administrator'  => 'badge-danger',
                                            'Front Office'   => 'badge-primary',
                                            'Housekeeping'   => 'badge-info',
                                            'Food & Beverage'=> 'badge-warning',
                                            default          => 'badge-secondary',
                                        };
                                    @endphp
                                    <span class="badge {{ $rc }}">{{ $role->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-info">Edit</a>
                                @if($user->id !== auth()->id())
                                <button class="btn btn-sm btn-danger btn-delete"
                                    data-action="{{ route('admin.users.destroy', $user->id) }}"
                                    data-label="{{ $user->name }}">
                                    Hapus
                                </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Belum ada data user.</td>
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
