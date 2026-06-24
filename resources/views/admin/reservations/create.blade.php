@extends('layouts.admin')

@section('title', 'Create Reservation')

@push('styles')
<style>
    /* Menyembunyikan checkbox asli */
    .room-card-input { display: none; }
    
    /* Styling dasar untuk card kamar */
    .room-card-label { 
        cursor: pointer; 
        transition: all 0.2s ease-in-out; 
        border: 2px solid #e9ecef; 
        border-radius: 8px; 
        background: #f8f9fc;
    }
    
    /* Efek hover saat mouse di atas card */
    .room-card-label:hover { 
        border-color: #babbbc; 
        transform: translateY(-2px); 
    }
    
    /* Efek saat card dipilih (checkbox aktif) */
    .room-card-input:checked + .room-card-label { 
        border-color: #caa53d; /* Warna emas PPKD */
        background-color: #fdfaf0; 
        box-shadow: 0 4px 10px rgba(202, 165, 61, 0.2); 
    }
</style>
@endpush

@section('content')
<div class="page-title mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h3>Create New Reservation</h3>
            <p class="text-subtitle text-muted">Register a new room booking for guests.</p>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('admin.reservations.index') }}" class="btn btn-secondary">
                <i data-feather="arrow-left"></i> Back
            </a>
        </div>
    </div>
</div>

