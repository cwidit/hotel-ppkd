@extends('layouts.admin')

@section('title', 'Buat Pesanan FnB')

@section('content')
<div class="page-title mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h3>Buat Pesanan FnB</h3>
            <p class="text-subtitle text-muted">Input pesanan makanan/minuman tamu (Room Service).</p>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('admin.fnb-orders.index') }}" class="btn btn-secondary">
                <i data-feather="arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

<section class="section">
    <div class="card">
        <div class="card-content">
            <div class="card-body">
                <form action="{{ route('admin.fnb-orders.store') }}" method="POST" id="order-form">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 col-12 mb-3">
                            <div class="form-group">
                                <label for="reservation_id">Pilih Reservasi (Tamu Check-In)</label>
                                <select id="reservation_id" class="form-control @error('reservation_id') is-invalid @enderror" name="reservation_id" required>
                                    <option value="">-- Pilih Tamu --</option>
                                    @foreach($reservations as $res)
                                        <option value="{{ $res->id }}" {{ old('reservation_id') == $res->id ? 'selected' : '' }}>
                                            {{ $res->booking_number }} - {{ $res->guest->first_name }} {{ $res->guest->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('reservation_id') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <h5 class="mt-3">Daftar Pesanan Menu</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="menu-table">
                            <thead>
                                <tr>
                                    <th>Menu</th>
                                    <th width="150px">Kuantitas</th>
                                    <th width="100px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="menu-container">
                                <tr class="menu-item">
                                    <td>
                                        <select class="form-control" name="menu_id[]" required>
                                            <option value="">-- Pilih Menu --</option>
                                            @foreach($menus as $menu)
                                                <option value="{{ $menu->id }}">{{ $menu->name }} (Rp {{ number_format($menu->price, 0, ',', '.') }})</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" name="quantity[]" min="1" value="1" required>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-remove-menu"><i data-feather="trash"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <button type="button" class="btn btn-sm btn-success" id="btn-add-menu">
                        <i data-feather="plus"></i> Tambah Menu Lain
                    </button>

                    <div class="col-12 d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary mr-1 mb-1">Kirim Pesanan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnAdd = document.getElementById('btn-add-menu');
    const container = document.getElementById('menu-container');

    btnAdd.addEventListener('click', function() {
        const row = document.querySelector('.menu-item').cloneNode(true);
        row.querySelector('input').value = 1;
        container.appendChild(row);
        feather.replace();
    });

    container.addEventListener('click', function(e) {
        if(e.target.closest('.btn-remove-menu')) {
            if(container.querySelectorAll('.menu-item').length > 1) {
                e.target.closest('.menu-item').remove();
            } else {
                alert('Minimal satu menu harus dipilih.');
            }
        }
    });
});
</script>
@endsection
