@extends('layouts.admin')

@section('title', 'Daftar Pembayaran')

@section('content')
<div class="page-title mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h3>Modul Pembayaran</h3>
            <p class="text-subtitle text-muted">Kelola transaksi dan pembayaran tamu.</p>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('admin.payments.create') }}" class="btn btn-primary">
                <i data-feather="plus" style="width:15px;height:15px;"></i> Proses Pembayaran
            </a>
        </div>
    </div>
</div>

<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Histori Pembayaran</span>
            <small class="text-muted">Total: {{ $payments->count() }} transaksi</small>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="table1">
                    <thead>
                        <tr>
                            <th>Tgl Pembayaran</th>
                            <th>No. Reservasi</th>
                            <th>Metode</th>
                            <th>Nominal</th>
                            <th>Referensi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                        <tr>
                            <td><small>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y H:i') }}</small></td>
                            <td>
                                <a href="{{ route('admin.reservations.show', $payment->reservation_id) }}" class="text-primary">
                                    {{ $payment->reservation->booking_number }}
                                </a>
                            </td>
                            <td>
                                @php
                                    $mc = match($payment->payment_method) {
                                        'Cash'          => 'badge-success',
                                        'Bank_Transfer' => 'badge-primary',
                                        'QRIS'          => 'badge-info',
                                        'Credit_Card'   => 'badge-warning',
                                        default         => 'badge-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $mc }}">{{ str_replace('_', ' ', $payment->payment_method) }}</span>
                            </td>
                            <td><strong>Rp {{ number_format($payment->amount, 0, ',', '.') }}</strong></td>
                            <td><small class="text-muted">{{ $payment->reference_number ?? '-' }}</small></td>
                            <td><span class="badge badge-success">{{ $payment->status }}</span></td>
                            <td>
                                <a href="{{ route('admin.payments.show', $payment->id) }}" class="btn btn-sm btn-info">Detail</a>
                                <button class="btn btn-sm btn-danger btn-delete"
                                    data-action="{{ route('admin.payments.destroy', $payment->id) }}"
                                    data-label="Pembayaran Rp {{ number_format($payment->amount, 0, ',', '.') }}">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i data-feather="credit-card" style="width:32px;height:32px;opacity:.3;"></i>
                                <p class="mt-2 mb-0">Belum ada data pembayaran.</p>
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
