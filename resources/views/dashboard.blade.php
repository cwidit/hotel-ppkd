@extends('layouts.admin')

@section('title', 'Dashboard - Administrator')

@section('content')
    <div class="page-title mb-3">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h3>Dashboard Administrator</h3>
                <p class="mb-0" style="color:#111827;">{{ now('Asia/Jakarta')->translatedFormat('l, d F Y') }}</p>
                <p class="text-muted" style="font-size:13px;">
                    <i data-feather="clock" style="width:13px;height:13px;"></i>
                    <span id="live-clock">--:--:--</span>
                    <small class="ml-1">WIB (GMT+7)</small>
                </p>
            </div>
            <div class="col-md-4 text-right">
                <a href="{{ route('admin.reservations.create') }}" class="btn btn-primary">
                    <i data-feather="plus" style="width:16px;height:16px;"></i> New Reservation
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
                            <div class="stat-value text-primary">{{ $vacantRooms }} <small
                                    style="font-size:.9rem;color:#aaa;">/ {{ $totalRooms }}</small></div>
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
                            <i data-feather="dollar-sign" style="color:#ffc107;width:24px;height:24px;"></i>
                        </div>
                        <div>
                            <div class="stat-value" style="font-size:1.2rem;color:#333;">Rp
                                {{ number_format($todayRevenue, 0, ',', '.') }}</div>
                            <div class="stat-label">Pendapatan Hari Ini
                                @if ($revenueTrend !== null)
                                    <span class="{{ $revenueTrend >= 0 ? 'trend-up' : 'trend-down' }} ml-1">
                                        {{ $revenueTrend >= 0 ? '↑' : '↓' }} {{ abs($revenueTrend) }}%
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card stat-card">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon mr-3" style="background:#fdecea;">
                            <i data-feather="alert-circle" style="color:#dc3545;width:24px;height:24px;"></i>
                        </div>
                        <div>
                            <div class="stat-value text-danger">{{ $dirtyRooms }}</div>
                            <div class="stat-label">Kamar Kotor</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Second row stats --}}
        <div class="row mb-4">
            <div class="col-6 col-md-3">
                <div class="card stat-card">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon mr-3" style="background:#fff8e1;">
                            <i data-feather="log-in" style="color:#ffc107;width:20px;height:20px;"></i>
                        </div>
                        <div>
                            <div class="stat-value text-warning">{{ $todayCheckins }}</div>
                            <div class="stat-label">Check-In Hari Ini</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card stat-card">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon mr-3" style="background:#fdecea;">
                            <i data-feather="log-out" style="color:#dc3545;width:20px;height:20px;"></i>
                        </div>
                        <div>
                            <div class="stat-value text-danger">{{ $todayCheckouts }}</div>
                            <div class="stat-label">Check-Out Hari Ini</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card stat-card">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon mr-3" style="background:#fde8e8;">
                            <i data-feather="coffee" style="color:#e74c3c;width:20px;height:20px;"></i>
                        </div>
                        <div>
                            <div class="stat-value" style="font-size:1.4rem;">{{ $pendingFnb }}</div>
                            <div class="stat-label">FnB Pending</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card stat-card">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon mr-3" style="background:#e8f0fe;">
                            <i data-feather="wind" style="color:#1d7af3;width:20px;height:20px;"></i>
                        </div>
                        <div>
                            <div class="stat-value" style="font-size:1.4rem;">{{ $pendingLaundry }}</div>
                            <div class="stat-label">Laundry Pending</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- FLOOR BOARD --}}
            <div class="col-12">
                <div class="card floor-board mb-4">
                    <div class="card-header">
                        <h4 class="mb-0">🏨 Floor Board - Status Kamar</h4>
                    </div>
                    <div class="card-body">
                        @foreach ($floors->sortKeysDesc() as $floor => $floorRooms)
                            <div class="mb-4">
                                <div class="floor-title">
                                    Lantai {{ $floor }}
                                </div>
                                <div class="d-flex flex-wrap">
                                    @foreach ($floorRooms as $room)
                                        @php
                                            $statusClass = 'room-vacant';
                                            if (str_contains($room->status, 'Occupied')) {
                                                $statusClass = 'room-occupied';
                                            }
                                            if (
                                                in_array($room->status, [
                                                    'Vacant Dirty (VD)',
                                                    'Occupied Dirty (OD)',
                                                    'Make Up Room (MUR)',
                                                ])
                                            ) {
                                                $statusClass = 'room-dirty';
                                            }
                                            if (str_contains($room->status, 'Out')) {
                                                $statusClass = 'room-maintenance';
                                            }
                                        @endphp
                                        <div class="room-box {{ $statusClass }}">
                                            <div class="room-number">
                                                {{ $room->room_number }}
                                            </div>

                                            <div class="room-type">
                                                {{ $room->roomType->name }} -
                                                {{ str_replace(' Bed', '', $room->bed_type) }}
                                            </div>

                                            <div class="room-status-text">
                                                @if (str_contains($room->status, 'Occupied'))
                                                    🔴 OCCUPIED
                                                @elseif(in_array($room->status, ['Vacant Dirty (VD)', 'Occupied Dirty (OD)', 'Make Up Room (MUR)']))
                                                    🟡 DIRTY
                                                @elseif(str_contains($room->status, 'Out'))
                                                    ⚫ OOO
                                                @else
                                                    🟢 VACANT
                                                @endif
                                            </div>

                                            @if ($room->connecting_room_id)
                                                <small class="d-block">🔗 Connecting</small>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            {{-- Calendar Occupancy --}}
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Kalender Ketersediaan Kamar</h4>
                        <div>
                            <span class="badge badge-success">Tersedia</span>
                            <span class="badge badge-primary">Terisi</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                            <table class="table table-bordered table-sm m-0"
                                style="font-size: 11px; white-space: nowrap;">
                                <thead class="thead-light sticky-top">
                                    <tr>
                                        <th style="min-width: 80px;">Kamar</th>
                                        @foreach ($dateRange as $date)
                                            <th class="text-center {{ $date->isWeekend() ? 'text-danger' : '' }}">
                                                {{ $date->format('d/m') }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rooms as $room)
                                        <tr>
                                            <td class="font-weight-bold">{{ $room->room_number }} <span
                                                    class="text-muted d-block"
                                                    style="font-size:9px;">{{ $room->roomType->name }}</span></td>
                                            @foreach ($dateRange as $date)
                                                @php
                                                    $res = $calendarData[$room->id][$date->format('Y-m-d')];
                                                @endphp
                                                @if ($res)
                                                    <td class="bg-primary text-white text-center p-0 align-middle"
                                                        title="{{ $res->guest->first_name }} ({{ $res->booking_number }})"
                                                        style="cursor:pointer;"
                                                        onclick="window.location='{{ route('admin.reservations.show', $res->id) }}'">
                                                        <div
                                                            style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                                                            <i data-feather="user" style="width:12px;height:12px;"></i>
                                                        </div>
                                                    </td>
                                                @else
                                                    <td class="text-center align-middle"
                                                        style="background-color: #f8f9fc;">
                                                        <span class="text-success" style="opacity:0.5;">✓</span>
                                                    </td>
                                                @endif
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Recent Reservations --}}
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Reservasi Terbaru</h4>
                        <a href="{{ route('admin.reservations.index') }}" class="btn btn-sm btn-outline-primary">Lihat
                            Semua</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>No. Booking</th>
                                        <th>Tamu</th>
                                        <th>Check-In</th>
                                        <th>Status</th>
                                        <th>Bayar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentReservations as $res)
                                        <tr style="cursor:pointer;"
                                            onclick="window.location='{{ route('admin.reservations.show', $res->id) }}'">
                                            <td><small class="text-muted">{{ $res->booking_number }}</small></td>
                                            <td>{{ $res->guest->first_name }} {{ $res->guest->last_name }}</td>
                                            <td>{{ \Carbon\Carbon::parse($res->check_in_date)->format('d M Y') }}</td>
                                            <td>
                                                @php
                                                    $sc = match ($res->status) {
                                                        'Confirmed' => 'primary',
                                                        'Checked_In' => 'success',
                                                        'Checked_Out' => 'info',
                                                        'Canceled' => 'danger',
                                                        default => 'secondary',
                                                    };
                                                @endphp
                                                <span
                                                    class="badge badge-{{ $sc }}">{{ str_replace('_', ' ', $res->status) }}</span>
                                            </td>
                                            <td>
                                                @php
                                                    $pc = match ($res->payment_status) {
                                                        'Paid' => 'success',
                                                        'Deposit_Paid' => 'warning',
                                                        'Partial' => 'info',
                                                        default => 'danger',
                                                    };
                                                @endphp
                                                <span
                                                    class="badge badge-{{ $pc }}">{{ str_replace('_', ' ', $res->payment_status) }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-3">Belum ada reservasi.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                {{-- Room Status Doughnut --}}
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="mb-0">Status Kamar</h4>
                    </div>
                    <div class="card-body">
                        <div style="max-width:220px;margin:auto;">
                            <canvas id="roomStatusChart"></canvas>
                        </div>
                        <div class="card mb-4">
                            <div class="card-header">
                                <h4 class="mb-0">Status Kamar</h4>
                            </div>

                            <div class="card-body text-center">
                                <div style="max-width:260px; margin:auto;">
                                    <canvas id="roomStatusChart"></canvas>
                                </div>

                                <div class="mt-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>
                                            <span
                                                style="display:inline-block;width:10px;height:10px;background:#28a745;border-radius:50%;margin-right:5px;"></span>
                                            Vacant
                                        </span>
                                        <strong>{{ $roomStatuses['Vacant'] }}</strong>
                                    </div>

                                    <div class="d-flex justify-content-between mb-1">
                                        <span>
                                            <span
                                                style="display:inline-block;width:10px;height:10px;background:#1d7af3;border-radius:50%;margin-right:5px;"></span>
                                            Occupied
                                        </span>
                                        <strong>{{ $roomStatuses['Occupied'] }}</strong>
                                    </div>

                                    <div class="d-flex justify-content-between">
                                        <span>
                                            <span
                                                style="display:inline-block;width:10px;height:10px;background:#dc3545;border-radius:50%;margin-right:5px;"></span>
                                            Maintenance
                                        </span>
                                        <strong>{{ $roomStatuses['Maintenance'] }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Quick Actions --}}
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Akses Cepat</h4>
                    </div>
                    <div class="card-body p-2">
                        <a href="{{ route('admin.calendar.index') }}"
                            class="btn btn-outline-primary btn-block mb-2 text-left">
                            <i data-feather="grid" style="width:14px;height:14px;margin-right:6px;"></i> Kalender Okupansi
                        </a>
                        <a href="{{ route('admin.housekeeping.index') }}"
                            class="btn btn-outline-warning btn-block mb-2 text-left">
                            <i data-feather="layers" style="width:14px;height:14px;margin-right:6px;"></i> Housekeeping
                            Board
                        </a>
                        <a href="{{ route('admin.reports.daily') }}"
                            class="btn btn-outline-info btn-block mb-2 text-left">
                            <i data-feather="file-text" style="width:14px;height:14px;margin-right:6px;"></i> Daily Report
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-block text-left">
                            <i data-feather="user" style="width:14px;height:14px;margin-right:6px;"></i> Kelola Pengguna
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // Live clock — Jakarta WIB (GMT+7)
        (function() {
            function tick() {
                var now = new Date(new Date().toLocaleString('en-US', {
                    timeZone: 'Asia/Jakarta'
                }));
                var h = String(now.getHours()).padStart(2, '0');
                var m = String(now.getMinutes()).padStart(2, '0');
                var s = String(now.getSeconds()).padStart(2, '0');
                var el = document.getElementById('live-clock');
                if (el) el.textContent = h + ':' + m + ':' + s;
            }
            tick();
            setInterval(tick, 1000);
        })();
        document.addEventListener('DOMContentLoaded', function() {


            // Room Status Doughnut
            new Chart(document.getElementById('roomStatusChart').getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Vacant', 'Occupied', 'Maintenance'],
                    datasets: [{
                        data: {!! json_encode(array_values($roomStatuses)) !!},
                        backgroundColor: ['#28a745', '#1d7af3', '#dc3545'],
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    aspectRatio: 1.3,

                    plugins: {
                        legend: {
                            display: false
                        }
                    },

                    cutout: '70%',
                }
            });
        });
    </script>
@endpush
