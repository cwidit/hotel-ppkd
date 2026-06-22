@extends('layouts.admin')

@section('title', 'Daily Report')

@section('content')
<div class="page-title mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h3>Laporan Harian (Daily Report)</h3>
            <p class="text-subtitle text-muted">Ringkasan aktivitas operasional untuk tanggal <strong>{{ \Carbon\Carbon::parse($data['date'])->format('d F Y') }}</strong></p>
        </div>
        <div class="col-md-6 text-right">
            <form action="{{ route('admin.reports.daily') }}" method="GET" class="d-inline-flex align-items-center" style="gap:6px;">
                <input type="date" name="date" class="form-control form-control-sm" value="{{ $data['date'] }}" style="width:150px;">
                <button type="submit" class="btn btn-sm btn-primary">
                    <i data-feather="filter" style="width:13px;height:13px;"></i> Filter
                </button>
                <div class="dropdown">
                    <button class="btn btn-sm btn-success dropdown-toggle" type="button" data-toggle="dropdown">
                        <i data-feather="download" style="width:13px;height:13px;"></i> Export CSV
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="{{ route('admin.reports.export-csv', ['type'=>'reservations','from'=>$data['date'],'to'=>$data['date']]) }}">Reservasi Hari Ini</a>
                        <a class="dropdown-item" href="{{ route('admin.reports.export-csv', ['type'=>'payments','from'=>$data['date'],'to'=>$data['date']]) }}">Pembayaran Hari Ini</a>
                        <a class="dropdown-item" href="{{ route('admin.reports.export-csv', ['type'=>'fnb_orders','from'=>$data['date'],'to'=>$data['date']]) }}">FnB Orders Hari Ini</a>
                    </div>
                </div>
                <button type="button" onclick="window.print()" class="btn btn-sm btn-outline-secondary">
                    <i data-feather="printer" style="width:13px;height:13px;"></i> Cetak
                </button>
            </form>
        </div>
    </div>
</div>

