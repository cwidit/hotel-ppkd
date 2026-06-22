@extends('layouts.admin')

@section('title', 'Permintaan Laundry')

@section('content')
<div class="page-title mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h3>Permintaan Laundry</h3>
            <p class="text-subtitle text-muted">Kelola layanan laundry untuk tamu.</p>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('admin.laundry-requests.create') }}" class="btn btn-primary">
                <i data-feather="plus" style="width:15px;height:15px;"></i> Buat Permintaan Laundry
            </a>
        </div>
    </div>
</div>

<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Daftar Permintaan</span>
            <small class="text-muted">Total: {{ $requests->count() }} permintaan</small>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="table1">
                    <thead>
                        <tr>
                            <th>Tgl Request</th>
                            <th>No. Reservasi</th>
                            <th>Tamu</th>
                            <th>Layanan</th>
                            <th>Qty</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $req)
                        <tr>
                            <td><small>{{ \Carbon\Carbon::parse($req->request_date)->format('d M Y H:i') }}</small></td>
                            <td>
                                <a href="{{ route('admin.reservations.show', $req->reservation_id) }}" class="text-primary">
                                    {{ $req->reservation->booking_number }}
                                </a>
                            </td>
                            <td>{{ $req->reservation->guest->first_name }} {{ $req->reservation->guest->last_name }}</td>
                            <td>{{ $req->laundryService->name ?? '-' }}</td>
                            <td>{{ $req->quantity }}</td>
                            <td>Rp {{ number_format($req->total_laundry_amount, 0, ',', '.') }}</td>
                            <td>
                                @php
                                    $sc = match($req->status) {
                                        'Pending'    => 'badge-warning',
                                        'Processing' => 'badge-info',
                                        'Completed'  => 'badge-success',
                                        'Canceled'   => 'badge-danger',
                                        default      => 'badge-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $sc }}">{{ $req->status }}</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.laundry-requests.edit', $req->id) }}" class="btn btn-sm btn-info">Update</a>
                                <button class="btn btn-sm btn-danger btn-delete"
                                    data-action="{{ route('admin.laundry-requests.destroy', $req->id) }}"
                                    data-label="Request Laundry #{{ $req->id }}">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i data-feather="wind" style="width:32px;height:32px;opacity:.3;"></i>
                                <p class="mt-2 mb-0">Belum ada permintaan laundry.</p>
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
