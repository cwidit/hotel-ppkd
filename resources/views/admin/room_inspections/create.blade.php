@extends('layouts.admin')

@section('title', 'Catat Inspeksi Kamar')

@section('content')
<div class="page-title mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h3>Catat Inspeksi Baru</h3>
            <p class="text-subtitle text-muted">Perbarui status kamar setelah dibersihkan atau diperiksa.</p>
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
        <div class="card-content">
            <div class="card-body">
                <form action="{{ route('admin.room-inspections.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 col-12 mb-3">
                            <div class="form-group">
                                <label for="room_id">Pilih Kamar (Kotor / Perlu Dibersihkan)</label>
                                <select id="room_id" class="form-control @error('room_id') is-invalid @enderror" name="room_id" required>
                                    <option value="">-- Pilih Kamar --</option>
                                    @foreach($rooms as $room)
                                        <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>
                                            Kamar {{ $room->room_number }} — {{ $room->roomType->name ?? '-' }} ({{ $room->status }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('room_id') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="col-md-6 col-12 mb-3">
                            <div class="form-group">
                                <label for="reservation_id">Reservasi Terkait <small class="text-muted">(opsional, diperlukan jika ada kerusakan)</small></label>
                                <select id="reservation_id" class="form-control @error('reservation_id') is-invalid @enderror" name="reservation_id">
                                    <option value="">-- Tidak ada / Pilih Reservasi --</option>
                                    @foreach(\App\Models\Reservation::with('guest')->whereIn('status', ['Checked_In','Checked_Out'])->get() as $res)
                                        <option value="{{ $res->id }}" {{ old('reservation_id') == $res->id ? 'selected' : '' }}>
                                            {{ $res->booking_number }} — {{ $res->guest->first_name }} {{ $res->guest->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('reservation_id') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="col-md-6 col-12 mb-3">
                            <div class="form-group">
                                <label for="inspection_result">Hasil Inspeksi</label>
                                <select id="inspection_result" class="form-control @error('inspection_result') is-invalid @enderror" name="inspection_result" required>
                                    <option value="Clean_Available" {{ old('inspection_result', 'Clean_Available') == 'Clean_Available' ? 'selected' : '' }}>
                                        Bersih & Siap (Vacant Ready)
                                    </option>
                                    <option value="Dirty" {{ old('inspection_result') == 'Dirty' ? 'selected' : '' }}>
                                        Masih Kotor (Vacant Dirty)
                                    </option>
                                    <option value="Damaged" {{ old('inspection_result') == 'Damaged' ? 'selected' : '' }}>
                                        Ada Kerusakan (Out of Order)
                                    </option>
                                </select>
                                <small class="text-muted">Status kamar akan otomatis diperbarui sesuai hasil ini.</small>
                                @error('inspection_result') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="col-md-6 col-12 mb-3" id="damages-charge-section" style="display:none;">
                            <div class="form-group">
                                <label for="damages_charge">Biaya Kerusakan (Rp)</label>
                                <input type="number" id="damages_charge" class="form-control @error('damages_charge') is-invalid @enderror"
                                    name="damages_charge" min="0" step="1000" value="{{ old('damages_charge', 0) }}">
                                <small class="text-muted">Akan otomatis ditambahkan ke extra charge reservasi terkait.</small>
                                @error('damages_charge') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="col-md-12 col-12">
                            <div class="form-group">
                                <label for="notes">Catatan Tambahan (Opsional)</label>
                                <textarea id="notes" class="form-control @error('notes') is-invalid @enderror"
                                    name="notes" rows="3"
                                    placeholder="Misal: Kunci pintu macet, handuk kurang 1, kaca retak.">{{ old('notes') }}</textarea>
                                @error('notes') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="col-12 d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary mr-1 mb-1">Simpan Inspeksi</button>
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
    const resultSelect = document.getElementById('inspection_result');
    const damagesSection = document.getElementById('damages-charge-section');

    function toggleDamages() {
        damagesSection.style.display = resultSelect.value === 'Damaged' ? 'block' : 'none';
    }

    resultSelect.addEventListener('change', toggleDamages);
    toggleDamages();
</script>
@endpush
