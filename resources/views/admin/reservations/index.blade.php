@extends('layouts.admin')

@section('title', 'Daftar Reservasi')

@section('content')
<div class="page-title mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h3>Reservasi & Registrasi Tamu</h3>
            <p class="text-subtitle text-muted">Kelola data pemesanan kamar hotel.</p>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('admin.reservations.create') }}" class="btn btn-primary">
                <i data-feather="plus" style="width:15px;height:15px;"></i> Tambah Reservasi
            </a>
        </div>
    </div>
</div>

<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Data Reservasi</span>
            <small class="text-muted">Total: {{ $reservations->count() }} data</small>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="table1">
                    <thead>
                        <tr>
                            <th>Booking No.</th>
                            <th>Tamu</th>
                            <th>Check-In</th>
                            <th>Check-Out</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Bayar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reservations as $res)
                        <tr>
                            <td>
                                <a href="{{ route('admin.reservations.show', $res->id) }}" class="font-weight-bold text-primary">
                                    {{ $res->booking_number }}
                                </a>
                            </td>
                            <td>{{ $res->guest->first_name }} {{ $res->guest->last_name }}</td>
                            <td>{{ \Carbon\Carbon::parse($res->check_in_date)->format('d M Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($res->check_out_date)->format('d M Y') }}</td>
                            <td>Rp {{ number_format($res->total_amount, 0, ',', '.') }}</td>
                            <td>
                                @php
                                    $sc = match($res->status) {
                                        'Confirmed'   => 'badge-primary',
                                        'Checked_In'  => 'badge-success',
                                        'Checked_Out' => 'badge-info',
                                        'Canceled'    => 'badge-danger',
                                        default       => 'badge-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $sc }}">{{ str_replace('_', ' ', $res->status) }}</span>
                            </td>
                            <td>
                                @php
                                    $pc = match($res->payment_status) {
                                        'Paid'         => 'badge-success',
                                        'Deposit_Paid' => 'badge-warning',
                                        'Partial'      => 'badge-info',
                                        default        => 'badge-danger',
                                    };
                                @endphp
                                <span class="badge {{ $pc }}">{{ str_replace('_', ' ', $res->payment_status) }}</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.reservations.show', $res->id) }}" class="btn btn-sm btn-primary">Detail</a>
                                <button class="btn btn-sm btn-danger btn-delete"
                                    data-action="{{ route('admin.reservations.destroy', $res->id) }}"
                                    data-label="Reservasi {{ $res->booking_number }}">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i data-feather="inbox" style="width:32px;height:32px;opacity:.3;"></i>
                                <p class="mt-2 mb-0">Belum ada data reservasi.</p>
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
