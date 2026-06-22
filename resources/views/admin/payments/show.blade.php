@extends('layouts.admin')

@section('title', 'Detail Pembayaran')

@section('content')
<div class="page-title mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h3>Invoice Pembayaran</h3>
        </div>
        <div class="col-md-6 text-right">
            <button onclick="window.print()" class="btn btn-success">
                <i data-feather="printer"></i> Cetak Invoice
            </button>
            <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary">
                <i data-feather="arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

<section class="section">
    <div class="card" id="invoice">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-sm-6">
                    <h6 class="mb-3">Dari:</h6>
                    <div>
                        <strong>Hotel Management System</strong>
                    </div>
                    <div>Jl. Contoh Hotel No. 123</div>
                    <div>Email: info@hotel.com</div>
                    <div>Phone: (021) 12345678</div>
                </div>

                <div class="col-sm-6 text-sm-right mt-4 mt-sm-0">
                    <h6 class="mb-3">Kepada (Tamu):</h6>
                    <div>
                        <strong>{{ $payment->reservation->guest->first_name }} {{ $payment->reservation->guest->last_name }}</strong>
                    </div>
                    <div>No Identitas: {{ $payment->reservation->guest->identity_number }}</div>
                    <div>Email: {{ $payment->reservation->guest->email ?? '-' }}</div>
                    <div>Phone: {{ $payment->reservation->guest->phone }}</div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered">
                        <tr>
                            <td width="30%"><strong>Nomor Pembayaran</strong></td>
                            <td>INV-{{ $payment->id }}-{{ \Carbon\Carbon::parse($payment->payment_date)->format('Ymd') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Nomor Reservasi</strong></td>
                            <td>{{ $payment->reservation->booking_number }}</td>
                        </tr>
                        <tr>
                            <td><strong>Tanggal & Waktu</strong></td>
                            <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d F Y, H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Metode Pembayaran</strong></td>
                            <td>{{ $payment->payment_method }} {{ $payment->reference_number ? ' (Ref: '.$payment->reference_number.')' : '' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Nominal Dibayar</strong></td>
                            <td><h4>Rp {{ number_format($payment->amount, 0, ',', '.') }}</h4></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="text-center mt-5">
                <p>Terima kasih atas pembayaran Anda.</p>
                <hr style="width: 200px;">
                <p><strong>Resepsionis / Finance</strong></p>
            </div>
        </div>
    </div>
</section>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    #invoice, #invoice * {
        visibility: visible;
    }
    #invoice {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    .btn {
        display: none !important;
    }
}
</style>
@endsection
