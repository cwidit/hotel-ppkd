@extends('layouts.admin')

@section('title', 'Housekeeping Board')

@section('content')
<div class="page-title mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h3>Housekeeping Board</h3>
            <p class="text-subtitle text-muted">Kelola status kebersihan kamar hotel.</p>
        </div>
    </div>
</div>

<section class="section">
    {{-- Stats --}}
    <div class="row mb-4">
        <div class="col-6 col-md-3">
            <div class="card text-center" style="border-left: 4px solid #dc3545;">
                <div class="card-body py-3">
                    <h2 class="text-danger mb-0">{{ $stats['dirty'] }}</h2>
                    <small class="text-muted">Perlu Dibersihkan</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card text-center" style="border-left: 4px solid #28a745;">
                <div class="card-body py-3">
                    <h2 class="text-success mb-0">{{ $stats['available'] }}</h2>
                    <small class="text-muted">Available (VR)</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card text-center" style="border-left: 4px solid #007bff;">
                <div class="card-body py-3">
                    <h2 class="text-primary mb-0">{{ $stats['occupied'] }}</h2>
                    <small class="text-muted">Occupied</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card text-center" style="border-left: 4px solid #6c757d;">
                <div class="card-body py-3">
                    <h2 class="text-secondary mb-0">{{ $stats['ooo'] }}</h2>
                    <small class="text-muted">Out of Order</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Dirty Rooms --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">
                <i data-feather="alert-circle" class="text-danger" style="width:18px;height:18px;"></i>
                Kamar yang Perlu Dibersihkan ({{ $dirtyRooms->count() }})
            </h4>
        </div>
        <div class="card-body">
            @if($dirtyRooms->isEmpty())
                <div class="text-center py-4 text-muted">
                    <i data-feather="check-circle" style="width:48px;height:48px;color:#28a745;"></i>
                    <p class="mt-2">Semua kamar sudah bersih!</p>
                </div>
            @else
                <div class="row">
                    @foreach($dirtyRooms as $room)
                    <div class="col-md-3 col-sm-4 col-6 mb-3">
                        <div class="card border h-100" style="border-color: #ffc107 !important;">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="mb-0 font-weight-bold">{{ $room->room_number }}</h5>
                                    @php
                                        $statusColor = str_contains($room->status, 'Dirty') ? 'warning' : 'secondary';
                                    @endphp
                                    <span class="badge badge-{{ $statusColor }}" style="font-size:9px;">
                                        {{ explode(' (', $room->status)[0] }}
                                    </span>
                                </div>
                                <p class="text-muted mb-3" style="font-size:11px;">{{ $room->roomType->name ?? '-' }}</p>

                                <form action="{{ route('admin.housekeeping.markClean', $room->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="new_status" value="Vacant Clean (VC)">
                                    <button type="submit" class="btn btn-sm btn-block btn-outline-success">
                                        <i data-feather="check" style="width:12px;height:12px;"></i> Tandai Bersih
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- All Rooms Status Overview --}}
    <div class="card mt-3">
        <div class="card-header">
            <h4 class="mb-0">Status Semua Kamar</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-sm" id="table1">
                    <thead>
                        <tr>
                            <th>No. Kamar</th>
                            <th>Tipe</th>
                            <th>Status Sekarang</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allRooms as $room)
                        @php
                            $sc = 'secondary';
                            if(str_contains($room->status, 'Ready') || str_contains($room->status, 'Vacant Clean Inspected')) $sc = 'success';
                            elseif(str_contains($room->status, 'Dirty') || str_contains($room->status, 'Make Up')) $sc = 'warning';
                            elseif(str_contains($room->status, 'Occupied')) $sc = 'primary';
                            elseif(str_contains($room->status, 'Out of')) $sc = 'danger';
                            elseif(str_contains($room->status, 'Expected') || str_contains($room->status, 'Vacant Clean')) $sc = 'info';
                        @endphp
                        <tr>
                            <td><strong>{{ $room->room_number }}</strong></td>
                            <td>{{ $room->roomType->name ?? '-' }}</td>
                            <td><span class="badge badge-{{ $sc }}">{{ $room->status }}</span></td>
                            <td>
                                @if(in_array($room->status, ['Vacant Dirty (VD)', 'Occupied Dirty (OD)', 'Make Up Room (MUR)', 'Check-Out (CO)']))
                                    <form action="{{ route('admin.housekeeping.markClean', $room->id) }}" method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="new_status" value="Vacant Clean (VC)">
                                        <button class="btn btn-xs btn-success" style="padding:2px 8px;font-size:11px;">Bersihkan</button>
                                    </form>
                                @else
                                    <span class="text-muted" style="font-size:11px;">—</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
