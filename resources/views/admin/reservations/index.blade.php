
@section('title', 'Reservations')

<div class="page-title mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h3>Reservations & Guest Registration</h3>
            <p class="text-subtitle text-muted">Manage hotel room reservations and guest records.</p>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('admin.reservations.create') }}" class="btn btn-primary">
                <i data-feather="plus" style="width:15px;height:15px;"></i> New Reservation
            </a>
        </div>
    </div>
</div>

<div class="card-header d-flex justify-content-between align-items-center">
    <span>Reservation Records</span>
    <small class="text-muted">Total: {{ $reservations->count() }} records</small>
</div>

<thead>
    <tr>
        <th>Booking No.</th>
        <th>Guest</th>
        <th>Check-In</th>
        <th>Check-Out</th>
        <th>Total Amount</th>
        <th>Status</th>
        <th>Payment</th>
        <th>Actions</th>
    </tr>
</thead>

<td>
    <a href="{{ route('admin.reservations.show', $res->id) }}" class="btn btn-sm btn-primary">
        Details
    </a>

```
<button class="btn btn-sm btn-danger btn-delete"
    data-action="{{ route('admin.reservations.destroy', $res->id) }}"
    data-label="Reservation {{ $res->booking_number }}">
    Delete
</button>
```

</td>

<td colspan="8" class="text-center py-4 text-muted">
    <i data-feather="inbox" style="width:32px;height:32px;opacity:.3;"></i>
    <p class="mt-2 mb-0">No reservation records found.</p>
</td>
