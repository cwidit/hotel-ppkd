@extends('layouts.admin')

@section('title', 'Detail Pesanan FnB')

@section('content')
<div class="page-title mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h3>Detail Pesanan #{{ $fnbOrder->id }}</h3>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('admin.fnb-orders.index') }}" class="btn btn-secondary">
                <i data-feather="arrow-left"></i> Kembali
            </a>
            <a href="{{ route('admin.fnb-orders.edit', $fnbOrder->id) }}" class="btn btn-info">
                <i data-feather="edit"></i> Update Status
            </a>
        </div>
    </div>
</div>

<section class="section">
    <div class="card">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h6>Informasi Tamu</h6>
                    <p><strong>Nama:</strong> {{ $fnbOrder->reservation->guest->first_name }} {{ $fnbOrder->reservation->guest->last_name }}<br>
                    <strong>No. Reservasi:</strong> {{ $fnbOrder->reservation->booking_number }}<br>
                    <strong>Status Pesanan:</strong> <span class="badge badge-info">{{ $fnbOrder->status }}</span></p>
                </div>
            </div>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Menu</th>
                        <th>Harga Satuan</th>
                        <th>Kuantitas</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($fnbOrder->items as $key => $item)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $item->fnbMenu->name }}</td>
                        <td>Rp {{ number_format($item->price_at_order, 0, ',', '.') }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>Rp {{ number_format($item->price_at_order * $item->quantity, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-right">Total Tagihan</th>
                        <th>Rp {{ number_format($fnbOrder->total_order_amount, 0, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>
            <p class="text-muted mt-2"><small>* Tagihan pesanan ini otomatis ditambahkan ke invoice akhir reservasi (Extra Charges).</small></p>
        </div>
    </div>
</section>
@endsection
