@extends('layouts.admin')

@section('title', 'Dashboard - Front Office')

@section('content')
<div class="page-title mb-3">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h3>Dashboard Front Office</h3>
            <p class="text-subtitle text-muted mb-0">{{ now()->translatedFormat('l, d F Y') }}</p>
            <p class="text-muted" style="font-size:13px;">
                <i data-feather="clock" style="width:13px;height:13px;"></i>
                <span id="live-clock">--:--:--</span>
                <small class="ml-1">WIB (GMT+7)</small>
            </p>
        </div>
        <div class="col-md-4 text-right">
            <a href="{{ route('admin.reservations.create') }}" class="btn btn-primary">
                <i data-feather="plus" style="width:16px;height:16px;"></i> Reservasi Baru
            </a>
        </div>
    </div>
</div>

<section class="section">
    {{-- Stat Cards --}}
    <div class="row mb-4">
        <div class="col-6 col-md-3">
            <div class="card stat-card">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon mr-3" style="background:#e8f4fd;">
                        <i data-feather="home" style="color:#1d7af3;width:24px;height:24px;"></i>
                    </div>
                    <div>
                        <div class="stat-value text-primary">{{ $vacantRooms }}</div>
                        <div class="stat-label">Kamar Tersedia</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon mr-3" style="background:#e8f8f0;">
                        <i data-feather="users" style="color:#28a745;width:24px;height:24px;"></i>
                    </div>
                    <div>
                        <div class="stat-value text-success">{{ $occupiedRooms }}</div>
                        <div class="stat-label">Kamar Terisi</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon mr-3" style="background:#fff8e1;">
                        <i data-feather="log-in" style="color:#ffc107;width:24px;height:24px;"></i>
                    </div>
                    <div>
                        <div class="stat-value text-warning">{{ $todayCheckins->count() }}</div>
                        <div class="stat-label">Tiba Hari Ini</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon mr-3" style="background:#fdecea;">
                        <i data-feather="log-out" style="color:#dc3545;width:24px;height:24px;"></i>
                    </div>
                    <div>
                        <div class="stat-value text-danger">{{ $todayCheckouts->count() }}</div>
                        <div class="stat-label">Berangkat Hari Ini</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Arrivals --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <span><i data-feather="log-in" style="width:16px;height:16px;color:#ffc107;margin-right:6px;"></i> Kedatangan Hari Ini</span>
                    <span class="badge badge-warning">{{ $todayCheckins->count() }}</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead><tr><th>Booking #</th><th>Nama Tamu</th><th>Malam</th><th>Aksi</th></tr></thead>
                            <tbody>
                                @forelse($todayCheckins as $r)
                                <tr>
                                    <td><small class="text-muted">{{ $r->booking_number }}</small></td>
                                    <td>{{ $r->guest->first_name }} {{ $r->guest->last_name }}</td>
                                    <td>{{ $r->total_days }}</td>
                                    <td>
                                        <a href="{{ route('admin.reservations.show', $r->id) }}" class="btn btn-xs btn-primary" style="padding:2px 8px;font-size:11px;">Detail</a>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="text-center text-muted py-3">Tidak ada kedatangan hari ini.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Departures --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <span><i data-feather="log-out" style="width:16px;height:16px;color:#dc3545;margin-right:6px;"></i> Keberangkatan Hari Ini</span>
                    <span class="badge badge-danger">{{ $todayCheckouts->count() }}</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead><tr><th>Booking #</th><th>Nama Tamu</th><th>Total</th><th>Aksi</th></tr></thead>
                            <tbody>
                                @forelse($todayCheckouts as $r)
                                <tr>
                                    <td><small class="text-muted">{{ $r->booking_number }}</small></td>
                                    <td>{{ $r->guest->first_name }} {{ $r->guest->last_name }}</td>
                                    <td>Rp {{ number_format($r->total_amount, 0, ',', '.') }}</td>
                                    <td>
                                        <a href="{{ route('admin.reservations.show', $r->id) }}" class="btn btn-xs btn-info" style="padding:2px 8px;font-size:11px;">Checkout</a>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="text-center text-muted py-3">Tidak ada keberangkatan hari ini.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Pending Notifications --}}
    @if($pendingFnb > 0 || $pendingLaundry > 0)
    <div class="alert alert-warning mt-2">
        <i data-feather="bell" style="width:16px;height:16px;margin-right:6px;"></i>
        <strong>Notifikasi:</strong>
        @if($pendingFnb > 0) {{ $pendingFnb }} order FnB menunggu. @endif
        @if($pendingLaundry > 0) {{ $pendingLaundry }} request laundry menunggu. @endif
        <a href="{{ route('admin.fnb-orders.index') }}" class="alert-link ml-2">Lihat FnB</a> |
        <a href="{{ route('admin.laundry-requests.index') }}" class="alert-link">Lihat Laundry</a>
    </div>
    @endif
</section>
@endsection

@push('scripts')
<script>
(function () {
    function tick() {
        var now = new Date(new Date().toLocaleString('en-US', { timeZone: 'Asia/Jakarta' }));
        var h = String(now.getHours()).padStart(2, '0');
        var m = String(now.getMinutes()).padStart(2, '0');
        var s = String(now.getSeconds()).padStart(2, '0');
        var el = document.getElementById('live-clock');
        if (el) el.textContent = h + ':' + m + ':' + s;
    }
    tick();
    setInterval(tick, 1000);
})();
</script>
@endpush
