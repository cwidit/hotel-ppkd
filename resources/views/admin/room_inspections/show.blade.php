@extends('layouts.admin')

@section('title', 'Detail Inspeksi Kamar')

@section('content')
<div class="page-title mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h3>Detail Inspeksi #{{ $roomInspection->id }}</h3>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('admin.room-inspections.index') }}" class="btn btn-secondary">
                <i data-feather="arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

<section class="section">
    <div class="card">
        <div class="card-body">
            <table class="table table-borderless">
                <tr>
                    <td width="30%"><strong>Tanggal & Waktu Inspeksi</strong></td>
                    <td>: {{ \Carbon\Carbon::parse($roomInspection->inspection_date)->format('d F Y, H:i') }}</td>
                </tr>
                <tr>
                    <td><strong>No. Kamar</strong></td>
                    <td>: {{ $roomInspection->room->room_number }}</td>
                </tr>
                @if($roomInspection->reservation)
                <tr>
                    <td><strong>Reservasi</strong></td>
                    <td>: {{ $roomInspection->reservation->booking_number }} — {{ $roomInspection->reservation->guest->first_name }} {{ $roomInspection->reservation->guest->last_name }}</td>
                </tr>
                @endif
                <tr>
                    <td><strong>Petugas Housekeeping</strong></td>
                    <td>: {{ $roomInspection->user->name }}</td>
                </tr>
                <tr>
                    <td><strong>Hasil Inspeksi</strong></td>
                    <td>:
                        @php
                            $badge = match($roomInspection->inspection_result) {
                                'Clean_Available' => 'bg-success',
                                'Dirty'           => 'bg-warning',
                                'Damaged'         => 'bg-danger',
                                default           => 'bg-secondary',
                            };
                            $label = match($roomInspection->inspection_result) {
                                'Clean_Available' => 'Bersih & Siap',
                                'Dirty'           => 'Kotor',
                                'Damaged'         => 'Ada Kerusakan',
                                default           => $roomInspection->inspection_result,
                            };
                        @endphp
                        <span class="badge {{ $badge }}">{{ $label }}</span>
                    </td>
                </tr>
                @if($roomInspection->damages_charge > 0)
                <tr>
                    <td><strong>Biaya Kerusakan</strong></td>
                    <td>: <span class="text-danger font-weight-bold">Rp {{ number_format($roomInspection->damages_charge, 0, ',', '.') }}</span></td>
                </tr>
                @endif
                <tr>
                    <td><strong>Catatan Tambahan</strong></td>
                    <td>: {{ $roomInspection->notes ?? 'Tidak ada catatan.' }}</td>
                </tr>
            </table>
        </div>
    </div>
</section>
@endsection
