@extends('layouts.admin')

@section('title', 'Dashboard - Food & Beverage')

@section('content')
<div class="page-title mb-3">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h3>Dashboard Food & Beverage</h3>
            <p class="text-subtitle text-muted mb-0">{{ now()->translatedFormat('l, d F Y') }}</p>
            <p class="text-muted" style="font-size:13px;">
                <i data-feather="clock" style="width:13px;height:13px;"></i>
                <span id="live-clock">--:--:--</span>
                <small class="ml-1">WIB (GMT+7)</small>
            </p>
        </div>
        <div class="col-md-4 text-right">
            <a href="{{ route('admin.fnb-orders.index') }}" class="btn btn-primary">
                <i data-feather="list" style="width:16px;height:16px;"></i> Semua Orders
            </a>
        </div>
    </div>
</div>

<section class="section">
    {{-- Stats --}}
    <div class="row mb-4">
        <div class="col-6 col-md-3">
            <div class="card stat-card">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon mr-3" style="background:#fdecea;">
                        <i data-feather="bell" style="color:#dc3545;width:24px;height:24px;"></i>
                    </div>
                    <div>
                        <div class="stat-value text-danger">{{ $newOrders->count() }}</div>
                        <div class="stat-label">Order Baru</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon mr-3" style="background:#fff8e1;">
                        <i data-feather="loader" style="color:#ffc107;width:24px;height:24px;"></i>
                    </div>
                    <div>
                        <div class="stat-value text-warning">{{ $processingOrders->count() }}</div>
                        <div class="stat-label">Sedang Diproses</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon mr-3" style="background:#e8f8f0;">
                        <i data-feather="check-circle" style="color:#28a745;width:24px;height:24px;"></i>
                    </div>
                    <div>
                        <div class="stat-value text-success">{{ $deliveredToday }}</div>
                        <div class="stat-label">Terkirim Hari Ini</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon mr-3" style="background:#e8f4fd;">
                        <i data-feather="dollar-sign" style="color:#1d7af3;width:24px;height:24px;"></i>
                    </div>
                    <div>
                        <div class="stat-value text-primary" style="font-size:1.2rem;">
                            Rp {{ number_format($todayRevenue, 0, ',', '.') }}
                        </div>
                        <div class="stat-label">Pendapatan FnB Hari Ini</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- New Orders --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>
                        <i data-feather="bell" style="width:16px;height:16px;color:#dc3545;margin-right:6px;"></i>
                        Order Baru (Pending)
                    </span>
                    <span class="badge badge-danger">{{ $newOrders->count() }}</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead><tr><th>#</th><th>Tamu / Kamar</th><th>Total</th><th>Aksi</th></tr></thead>
                            <tbody>
                                @forelse($newOrders as $order)
                                <tr>
                                    <td><small>#{{ $order->id }}</small></td>
                                    <td>
                                        {{ $order->reservation->guest->first_name ?? '-' }}<br>
                                        <small class="text-muted">{{ $order->reservation->booking_number }}</small>
                                    </td>
                                    <td>Rp {{ number_format($order->total_order_amount, 0, ',', '.') }}</td>
                                    <td>
                                        <a href="{{ route('admin.fnb-orders.show', $order->id) }}" class="btn btn-xs btn-primary" style="padding:2px 8px;font-size:11px;">Detail</a>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="text-center text-muted py-3">Tidak ada order baru.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Processing Orders --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>
                        <i data-feather="loader" style="width:16px;height:16px;color:#ffc107;margin-right:6px;"></i>
                        Sedang Diproses
                    </span>
                    <span class="badge badge-warning">{{ $processingOrders->count() }}</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead><tr><th>#</th><th>Tamu</th><th>Total</th><th>Aksi</th></tr></thead>
                            <tbody>
                                @forelse($processingOrders as $order)
                                <tr>
                                    <td><small>#{{ $order->id }}</small></td>
                                    <td>{{ $order->reservation->guest->first_name ?? '-' }}</td>
                                    <td>Rp {{ number_format($order->total_order_amount, 0, ',', '.') }}</td>
                                    <td>
                                        <a href="{{ route('admin.fnb-orders.show', $order->id) }}" class="btn btn-xs btn-warning" style="padding:2px 8px;font-size:11px;">Update</a>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="text-center text-muted py-3">Tidak ada order diproses.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
