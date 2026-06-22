<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $reservation->booking_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Arial', sans-serif; font-size: 13px; color: #333; background: #fff; }

        .invoice-wrapper { max-width: 780px; margin: 0 auto; padding: 30px; }

        .header { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 3px solid #1d7af3; padding-bottom: 20px; margin-bottom: 20px; }
        .hotel-name { font-size: 22px; font-weight: bold; color: #1d7af3; }
        .hotel-meta { font-size: 11px; color: #666; margin-top: 4px; }
        .invoice-title { text-align: right; }
        .invoice-title h2 { font-size: 28px; color: #1d7af3; letter-spacing: 2px; }
        .invoice-title .booking-no { font-size: 13px; color: #555; margin-top: 4px; }

        .info-section { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .info-box { width: 48%; }
        .info-box h4 { font-size: 11px; text-transform: uppercase; color: #999; letter-spacing: 1px; margin-bottom: 8px; border-bottom: 1px solid #eee; padding-bottom: 4px; }
        .info-box p { margin: 3px 0; font-size: 12px; }
        .info-box strong { color: #333; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        table th { background: #f5f7fa; color: #555; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; padding: 8px 10px; text-align: left; border-bottom: 2px solid #ddd; }
        table td { padding: 8px 10px; border-bottom: 1px solid #f0f0f0; font-size: 12px; }
        table td.right, table th.right { text-align: right; }

        .section-title { font-size: 12px; font-weight: bold; color: #1d7af3; text-transform: uppercase; letter-spacing: 1px; margin: 16px 0 8px; }

        .summary-table { margin-left: auto; width: 320px; }
        .summary-table td { padding: 6px 10px; }
        .summary-table .total-row td { background: #1d7af3; color: #fff; font-weight: bold; font-size: 14px; }
        .summary-table .paid-row td { color: #28a745; }
        .summary-table .remaining-row td { font-weight: bold; color: #dc3545; }
        .summary-table .settled-row td { font-weight: bold; color: #28a745; }

        .footer { margin-top: 30px; padding-top: 16px; border-top: 1px solid #eee; display: flex; justify-content: space-between; font-size: 11px; color: #888; }
        .signature-box { text-align: center; }
        .signature-box .line { border-bottom: 1px solid #333; width: 160px; margin: 40px auto 6px; }

        .status-badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 10px; font-weight: bold; }
        .badge-paid { background: #d4edda; color: #155724; }
        .badge-unpaid { background: #f8d7da; color: #721c24; }
        .badge-partial { background: #fff3cd; color: #856404; }

        .no-print { display: block; }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; }
            .invoice-wrapper { padding: 15px; }
        }
    </style>
</head>
<body>

<div class="no-print" style="background:#f5f7fa;padding:12px 30px;border-bottom:1px solid #ddd;display:flex;align-items:center;gap:12px;">
    <button onclick="window.print()" style="background:#1d7af3;color:#fff;border:none;padding:8px 20px;border-radius:5px;cursor:pointer;font-size:13px;">
        🖨️ Cetak Invoice
    </button>
    <a href="{{ route('admin.reservations.show', $reservation->id) }}" style="color:#555;font-size:12px;text-decoration:none;">← Kembali ke Detail Reservasi</a>
</div>

<div class="invoice-wrapper">
    {{-- Header --}}
    <div class="header">
        <div>
            <div class="hotel-name">PPKD HOTEL</div>
            <div class="hotel-meta">Jl. Raya Hotel No. 1, Jakarta<br>Telp: (021) 123-4567 | Email: info@ppkdhotel.com</div>
        </div>
        <div class="invoice-title">
            <h2>INVOICE</h2>
            <div class="booking-no">{{ $reservation->booking_number }}</div>
            <div class="booking-no">Tercetak: {{ now()->format('d M Y, H:i') }}</div>
        </div>
    </div>

    {{-- Guest & Booking Info --}}
    <div class="info-section">
        <div class="info-box">
            <h4>Informasi Tamu</h4>
            <p><strong>{{ $reservation->guest->first_name }} {{ $reservation->guest->last_name }}</strong></p>
            <p>No. Identitas: {{ $reservation->guest->identity_number }}</p>
            <p>Telepon: {{ $reservation->guest->phone ?? '-' }}</p>
            <p>Email: {{ $reservation->guest->email ?? '-' }}</p>
        </div>
        <div class="info-box">
            <h4>Detail Pemesanan</h4>
            <p><strong>No. Booking:</strong> {{ $reservation->booking_number }}</p>
            <p><strong>Check-In:</strong> {{ \Carbon\Carbon::parse($reservation->check_in_date)->format('d M Y') }}</p>
            <p><strong>Check-Out:</strong> {{ \Carbon\Carbon::parse($reservation->check_out_date)->format('d M Y') }}</p>
            <p><strong>Lama Menginap:</strong> {{ $reservation->total_days }} Malam</p>
            <p><strong>Status:</strong>
                @php
                    $ps = $reservation->payment_status;
                    $badgeClass = $ps === 'Paid' ? 'badge-paid' : ($ps === 'Unpaid' ? 'badge-unpaid' : 'badge-partial');
                @endphp
                <span class="status-badge {{ $badgeClass }}">{{ str_replace('_', ' ', $ps) }}</span>
            </p>
        </div>
    </div>

    {{-- Room Charges --}}
    <div class="section-title">Biaya Kamar</div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>No. Kamar</th>
                <th>Tipe Kamar</th>
                <th class="right">Harga / Malam</th>
                <th class="right">Subtotal ({{ $reservation->total_days }} malam)</th>
            </tr>
        </thead>
        <tbody>
            @php $roomTotal = 0; @endphp
            @foreach($reservation->reservationRooms as $i => $rr)
                @php $sub = $rr->price_at_booking * $reservation->total_days; $roomTotal += $sub; @endphp
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $rr->room->room_number }}</td>
                    <td>{{ $rr->roomType->name }}</td>
                    <td class="right">Rp {{ number_format($rr->price_at_booking, 0, ',', '.') }}</td>
                    <td class="right">Rp {{ number_format($sub, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Extra Charges --}}
    @php $extraTotal = $reservation->extraCharges->sum('amount'); @endphp
    @if($reservation->extraCharges->isNotEmpty())
    <div class="section-title">Biaya Tambahan</div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Jenis</th>
                <th>Keterangan</th>
                <th class="right">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reservation->extraCharges as $i => $ec)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $ec->charge_type }}</td>
                <td>{{ $ec->name }}</td>
                <td class="right">Rp {{ number_format($ec->amount, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    {{-- Summary --}}
    @php
        $grandTotal  = $roomTotal + $reservation->hotel_tax + $reservation->service_charge + $extraTotal;
        $totalPaid   = $reservation->payments->sum('amount');
        $remaining   = $grandTotal - $totalPaid;
    @endphp
    <table class="summary-table">
        <tr>
            <td>Total Kamar</td>
            <td class="right">Rp {{ number_format($roomTotal, 0, ',', '.') }}</td>
        </tr>
        @if($extraTotal > 0)
        <tr>
            <td>Extra Charges</td>
            <td class="right">Rp {{ number_format($extraTotal, 0, ',', '.') }}</td>
        </tr>
        @endif
        <tr>
            <td>Pajak Hotel (10%)</td>
            <td class="right">Rp {{ number_format($reservation->hotel_tax, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Service Charge (5%)</td>
            <td class="right">Rp {{ number_format($reservation->service_charge, 0, ',', '.') }}</td>
        </tr>
        <tr class="total-row">
            <td>GRAND TOTAL</td>
            <td class="right">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
        </tr>
        <tr class="paid-row">
            <td>Total Dibayar</td>
            <td class="right">- Rp {{ number_format($totalPaid, 0, ',', '.') }}</td>
        </tr>
        @if($remaining > 0)
        <tr class="remaining-row">
            <td>Sisa Tagihan</td>
            <td class="right">Rp {{ number_format($remaining, 0, ',', '.') }}</td>
        </tr>
        @else
        <tr class="settled-row">
            <td>Status</td>
            <td class="right">LUNAS ✓</td>
        </tr>
        @endif
    </table>

    {{-- Footer --}}
    <div class="footer">
        <div>
            <p>Terima kasih telah menginap di PPKD Hotel.</p>
            <p>Dokumen ini dicetak secara otomatis oleh sistem HMS.</p>
        </div>
        <div class="signature-box">
            <div class="line"></div>
            <p>Petugas Front Office</p>
        </div>
    </div>
</div>

</body>
</html>
