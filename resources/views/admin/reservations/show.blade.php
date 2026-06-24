@extends('layouts.admin')

@section('title', 'Detail Reservasi')

@section('content')
<div class="page-title mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h3>Detail Reservasi: {{ $reservation->booking_number }}</h3>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('admin.reservations.index') }}" class="btn btn-secondary">
                <i data-feather="arrow-left"></i> Kembali
            </a>
            <a href="{{ route('admin.reservations.print', $reservation->id) }}" class="btn btn-success" target="_blank">
                <i data-feather="printer"></i> Cetak Invoice
            </a>
        </div>
    </div>
</div>

<section class="section">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><h4>Informasi Tamu</h4></div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="35%"><strong>Nama Lengkap</strong></td>
                            <td>: {{ $reservation->guest->first_name }} {{ $reservation->guest->last_name }}</td>
                        </tr>
                        <tr>
                            <td><strong>No. Identitas</strong></td>
                            <td>: {{ $reservation->guest->identity_number }}</td>
                        </tr>
                        <tr>
                            <td><strong>No. HP</strong></td>
                            <td>: {{ $reservation->guest->phone }}</td>
                        </tr>
                        <tr>
                            <td><strong>Email</strong></td>
                            <td>: {{ $reservation->guest->email ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><h4>Informasi Pemesanan</h4></div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="35%"><strong>Check-In</strong></td>
                            <td>: {{ \Carbon\Carbon::parse($reservation->check_in_date)->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Check-Out</strong></td>
                            <td>: {{ \Carbon\Carbon::parse($reservation->check_out_date)->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Total Malam</strong></td>
                            <td>: {{ $reservation->total_days }} Malam</td>
                        </tr>
                        <tr>
                            <td><strong>Status Reservasi</strong></td>
                            <td>
                                <form action="{{ route('admin.reservations.update', $reservation->id) }}" method="POST" class="d-flex align-items-center">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="payment_status" value="{{ $reservation->payment_status }}">
                                    <select name="status" class="form-control form-control-sm mr-2" onchange="this.form.submit()" style="max-width:160px;">
                                        <option value="Confirmed"   {{ $reservation->status == 'Confirmed'   ? 'selected' : '' }}>Confirmed</option>
                                        <option value="Checked_In"  {{ $reservation->status == 'Checked_In'  ? 'selected' : '' }} {{ $reservation->payment_status !== 'Paid' && $reservation->status !== 'Checked_In' ? 'disabled' : '' }}>Checked In {{ $reservation->payment_status !== 'Paid' && $reservation->status !== 'Checked_In' ? '(Harus Lunas)' : '' }}</option>
                                        <option value="Checked_Out" {{ $reservation->status == 'Checked_Out' ? 'selected' : '' }}>Checked Out</option>
                                        <option value="Canceled"    {{ $reservation->status == 'Canceled'    ? 'selected' : '' }}>Canceled</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Status Bayar</strong></td>
                            <td>
                                <form action="{{ route('admin.reservations.update', $reservation->id) }}" method="POST" class="d-flex align-items-center">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="status" value="{{ $reservation->status }}">
                                    <select name="payment_status" class="form-control form-control-sm mr-2" onchange="this.form.submit()" style="max-width:160px;">
                                        <option value="Unpaid"       {{ $reservation->payment_status == 'Unpaid'       ? 'selected' : '' }}>Unpaid</option>
                                        <option value="Deposit_Paid" {{ $reservation->payment_status == 'Deposit_Paid' ? 'selected' : '' }}>Deposit Paid</option>
                                        <option value="Paid"         {{ $reservation->payment_status == 'Paid'         ? 'selected' : '' }}>Paid Fully</option>
                                        <option value="Partial"      {{ $reservation->payment_status == 'Partial'      ? 'selected' : '' }}>Partial</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Rooms --}}
    <div class="card">
        <div class="card-header"><h4>Daftar Kamar</h4></div>
        <div class="card-body">
            @php $roomTotal = 0; @endphp
            @foreach($reservation->reservationRooms as $i => $rr)
                @php $sub = $rr->price_at_booking * $reservation->total_days; $roomTotal += $sub; @endphp
                <div class="d-flex justify-content-between align-items-start border rounded p-3 mb-3" style="background:#f8f9fc;">
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center mb-1">
                            <span class="font-weight-bold mr-2" style="font-size:1.1rem;">Kamar {{ $rr->room->room_number }}</span>
                            <span class="badge badge-primary">{{ $rr->roomType->name }}</span>
                            @if($rr->roomType->has_breakfast)
                                <span class="badge badge-success ml-1">Sarapan Termasuk</span>
                            @endif
                        </div>
                        <div class="d-flex flex-wrap mb-2" style="gap:16px;">
                            <small class="text-muted">
                                <i data-feather="users" style="width:13px;height:13px;"></i>
                                Kapasitas: <strong>{{ $rr->roomType->capacity }} orang</strong>
                            </small>
                            <small class="text-muted">
                                <i data-feather="moon" style="width:13px;height:13px;"></i>
                                Durasi: <strong>{{ $reservation->total_days }} malam</strong>
                            </small>
                            <small class="text-muted">
                                <i data-feather="tag" style="width:13px;height:13px;"></i>
                                Harga/malam: <strong>Rp {{ number_format($rr->price_at_booking, 0, ',', '.') }}</strong>
                            </small>
                        </div>
                        @if($rr->roomType->description)
                        <small class="text-muted">
                            <i data-feather="info" style="width:13px;height:13px;"></i>
                            {{ $rr->roomType->description }}
                        </small>
                        @endif
                    </div>
                    <div class="text-right ml-4" style="white-space:nowrap;">
                        <div class="text-muted" style="font-size:11px;">Subtotal</div>
                        <strong style="font-size:1.05rem;">Rp {{ number_format($sub, 0, ',', '.') }}</strong>
                    </div>
                </div>
            @endforeach
            <div class="text-right">
                <strong>Total Kamar: Rp {{ number_format($roomTotal, 0, ',', '.') }}</strong>
            </div>
        </div>
    </div>

    {{-- Extra Charges (FnB, Laundry, Damage) --}}
    @if($reservation->extraCharges->isNotEmpty())
    <div class="card">
        <div class="card-header"><h4>Biaya Tambahan (Extra Charges)</h4></div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Jenis</th>
                            <th>Keterangan</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reservation->extraCharges as $i => $ec)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td><span class="badge badge-secondary">{{ $ec->charge_type }}</span></td>
                            <td>{{ $ec->name }}</td>
                            <td>Rp {{ number_format($ec->amount, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-right">Total Extra Charges</th>
                            <th>Rp {{ number_format($reservation->extraCharges->sum('amount'), 0, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- Invoice Summary --}}
    <div class="card">
        <div class="card-header"><h4>Ringkasan Tagihan</h4></div>
        <div class="card-body">
            @php
                $extraTotal   = $reservation->extraCharges->sum('amount');
                $grandTotal   = $roomTotal + $reservation->hotel_tax + $reservation->service_charge + $extraTotal;
                $totalPaid    = $reservation->payments->sum('amount');
                $remaining    = $grandTotal - $totalPaid;
            @endphp
            <div class="table-responsive">
                <table class="table table-bordered" style="max-width:500px;margin-left:auto;">
                    <tr>
                        <td>Total Kamar</td>
                        <td class="text-right">Rp {{ number_format($roomTotal, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Extra Charges (FnB, Laundry, dll)</td>
                        <td class="text-right">Rp {{ number_format($extraTotal, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Pajak Hotel (10%)</td>
                        <td class="text-right">Rp {{ number_format($reservation->hotel_tax, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Service Charge (5%)</td>
                        <td class="text-right">Rp {{ number_format($reservation->service_charge, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="table-primary">
                        <th>Grand Total</th>
                        <th class="text-right">Rp {{ number_format($grandTotal, 0, ',', '.') }}</th>
                    </tr>
                    <tr class="text-success">
                        <td>Total Pembayaran</td>
                        <td class="text-right">- Rp {{ number_format($totalPaid, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="{{ $remaining > 0 ? 'table-danger' : 'table-success' }}">
                        <th>{{ $remaining > 0 ? 'Sisa Tagihan' : 'Lunas' }}</th>
                        <th class="text-right">Rp {{ number_format(max(0, $remaining), 0, ',', '.') }}</th>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    {{-- Payments history --}}
    @if($reservation->payments->isNotEmpty())
    <div class="card">
        <div class="card-header"><h4>Riwayat Pembayaran</h4></div>
        <div class="card-body">
            <table class="table table-striped">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Tanggal</th>
                        <th>Metode</th>
                        <th>Referensi</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reservation->payments as $i => $pay)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($pay->payment_date)->format('d M Y H:i') }}</td>
                        <td>{{ str_replace('_', ' ', $pay->payment_method) }}</td>
                        <td>{{ $pay->reference_number ?? '-' }}</td>
                        <td>Rp {{ number_format($pay->amount, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</section>
@endsection