<section class="section">
    <div class="card">
        <div class="card-content">
            <div class="card-body">
                <form action="{{ route('admin.reservations.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        
                        {{-- Guest Type Selection --}}
                        <div class="col-md-12 col-12 mb-3">
                            <div class="form-group">
                                <label>Guest Type <span class="text-danger">*</span></label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="guest_type" id="guest_existing" value="existing" {{ old('guest_type', 'existing') == 'existing' ? 'checked' : '' }} onchange="toggleGuestFields()">
                                        <label class="form-check-label" for="guest_existing">Select from Guest Book</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="guest_type" id="guest_new" value="new" {{ old('guest_type') == 'new' ? 'checked' : '' }} onchange="toggleGuestFields()">
                                        <label class="form-check-label" for="guest_new">Input New Guest</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Existing Guest Dropdown --}}
                        <div class="col-md-12 col-12 mb-3" id="existing_guest_div">
                            <div class="form-group">
                                <label for="guest_id">Select Guest</label>
                                <select id="guest_id" class="form-control @error('guest_id') is-invalid @enderror" name="guest_id">
                                    <option value="">-- Search Guest --</option>
                                    @foreach($guests as $guest)
                                        <option value="{{ $guest->id }}" {{ old('guest_id') == $guest->id ? 'selected' : '' }}>
                                            {{ $guest->identity_number }} - {{ $guest->first_name }} {{ $guest->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('guest_id') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- New Guest Form --}}
                        <div id="new_guest_div" class="col-12" style="display: none;">
                            <div class="row bg-light pt-3 pb-2 mb-3 rounded border">
                                <div class="col-12"><h6 class="mb-3">New Guest Information</h6></div>
                                
                                <div class="col-md-6 mb-2">
                                    <label>ID/Passport Number <span class="text-danger">*</span></label>
                                    <input type="text" name="guest_identity_number" class="form-control" value="{{ old('guest_identity_number') }}">
                                    @error('guest_identity_number') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                
                                <div class="col-md-6 mb-2">
                                    <label>First Name <span class="text-danger">*</span></label>
                                    <input type="text" name="guest_first_name" class="form-control" value="{{ old('guest_first_name') }}">
                                    @error('guest_first_name') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                
                                <div class="col-md-6 mb-2">
                                    <label>Last Name</label>
                                    <input type="text" name="guest_last_name" class="form-control" value="{{ old('guest_last_name') }}">
                                </div>
                                
                                <div class="col-md-6 mb-2">
                                    <label>Email Address</label>
                                    <input type="email" name="guest_email" class="form-control" value="{{ old('guest_email') }}">
                                    @error('guest_email') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                
                                <div class="col-md-6 mb-2">
                                    <label>Phone Number</label>
                                    <input type="text" name="guest_phone_number" class="form-control" value="{{ old('guest_phone_number') }}">
                                </div>
                            </div>
                        </div>

                        {{-- Dates --}}
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="check_in_date">Check-In Date <span class="text-danger">*</span></label>
                                <input type="date" id="check_in_date" class="form-control @error('check_in_date') is-invalid @enderror" name="check_in_date" value="{{ old('check_in_date') }}" required>
                                @error('check_in_date') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="check_out_date">Check-Out Date <span class="text-danger">*</span></label>
                                <input type="date" id="check_out_date" class="form-control @error('check_out_date') is-invalid @enderror" name="check_out_date" value="{{ old('check_out_date') }}" required>
                                @error('check_out_date') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- Room Selection Cards --}}
                        <div class="col-md-12 col-12 mt-3 mb-4">
                            <label class="mb-2">Select Room(s) <span class="text-danger">*</span></label>
                            <div class="row g-3">
                                @foreach($rooms as $room)
                                <div class="col-md-4 mb-3">
                                    
                                    {{-- Hidden Checkbox --}}
                                    <input type="checkbox" name="rooms[]" value="{{ $room->id }}" id="room_{{ $room->id }}" class="room-card-input">
                                    
                                    {{-- Card Label --}}
                                    <label for="room_{{ $room->id }}" class="card h-100 p-3 mb-0 room-card-label shadow-sm">
                                        <div class="font-weight-bold mb-1">
                                            Room {{ $room->room_number }}
                                        </div>
                                        
                                        <div class="mb-2">
                                            <span class="badge badge-primary">
                                                {{ $room->roomType->name ?? '-' }}
                                            </span>
                                            @if($room->roomType->has_breakfast)
                                                <span class="badge badge-success ml-1">Breakfast</span>
                                            @endif
                                        </div>
                                        
                                        <div class="small text-muted">
                                            <i data-feather="users" style="width:12px;height:12px;"></i> Capacity: {{ $room->roomType->capacity ?? '-' }} person(s)
                                        </div>
                                        
                                        <div class="small text-dark font-weight-bold mt-1">
                                            Rp {{ number_format($room->roomType->price_per_night ?? 0, 0, ',', '.') }} / night
                                        </div>
                                        
                                        <div class="small text-muted mt-2">
                                            {{ $room->roomType->description ?? '' }}
                                        </div>
                                    </label>
                                    
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Payment Details --}}
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="payment_method">Payment Method <span class="text-danger">*</span></label>
                                <select id="payment_method" class="form-control @error('payment_method') is-invalid @enderror" name="payment_method" required>
                                    <option value="none" {{ old('payment_method') == 'none' ? 'selected' : '' }}>Unpaid</option>
                                    <option value="Cash" {{ old('payment_method') == 'Cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="Bank_Transfer" {{ old('payment_method') == 'Bank_Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    <option value="QRIS" {{ old('payment_method') == 'QRIS' ? 'selected' : '' }}>QRIS</option>
                                    <option value="Credit_Card" {{ old('payment_method') == 'Credit_Card' ? 'selected' : '' }}>Credit Card</option>
                                </select>
                                <small class="text-muted">Select method if there is an initial payment.</small>
                                @error('payment_method') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="deposit_amount">Amount / Deposit (Rp) <span class="text-danger">*</span></label>
                                <input type="number" id="deposit_amount" class="form-control @error('deposit_amount') is-invalid @enderror" name="deposit_amount" value="{{ old('deposit_amount', 0) }}" min="0" required>
                                <small class="text-muted">Enter 0 if unpaid. Enter nominal amount for DP/Full payment.</small>
                                @error('deposit_amount') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        
                        {{-- Submit Buttons --}}
                        <div class="col-12 d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary mr-2">Create Reservation</button>
                            <button type="reset" class="btn btn-light-secondary">Reset</button>
                        </div>
                        
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Logic untuk memunculkan/menyembunyikan form tamu baru
    function toggleGuestFields() {
        if(document.getElementById('guest_new').checked) {
            document.getElementById('new_guest_div').style.display = 'block';
            document.getElementById('existing_guest_div').style.display = 'none';
        } else {
            document.getElementById('new_guest_div').style.display = 'none';
            document.getElementById('existing_guest_div').style.display = 'block';
        }
    }
    
    // Jalankan fungsi saat halaman pertama kali di-load
    window.onload = toggleGuestFields;
</script>
@endpush