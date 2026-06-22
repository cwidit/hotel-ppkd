@extends('layouts.admin')

@section('title', 'Kamar')

@section('content')
<div class="page-title mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h3>Kamar</h3>
            <p class="text-subtitle text-muted">Kelola data kamar hotel beserta statusnya.</p>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('admin.rooms.create') }}" class="btn btn-primary">
                <i data-feather="plus" style="width:15px;height:15px;"></i> Tambah Kamar
            </a>
        </div>
    </div>
</div>

<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Daftar Kamar</span>
            <small class="text-muted">Total: {{ $rooms->count() }} kamar</small>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="table1">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>No Kamar</th>
                            <th>Tipe Kamar</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $statuses = [
                            'Vacant Ready (VR)', 'Vacant Dirty (VD)', 'Vacant Clean (VC)',
                            'Vacant Clean Inspected (VCI)', 'Occupied Clean (OC)', 'Occupied Dirty (OD)',
                            'Occupied No Baggage (ONB)', 'Expected Arrival (EA)', 'Expected Departure (ED)',
                            'Complimentary (Comp)', 'Out of Order (OOO)', 'Out of Service (OOS)',
                            'Do Not Disturb (DND)', 'Sleep Out (SO)', 'Skipper', 'Make Up Room (MUR)',
                            'Turn Down Service (TDS)', 'House Use (HU)', 'Lock Out (LO)',
                            'Late Check Out (LCO)', 'Early Check In (ECI)', 'Extra Bed (EB)',
                            'Incognito', 'VIP', 'No Show (NS)',
                        ];
                        @endphp
                        @forelse($rooms as $i => $room)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td><strong>{{ $room->room_number }}</strong></td>
                            <td>{{ $room->roomType->name ?? '-' }}</td>
                            <td>
                                @php
                                    $rc = match(true) {
                                        str_starts_with($room->status, 'Vacant Ready')   => 'badge-success',
                                        str_starts_with($room->status, 'Vacant Dirty')   => 'badge-warning',
                                        str_starts_with($room->status, 'Vacant Clean')   => 'badge-info',
                                        str_starts_with($room->status, 'Occupied')       => 'badge-primary',
                                        str_starts_with($room->status, 'Out of')         => 'badge-danger',
                                        default                                          => 'badge-secondary',
                                    };
                                @endphp
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge {{ $rc }} mr-2">{{ $room->status }}</span>
                                    <form action="{{ route('admin.rooms.updateStatus', $room->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="form-control form-control-sm" onchange="this.form.submit()" style="max-width:220px;display:inline-block;">
                                            @foreach($statuses as $st)
                                                <option value="{{ $st }}" {{ $room->status === $st ? 'selected' : '' }}>{{ $st }}</option>
                                            @endforeach
                                        </select>
                                    </form>
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('admin.rooms.edit', $room->id) }}" class="btn btn-sm btn-info">Edit</a>
                                <button class="btn btn-sm btn-danger btn-delete"
                                    data-action="{{ route('admin.rooms.destroy', $room->id) }}"
                                    data-label="Kamar {{ $room->room_number }}">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <i data-feather="home" style="width:32px;height:32px;opacity:.3;"></i>
                                <p class="mt-2 mb-0">Belum ada data kamar.</p>
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
