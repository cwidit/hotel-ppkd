@extends('layouts.admin')

@section('title', 'FnB Orders')

@section('content')
<div class="page-title mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h3>Pemesanan FnB (Room Service)</h3>
            <p class="text-subtitle text-muted">Kelola pesanan makanan dan minuman tamu.</p>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('admin.fnb-orders.create') }}" class="btn btn-primary">
                <i data-feather="plus" style="width:15px;height:15px;"></i> Buat Pesanan Baru
            </a>
        </div>
    </div>
</div>

<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Daftar Pesanan</span>
            <small class="text-muted">Total: {{ $orders->count() }} pesanan</small>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="table1">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>No. Reservasi</th>
                            <th>Tamu</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $i => $order)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>
                                <a href="{{ route('admin.reservations.show', $order->reservation_id) }}" class="text-primary">
                                    {{ $order->reservation->booking_number }}
                                </a>
                            </td>
                            <td>{{ $order->reservation->guest->first_name }} {{ $order->reservation->guest->last_name }}</td>
                            <td><small>{{ \Carbon\Carbon::parse($order->order_date)->format('d M Y H:i') }}</small></td>
                            <td>Rp {{ number_format($order->total_order_amount, 0, ',', '.') }}</td>
                            <td>
                                @php
                                    $sc = match($order->status) {
                                        'Pending'    => 'badge-warning',
                                        'Processing' => 'badge-info',
                                        'Delivered'  => 'badge-primary',
                                        'Completed'  => 'badge-success',
                                        'Canceled'   => 'badge-danger',
                                        default      => 'badge-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $sc }}">{{ $order->status }}</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.fnb-orders.show', $order->id) }}" class="btn btn-sm btn-primary">Detail</a>
                                <a href="{{ route('admin.fnb-orders.edit', $order->id) }}" class="btn btn-sm btn-info">Update</a>
                                <button class="btn btn-sm btn-danger btn-delete"
                                    data-action="{{ route('admin.fnb-orders.destroy', $order->id) }}"
                                    data-label="Order #{{ $order->id }}">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i data-feather="coffee" style="width:32px;height:32px;opacity:.3;"></i>
                                <p class="mt-2 mb-0">Belum ada pesanan FnB.</p>
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
