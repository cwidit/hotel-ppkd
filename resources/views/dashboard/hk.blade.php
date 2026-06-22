@extends('layouts.admin')

@section('title', 'Dashboard - Housekeeping')

@section('content')
<div class="page-title mb-3">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h3>Dashboard Housekeeping</h3>
            <p class="text-subtitle text-muted mb-0">{{ now()->translatedFormat('l, d F Y') }}</p>
            <p class="text-muted" style="font-size:13px;">
                <i data-feather="clock" style="width:13px;height:13px;"></i>
                <span id="live-clock">--:--:--</span>
                <small class="ml-1">WIB (GMT+7)</small>
            </p>
        </div>
        <div class="col-md-4 text-right">
            <a href="{{ route('admin.housekeeping.index') }}" class="btn btn-primary">
                <i data-feather="layers" style="width:16px;height:16px;"></i> Housekeeping Board
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
                        <i data-feather="alert-circle" style="color:#dc3545;width:24px;height:24px;"></i>
                    </div>
                    <div>
                        <div class="stat-value text-danger">{{ $dirtyRooms->count() }}</div>
                        <div class="stat-label">Kamar Kotor</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon mr-3" style="background:#e8f4fd;">
                        <i data-feather="search" style="color:#1d7af3;width:24px;height:24px;"></i>
                    </div>
                    <div>
                        <div class="stat-value text-primary">{{ $pendingInspections }}</div>
                        <div class="stat-label">Inspeksi Hari Ini</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon mr-3" style="background:#fff8e1;">
                        <i data-feather="wind" style="color:#ffc107;width:24px;height:24px;"></i>
                    </div>
                    <div>
                        <div class="stat-value text-warning">{{ $pendingLaundry }}</div>
                        <div class="stat-label">Laundry Pending</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon mr-3" style="background:#e8f8f0;">
                        <i data-feather="loader" style="color:#28a745;width:24px;height:24px;"></i>
                    </div>
                    <div>
                        <div class="stat-value text-success">{{ $processingLaundry }}</div>
                        <div class="stat-label">Laundry Proses</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Dirty Rooms Grid --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i data-feather="alert-circle" style="width:16px;height:16px;color:#dc3545;margin-right:6px;"></i> Kamar Perlu Dibersihkan</span>
            <a href="{{ route('admin.housekeeping.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
        </div>
        <div class="card-body">
            @if($dirtyRooms->isEmpty())
                <div class="text-center py-4 text-muted">
                    <i data-feather="check-circle" style="width:40px;height:40px;color:#28a745;"></i>
                    <p class="mt-2">Semua kamar sudah bersih!</p>
                </div>
            @else
                <div class="row">
                    @foreach($dirtyRooms as $room)
                    <div class="col-md-2 col-4 mb-3">
                        <div class="card text-center border" style="border-color:#ffc107 !important;">
                            <div class="card-body p-2">
                                <h5 class="mb-1">{{ $room->room_number }}</h5>
                                <small class="text-muted d-block mb-2" style="font-size:10px;">{{ $room->roomType->name ?? '-' }}</small>
                                <form action="{{ route('admin.housekeeping.markClean', $room->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="new_status" value="Vacant Clean (VC)">
                                    <button class="btn btn-xs btn-success w-100" style="font-size:10px;padding:2px 4px;">✓ Bersih</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <i data-feather="wind" style="width:16px;height:16px;margin-right:6px;"></i> Request Laundry Pending
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead><tr><th>Booking</th><th>Layanan</th><th>Qty</th><th>Aksi</th></tr></thead>
                            <tbody>
                                @forelse(\App\Models\LaundryRequest::with(['reservation.guest','laundryService'])->where('status','Pending')->take(8)->get() as $lr)
                                <tr>
                                    <td><small>{{ $lr->reservation->booking_number }}</small></td>
                                    <td>{{ $lr->laundryService->name ?? '-' }}</td>
                                    <td>{{ $lr->quantity }}</td>
                                    <td>
                                        <a href="{{ route('admin.laundry-requests.index') }}" class="btn btn-xs btn-warning" style="padding:2px 6px;font-size:10px;">Proses</a>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="text-center text-muted py-2">Tidak ada.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <i data-feather="check-square" style="width:16px;height:16px;margin-right:6px;"></i> Inspeksi Terbaru
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead><tr><th>Kamar</th><th>Hasil</th><th>Waktu</th></tr></thead>
                            <tbody>
                                @forelse(\App\Models\RoomInspection::with('room')->latest()->take(6)->get() as $ins)
                                <tr>
                                    <td><strong>{{ $ins->room->room_number }}</strong></td>
                                    <td>
                                        @php
                                            $badge = match($ins->inspection_result) {
                                                'Clean_Available' => 'success',
                                                'Dirty'           => 'warning',
                                                'Damaged'         => 'danger',
                                                default           => 'secondary',
                                            };
                                        @endphp
                                        <span class="badge badge-{{ $badge }}" style="font-size:10px;">{{ $ins->inspection_result }}</span>
                                    </td>
                                    <td><small class="text-muted">{{ $ins->created_at->diffForHumans() }}</small></td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="text-center text-muted py-2">Tidak ada.</td></tr>
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
