@extends('layouts.admin')

@section('title', 'Inspeksi Kamar')

@section('content')
<div class="page-title mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h3>Inspeksi Kamar (Housekeeping)</h3>
            <p class="text-subtitle text-muted">Pencatatan pembersihan dan inspeksi kamar.</p>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('admin.room-inspections.create') }}" class="btn btn-primary">
                <i data-feather="plus" style="width:15px;height:15px;"></i> Catat Inspeksi Baru
            </a>
        </div>
    </div>
</div>

<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Histori Inspeksi</span>
            <small class="text-muted">Total: {{ $inspections->count() }} inspeksi</small>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="table1">
                    <thead>
                        <tr>
                            <th>Waktu Inspeksi</th>
                            <th>No. Kamar</th>
                            <th>Reservasi</th>
                            <th>Petugas (HK)</th>
                            <th>Hasil Inspeksi</th>
                            <th>Biaya Kerusakan</th>
                            <th>Catatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inspections as $ins)
                        <tr>
                            <td><small>{{ \Carbon\Carbon::parse($ins->inspection_date)->format('d M Y H:i') }}</small></td>
                            <td><strong>{{ $ins->room->room_number }}</strong></td>
                            <td>
                                @if($ins->reservation)
                                    <a href="{{ route('admin.reservations.show', $ins->reservation_id) }}" class="text-primary">
                                        {{ $ins->reservation->booking_number }}
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $ins->user->name }}</td>
                            <td>
                                @php
                                    $badge = match($ins->inspection_result) {
                                        'Clean_Available' => 'badge-success',
                                        'Dirty'           => 'badge-warning',
                                        'Damaged'         => 'badge-danger',
                                        default           => 'badge-secondary',
                                    };
                                    $label = match($ins->inspection_result) {
                                        'Clean_Available' => 'Bersih & Siap',
                                        'Dirty'           => 'Kotor',
                                        'Damaged'         => 'Ada Kerusakan',
                                        default           => $ins->inspection_result,
                                    };
                                @endphp
                                <span class="badge {{ $badge }}">{{ $label }}</span>
                            </td>
                            <td>
                                @if($ins->damages_charge > 0)
                                    <span class="text-danger font-weight-bold">Rp {{ number_format($ins->damages_charge, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td><small class="text-muted">{{ Str::limit($ins->notes ?? '-', 30) }}</small></td>
                            <td>
                                <a href="{{ route('admin.room-inspections.show', $ins->id) }}" class="btn btn-sm btn-info">Detail</a>
                                <button class="btn btn-sm btn-danger btn-delete"
                                    data-action="{{ route('admin.room-inspections.destroy', $ins->id) }}"
                                    data-label="Inspeksi Kamar {{ $ins->room->room_number }}">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i data-feather="check-square" style="width:32px;height:32px;opacity:.3;"></i>
                                <p class="mt-2 mb-0">Belum ada data inspeksi kamar.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

@include('components.delete-modal')
@endsection
