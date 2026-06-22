@extends('layouts.admin')

@section('title', 'Proses Pembayaran')

@section('content')
<div class="page-title mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h3>Proses Pembayaran Baru</h3>
            <p class="text-subtitle text-muted">Catat pembayaran reservasi hotel.</p>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary">
                <i data-feather="arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

<section class="section">
    <div class="card">
        <div class="card-content">
            <div class="card-body">
                <form action="{{ route('admin.payments.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 col-12 mb-3">
                            <div class="form-group">
                                <label for="reservation_id">Pilih Reservasi (Belum Lunas)</label>
                                <select id="reservation_id" class="form-control @error('reservation_id') is-invalid @enderror" name="reservation_id" required>
                                    <option value="">-- Pilih Reservasi --</option>
                                    @foreach($reservations as $res)
                                        @php
                                            $totalPaid = $res->deposit_amount + $res->payments()->where('status', 'Completed')->sum('amount');
                                            $sisa = $res->total_amount - $totalPaid;
                                        @endphp
                                        <option value="{{ $res->id }}" data-sisa="{{ $sisa }}" {{ (old('reservation_id') == $res->id || ($selectedReservation && $selectedReservation->id == $res->id)) ? 'selected' : '' }}>
                                            {{ $res->booking_number }} - {{ $res->guest->first_name }} (Tagihan: Rp {{ number_format($res->total_amount, 0, ',', '.') }}, Sisa: Rp {{ number_format($sisa, 0, ',', '.') }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('reservation_id') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="payment_date">Tanggal Pembayaran</label>
                                <input type="datetime-local" id="payment_date" class="form-control @error('payment_date') is-invalid @enderror" name="payment_date" value="{{ old('payment_date', now()->format('Y-m-d\TH:i')) }}" required>
                                @error('payment_date') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="payment_method">Metode Pembayaran</label>
                                <select id="payment_method" class="form-control @error('payment_method') is-invalid @enderror" name="payment_method" required>
                                    <option value="Cash" {{ old('payment_method') == 'Cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="Credit Card" {{ old('payment_method') == 'Credit Card' ? 'selected' : '' }}>Credit Card</option>
                                    <option value="Debit Card" {{ old('payment_method') == 'Debit Card' ? 'selected' : '' }}>Debit Card</option>
                                    <option value="Bank Transfer" {{ old('payment_method') == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    <option value="E-Wallet" {{ old('payment_method') == 'E-Wallet' ? 'selected' : '' }}>E-Wallet</option>
                                </select>
                                @error('payment_method') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="amount" class="d-flex justify-content-between align-items-center">
                                    <span>Nominal Pembayaran (Rp)</span>
                                    <div class="form-check form-check-inline m-0">
                                        <input class="form-check-input" type="checkbox" id="full_payment_check" onchange="autoFillAmount()">
                                        <label class="form-check-label" for="full_payment_check">Lunas (Auto Isi Sisa)</label>
                                    </div>
                                </label>
                                <input type="number" id="amount" class="form-control @error('amount') is-invalid @enderror" name="amount" value="{{ old('amount') }}" required>
                                @error('amount') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="reference_number">No Referensi / Transaksi (Opsional)</label>
                                <input type="text" id="reference_number" class="form-control @error('reference_number') is-invalid @enderror" name="reference_number" value="{{ old('reference_number') }}">
                                @error('reference_number') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="col-12 d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary mr-1 mb-1">Proses Pembayaran</button>
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
    function autoFillAmount() {
        var resSelect = document.getElementById('reservation_id');
        var amountInput = document.getElementById('amount');
        var isChecked = document.getElementById('full_payment_check').checked;

        if (isChecked && resSelect.selectedIndex > 0) {
            var selectedOption = resSelect.options[resSelect.selectedIndex];
            var sisa = selectedOption.getAttribute('data-sisa');
            if (sisa) {
                amountInput.value = sisa;
            }
        } else if (!isChecked) {
            amountInput.value = '';
        }
    }

    // Juga jalankan saat dropdown berubah
    document.getElementById('reservation_id').addEventListener('change', function() {
        if (document.getElementById('full_payment_check').checked) {
            autoFillAmount();
        }
    });
</script>
@endpush
