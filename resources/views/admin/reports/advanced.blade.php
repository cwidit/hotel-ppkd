@extends('layouts.admin')

@section('title', 'Laporan Lanjutan')

@section('content')
<div class="page-title mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h3>Advanced Reports</h3>
            <p class="text-subtitle text-muted">Filter laporan berdasarkan rentang waktu tertentu.</p>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('admin.reports.export-csv', ['type' => 'reservations', 'from' => $data['from'], 'to' => $data['to']]) }}" class="btn btn-success">
                <i data-feather="download"></i> Export CSV Reservasi
            </a>
            <a href="{{ route('admin.reports.export-csv', ['type' => 'payments', 'from' => $data['from'], 'to' => $data['to']]) }}" class="btn btn-info">
                <i data-feather="download"></i> Export CSV Pembayaran
            </a>
        </div>
    </div>
</div>

<section class="section">
    {{-- Filter Form --}}
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.reports.advanced') }}" method="GET" class="row align-items-end">
                <div class="col-md-3">
                    <label>Pilih Preset</label>
                    <select name="preset" class="form-control" onchange="toggleCustomDates(this.value)">
                        <option value="this_week" {{ $data['preset'] == 'this_week' ? 'selected' : '' }}>Minggu Ini</option>
                        <option value="last_week" {{ $data['preset'] == 'last_week' ? 'selected' : '' }}>Minggu Lalu</option>
                        <option value="this_month" {{ $data['preset'] == 'this_month' ? 'selected' : '' }}>Bulan Ini</option>
                        <option value="last_month" {{ $data['preset'] == 'last_month' ? 'selected' : '' }}>Bulan Lalu</option>
                        <option value="custom" {{ $data['preset'] == 'custom' ? 'selected' : '' }}>Custom Tanggal</option>
                    </select>
                </div>
                <div class="col-md-3 custom-date" style="display: {{ $data['preset'] == 'custom' ? 'block' : 'none' }};">
                    <label>Dari Tanggal</label>
                    <input type="date" name="from_date" class="form-control" value="{{ $data['from'] }}">
                </div>
                <div class="col-md-3 custom-date" style="display: {{ $data['preset'] == 'custom' ? 'block' : 'none' }};">
                    <label>Sampai Tanggal</label>
                    <input type="date" name="to_date" class="form-control" value="{{ $data['to'] }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">Tampilkan Laporan</button>
                </div>
            </form>
        </div>
    </div>

    @if(isset($data['revenue']))
    {{-- Summary Cards --}}
    <div class="row">
        <div class="col-md-3">
            <div class="card text-center p-3">
                <h6 class="text-muted">Total Pendapatan</h6>
                <h4 class="text-success">Rp {{ number_format($data['revenue'], 0, ',', '.') }}</h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center p-3">
                <h6 class="text-muted">Booking Baru</h6>
                <h4 class="text-primary">{{ $data['new_bookings'] }}</h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center p-3">
                <h6 class="text-muted">Total Check-In</h6>
                <h4 class="text-info">{{ $data['check_ins'] }}</h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center p-3">
                <h6 class="text-muted">Total Check-Out</h6>
                <h4 class="text-warning">{{ $data['check_outs'] }}</h4>
            </div>
        </div>
    </div>
    @endif

    @if(isset($data['reservations']))
    {{-- Table of Reservations --}}
    <div class="card">
        <div class="card-header">
            Daftar Reservasi Dibuat ({{ \Carbon\Carbon::parse($data['from'])->format('d M') }} - {{ \Carbon\Carbon::parse($data['to'])->format('d M Y') }})
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No. Booking</th>
                            <th>Tamu</th>
                            <th>Check-In</th>
                            <th>Status</th>
                            <th>Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data['reservations'] as $res)
                        <tr>
                            <td>{{ $res->booking_number }}</td>
                            <td>{{ $res->guest->first_name }} {{ $res->guest->last_name }}</td>
                            <td>{{ \Carbon\Carbon::parse($res->check_in_date)->format('d M Y') }}</td>
                            <td>{{ $res->status }}</td>
                            <td>Rp {{ number_format($res->total_amount, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data di periode ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</section>

@push('scripts')
<script>
function toggleCustomDates(val) {
    const customDivs = document.querySelectorAll('.custom-date');
    if (val === 'custom') {
        customDivs.forEach(div => div.style.display = 'block');
    } else {
        customDivs.forEach(div => div.style.display = 'none');
    }
}
</script>
@endpush
@endsection
