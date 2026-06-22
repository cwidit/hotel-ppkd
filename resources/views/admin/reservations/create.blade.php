@extends('layouts.admin')

@section('title', 'Tambah Reservasi')

@section('content')
<div class="page-title mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h3>Buat Reservasi Baru</h3>
            <p class="text-subtitle text-muted">Daftarkan pemesanan kamar untuk tamu.</p>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('admin.reservations.index') }}" class="btn btn-secondary">
                <i data-feather="arrow-left"></i> Kembali
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
                        <div class="col-md-12 col-12 mb-3">
                            <div class="form-group">
                                <label>Tipe Tamu <span class="text-danger">*</span></label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="guest_type" id="guest_existing" value="existing" {{ old('guest_type', 'existing') == 'existing' ? 'checked' : '' }} onchange="toggleGuestFields()">
                                        <label class="form-check-label" for="guest_existing">Pilih dari Buku Tamu</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="guest_type" id="guest_new" value="new" {{ old('guest_type') == 'new' ? 'checked' : '' }} onchange="toggleGuestFields()">
                                        <label class="form-check-label" for="guest_new">Input Tamu Baru</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 col-12 mb-3" id="existing_guest_div">
                            <div class="form-group">
                                <label for="guest_id">Pilih Tamu</label>
                                <select id="guest_id" class="form-control @error('guest_id') is-invalid @enderror" name="guest_id">
                                    <option value="">-- Cari Tamu --</option>
                                    @foreach($guests as $guest)
                                        <option value="{{ $guest->id }}" {{ old('guest_id') == $guest->id ? 'selected' : '' }}>
                                            {{ $guest->identity_number }} - {{ $guest->first_name }} {{ $guest->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('guest_id') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div id="new_guest_div" class="col-12" style="display: none;">
                            <div class="row bg-light pt-3 pb-2 mb-3 rounded border">
                                <div class="col-12"><h6 class="mb-3">Informasi Tamu Baru</h6></div>
                                <div class="col-md-6 mb-2">
                                    <label>No. Identitas (KTP/Paspor) <span class="text-danger">*</span></label>
                                    <input type="text" name="guest_identity_number" class="form-control" value="{{ old('guest_identity_number') }}">
                                    @error('guest_identity_number') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label>Nama Depan <span class="text-danger">*</span></label>
                                    <input type="text" name="guest_first_name" class="form-control" value="{{ old('guest_first_name') }}">
                                    @error('guest_first_name') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label>Nama Belakang</label>
                                    <input type="text" name="guest_last_name" class="form-control" value="{{ old('guest_last_name') }}">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label>Email</label>
                                    <input type="email" name="guest_email" class="form-control" value="{{ old('guest_email') }}">
                                    @error('guest_email') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label>Nomor HP</label>
                                    <input type="text" name="guest_phone_number" class="form-control" value="{{ old('guest_phone_number') }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="check_in_date">Tanggal Check-In <span class="text-danger">*</span></label>
                                <input type="date" id="check_in_date" class="form-control @error('check_in_date') is-invalid @enderror" name="check_in_date" value="{{ old('check_in_date') }}" required>
                                @error('check_in_date') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="check_out_date">Tanggal Check-Out <span class="text-danger">*</span></label>
                                <input type="date" id="check_out_date" class="form-control @error('check_out_date') is-invalid @enderror" name="check_out_date" value="{{ old('check_out_date') }}" required>
                                @error('check_out_date') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="col-md-12 col-12 mt-3 mb-3">
                            <div class="form-group">
                                <label for="rooms">Pilih Kamar (Tahan tombol CTRL/CMD untuk memilih lebih dari satu) <span class="text-danger">*</span></label>
                                <select id="rooms" class="form-control @error('rooms') is-invalid @enderror" name="rooms[]" multiple required style="height: 150px;">
                                    @foreach($rooms as $room)
                                        <option value="{{ $room->id }}"
                                            data-type="{{ $room->roomType->name ?? '-' }}"
                                            data-price="{{ number_format($room->roomType->price_per_night ?? 0, 0, ',', '.') }}"
                                            data-capacity="{{ $room->roomType->capacity ?? '-' }}"
                                            data-breakfast="{{ $room->roomType->has_breakfast ? 'Ya' : 'Tidak' }}"
                                            data-desc="{{ $room->roomType->description ?? '' }}"
                                            {{ (is_array(old('rooms')) && in_array($room->id, old('rooms'))) ? 'selected' : '' }}>
                                            Kamar {{ $room->room_number }} - {{ $room->roomType->name ?? 'Tipe Kosong' }} (Rp {{ number_format($room->roomType->price_per_night ?? 0, 0, ',', '.') }}/malam)
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Hanya kamar berstatus 'Vacant Ready (VR)' yang ditampilkan.</small>
                                @error('rooms') <br><span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            {{-- Info panel kamar terpilih --}}
                            <div id="room-info-panel" class="mt-2" style="display:none;"></div>
                        </div>

                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="payment_method">Metode Pembayaran</label>
                                <select id="payment_method" class="form-control @error('payment_method') is-invalid @enderror" name="payment_method" required>
                                    <option value="none" {{ old('payment_method') == 'none' ? 'selected' : '' }}>Belum Bayar (Unpaid)</option>
                                    <option value="Cash" {{ old('payment_method') == 'Cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="Bank_Transfer" {{ old('payment_method') == 'Bank_Transfer' ? 'selected' : '' }}>Transfer Bank</option>
                                    <option value="QRIS" {{ old('payment_method') == 'QRIS' ? 'selected' : '' }}>QRIS</option>
                                    <option value="Credit_Card" {{ old('payment_method') == 'Credit_Card' ? 'selected' : '' }}>Kartu Kredit</option>
                                </select>
                                <small class="text-muted">Pilih metode jika ada pembayaran awal.</small>
                                @error('payment_method') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="deposit_amount">Jumlah Uang / Deposit (Rp)</label>
                                <input type="number" id="deposit_amount" class="form-control @error('deposit_amount') is-invalid @enderror" name="deposit_amount" value="{{ old('deposit_amount', 0) }}" min="0" required>
                                <small class="text-muted">Isi 0 jika Belum Bayar. Isi nominal uang jika DP/Lunas.</small>
                                @error('deposit_amount') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        
                        <div class="col-12 d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary mr-1 mb-1">Buat Reservasi</button>
                            <button type="reset" class="btn btn-light-secondary mr-1 mb-1">Reset</button>
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
    function toggleGuestFields() {
        if(document.getElementById('guest_new').checked) {
            document.getElementById('new_guest_div').style.display = 'block';
            document.getElementById('existing_guest_div').style.display = 'none';
        } else {
            document.getElementById('new_guest_div').style.display = 'none';
            document.getElementById('existing_guest_div').style.display = 'block';
        }
    }
    window.onload = toggleGuestFields;

    document.getElementById('rooms').addEventListener('change', function () {
        var panel = document.getElementById('room-info-panel');
        var selected = Array.from(this.selectedOptions);

        if (selected.length === 0) {
            panel.style.display = 'none';
            panel.innerHTML = '';
            return;
        }

        var html = '<div class="row">';
        selected.forEach(function (opt) {
            var desc = opt.dataset.desc;
            html += '<div class="col-md-6 mb-2">'
                  + '<div class="border rounded p-3" style="background:#f8f9fc;">'
                  + '<div class="d-flex align-items-center mb-2">'
                  + '<strong class="mr-2">Kamar ' + opt.text.split(' - ')[0].replace('Kamar ','') + '</strong>'
                  + '<span class="badge badge-primary">' + opt.dataset.type + '</span>'
                  + (opt.dataset.breakfast === 'Ya' ? '<span class="badge badge-success ml-1">Sarapan</span>' : '')
                  + '</div>'
                  + '<div class="d-flex flex-wrap" style="gap:12px;">'
                  + '<small class="text-muted"><i data-feather="users" style="width:12px;height:12px;"></i> Kapasitas: <strong>' + opt.dataset.capacity + ' orang</strong></small>'
                  + '<small class="text-muted"><i data-feather="tag" style="width:12px;height:12px;"></i> <strong>Rp ' + opt.dataset.price + '</strong>/malam</small>'
                  + '<small class="text-muted"><i data-feather="coffee" style="width:12px;height:12px;"></i> Sarapan: <strong>' + opt.dataset.breakfast + '</strong></small>'
                  + '</div>'
                  + (desc ? '<small class="text-muted d-block mt-2"><i data-feather="info" style="width:12px;height:12px;"></i> ' + desc + '</small>' : '')
                  + '</div>'
                  + '</div>';
        });
        html += '</div>';

        panel.innerHTML = html;
        panel.style.display = 'block';
        feather.replace();
    });
</script>
@endpush
