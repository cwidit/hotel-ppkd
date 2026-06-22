@extends('layouts.admin')

@section('title', 'Kalender Okupansi')

@section('content')
<div class="page-title mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h3>Kalender Okupansi</h3>
            <p class="text-subtitle text-muted">
                {{ $startDate->format('d M Y') }} — {{ $endDate->format('d M Y') }}
            </p>
        </div>
    </div>
</div>

<section class="section">
    {{-- Legend --}}
    <div class="mb-3 d-flex gap-3 flex-wrap" style="gap:12px;">
        <span><span style="display:inline-block;width:14px;height:14px;background:#c3e6cb;border:1px solid #28a745;border-radius:3px;vertical-align:middle;"></span> Available</span>
        <span><span style="display:inline-block;width:14px;height:14px;background:#bee5eb;border:1px solid #17a2b8;border-radius:3px;vertical-align:middle;"></span> Confirmed</span>
        <span><span style="display:inline-block;width:14px;height:14px;background:#1d7af3;border:1px solid #1d7af3;border-radius:3px;vertical-align:middle;"></span> Checked In</span>
        <span><span style="display:inline-block;width:14px;height:14px;background:#f5f5f5;border:1px solid #ccc;border-radius:3px;vertical-align:middle;"></span> Kosong</span>
    </div>

    <div class="card">
        <div class="card-body p-2">
            <div class="table-responsive">
                <table style="border-collapse:collapse;width:100%;font-size:11px;">
                    <thead>
                        <tr>
                            <th style="min-width:90px;padding:6px 8px;background:#f5f7fa;border:1px solid #dee2e6;white-space:nowrap;position:sticky;left:0;z-index:2;">
                                Kamar
                            </th>
                            @foreach($days as $day)
                            <th style="min-width:38px;padding:4px 2px;text-align:center;background:{{ $day->isToday() ? '#e8f0fe' : '#f5f7fa' }};border:1px solid #dee2e6;white-space:nowrap;">
                                <div style="font-weight:{{ $day->isToday() ? 'bold' : 'normal' }};color:{{ $day->isToday() ? '#1d7af3' : '#555' }};">
                                    {{ $day->format('d') }}
                                </div>
                                <div style="font-size:9px;color:#999;">{{ $day->format('D') }}</div>
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rooms as $room)
                        <tr>
                            <td style="padding:5px 8px;border:1px solid #dee2e6;background:#fff;position:sticky;left:0;z-index:1;white-space:nowrap;">
                                <strong>{{ $room->room_number }}</strong>
                                <div style="font-size:9px;color:#999;">{{ $room->roomType->name ?? '-' }}</div>
                            </td>
                            @foreach($days as $day)
                            @php
                                $dateKey = $day->format('Y-m-d');
                                $entry   = $calendar[$room->id][$dateKey] ?? null;
                                $isToday = $day->isToday();

                                if ($entry) {
                                    $bgColor    = $entry['status'] === 'Checked_In' ? '#1d7af3' : '#bee5eb';
                                    $textColor  = $entry['status'] === 'Checked_In' ? '#fff' : '#0c5460';
                                    $borderColor= $entry['status'] === 'Checked_In' ? '#1565c0' : '#17a2b8';
                                    $title      = $entry['booking_number'];
                                } else {
                                    $bgColor    = $isToday ? '#fff9e6' : '#fff';
                                    $textColor  = '#ccc';
                                    $borderColor= $isToday ? '#ffc107' : '#dee2e6';
                                    $title      = '';
                                }
                            @endphp
                            <td style="padding:2px;border:1px solid {{ $borderColor }};background:{{ $bgColor }};text-align:center;vertical-align:middle;cursor:{{ $entry ? 'pointer' : 'default' }};"
                                @if($entry) title="{{ $entry['booking_number'] }} ({{ $entry['status'] }})"
                                onclick="window.location='{{ route('admin.reservations.show', $entry['reservation_id']) }}'" @endif>
                                @if($entry)
                                    <span style="font-size:8px;color:{{ $textColor }};font-weight:bold;display:block;overflow:hidden;max-width:36px;white-space:nowrap;text-overflow:ellipsis;">
                                        {{ substr($entry['booking_number'], -4) }}
                                    </span>
                                @endif
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <p class="text-muted mt-2" style="font-size:11px;">
        * Klik pada blok berwarna untuk membuka detail reservasi.
        Kalender menampilkan reservasi berstatus <strong>Confirmed</strong> dan <strong>Checked In</strong>.
    </p>
</section>
@endsection