<section class="section" id="print-area">
    
    @if(isset($data['revenue']))
    <!-- Admin Summary -->
    <div class="card bg-light-primary mb-4">
        <div class="card-body">
            <h4 class="mb-3">General Summary (Admin)</h4>
            <div class="row">
                <div class="col-md-3"><strong>Total Pemasukan:</strong><br><h4>Rp {{ number_format($data['revenue'], 0, ',', '.') }}</h4></div>
                <div class="col-md-3"><strong>Reservasi Baru:</strong><br><h4>{{ $data['new_bookings'] }}</h4></div>
                <div class="col-md-3"><strong>Check-Ins:</strong><br><h4>{{ $data['check_ins'] }}</h4></div>
                <div class="col-md-3"><strong>Check-Outs:</strong><br><h4>{{ $data['check_outs'] }}</h4></div>
            </div>
        </div>
    </div>
    @endif

    @if(isset($data['fo_role']))
    <!-- Front Office Report -->
    <div class="card mb-4 border-left-primary shadow-sm">
        <div class="card-header bg-white border-bottom">
            <h4 class="card-title text-primary"><i data-feather="calendar" class="mr-2"></i> Front Office Report</h4>
        </div>
        <div class="card-body mt-3">
            <div class="row">
                <div class="col-md-6">
                    <h6>Daftar Tamu Check-In Hari Ini</h6>
                    <table class="table table-sm table-bordered">
                        <thead class="bg-light">
                            <tr><th>No. Booking</th><th>Nama Tamu</th><th>Total Malam</th></tr>
                        </thead>
                        <tbody>
                            @foreach($data['check_ins_data'] as $ci)
                            <tr><td>{{ $ci->booking_number }}</td><td>{{ $ci->guest->first_name }}</td><td>{{ $ci->total_days }}</td></tr>
                            @endforeach
                            @if($data['check_ins_data']->isEmpty()) <tr><td colspan="3" class="text-center text-muted">Tidak ada data.</td></tr> @endif
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6>Daftar Tamu Check-Out Hari Ini</h6>
                    <table class="table table-sm table-bordered">
                        <thead class="bg-light">
                            <tr><th>No. Booking</th><th>Nama Tamu</th><th>Total Tagihan</th></tr>
                        </thead>
                        <tbody>
                            @foreach($data['check_outs_data'] as $co)
                            <tr><td>{{ $co->booking_number }}</td><td>{{ $co->guest->first_name }}</td><td>Rp {{ number_format($co->total_amount,0,',','.') }}</td></tr>
                            @endforeach
                            @if($data['check_outs_data']->isEmpty()) <tr><td colspan="3" class="text-center text-muted">Tidak ada data.</td></tr> @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if(isset($data['fnb_role']))
    <!-- FnB Report -->
    <div class="card mb-4 border-left-warning shadow-sm">
        <div class="card-header bg-white border-bottom">
            <h4 class="card-title text-warning"><i data-feather="coffee" class="mr-2"></i> Food & Beverage Report</h4>
        </div>
        <div class="card-body mt-3">
            <div class="d-flex justify-content-between mb-3">
                <h6>Daftar Pesanan FnB (Room Service)</h6>
                <strong>Total Revenue: Rp {{ number_format($data['fnb_revenue'], 0, ',', '.') }}</strong>
            </div>
            <table class="table table-sm table-bordered">
                <thead class="bg-light">
                    <tr><th>No. Pesanan</th><th>No. Reservasi / Tamu</th><th>Status</th><th>Subtotal</th></tr>
                </thead>
                <tbody>
                    @foreach($data['fnb_orders'] as $fnb)
                    <tr>
                        <td>#{{ $fnb->id }}</td>
                        <td>{{ $fnb->reservation->booking_number }} ({{ $fnb->reservation->guest->first_name }})</td>
                        <td>{{ $fnb->status }}</td>
                        <td>Rp {{ number_format($fnb->total_order_amount, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                    @if($data['fnb_orders']->isEmpty()) <tr><td colspan="4" class="text-center text-muted">Tidak ada data.</td></tr> @endif
                </tbody>
            </table>
        </div>
    </div>
    @endif

    @if(isset($data['hk_role']))
    <!-- Housekeeping Report -->
    <div class="card mb-4 border-left-info shadow-sm">
        <div class="card-header bg-white border-bottom">
            <h4 class="card-title text-info"><i data-feather="wind" class="mr-2"></i> Housekeeping & Laundry Report</h4>
        </div>
        <div class="card-body mt-3">
            <div class="row">
                <div class="col-md-6">
                    <h6>Log Inspeksi / Pembersihan Kamar</h6>
                    <table class="table table-sm table-bordered">
                        <thead class="bg-light">
                            <tr><th>Kamar</th><th>Waktu</th><th>Status Baru</th></tr>
                        </thead>
                        <tbody>
                            @foreach($data['room_inspections'] as $ri)
                            <tr>
                        <td>{{ $ri->room->room_number }}</td>
                                <td>{{ \Carbon\Carbon::parse($ri->inspection_date)->format('H:i') }}</td>
                                <td>
                                    @php
                                        $badge = match($ri->inspection_result) {
                                            'Clean_Available' => 'success',
                                            'Dirty'           => 'warning',
                                            'Damaged'         => 'danger',
                                            default           => 'secondary',
                                        };
                                    @endphp
                                    <span class="badge badge-{{ $badge }}">{{ $ri->inspection_result }}</span>
                                </td>
                            </tr>
                            @endforeach
                            @if($data['room_inspections']->isEmpty()) <tr><td colspan="3" class="text-center text-muted">Tidak ada data.</td></tr> @endif
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6>Daftar Permintaan Laundry</h6>
                    <table class="table table-sm table-bordered">
                        <thead class="bg-light">
                            <tr><th>No. Booking</th><th>Layanan / Qty</th><th>Status</th></tr>
                        </thead>
                        <tbody>
                            @foreach($data['laundry_requests'] as $lr)
                            <tr>
                                <td>{{ $lr->reservation->booking_number }}</td>
                                <td>{{ $lr->laundryService->name ?? 'Layanan' }} ({{ $lr->quantity }})</td>
                                <td>{{ $lr->status }}</td>
                            </tr>
                            @endforeach
                            @if($data['laundry_requests']->isEmpty()) <tr><td colspan="3" class="text-center text-muted">Tidak ada data.</td></tr> @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif

</section>

<style>
    .border-left-primary { border-left: 4px solid #435ebe !important; }
    .border-left-warning { border-left: 4px solid #ffc107 !important; }
    .border-left-info { border-left: 4px solid #17a2b8 !important; }
    @media print {
        body * { visibility: hidden; }
        #print-area, #print-area * { visibility: visible; }
        #print-area { position: absolute; left: 0; top: 0; width: 100%; }
        .btn { display: none !important; }
        .card { border: none !important; box-shadow: none !important; margin-bottom: 20px !important; }
    }
</style>
@endsection
