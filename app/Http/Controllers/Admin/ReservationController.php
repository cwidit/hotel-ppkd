<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\ReservationRoom;
use App\Models\Guest;
use App\Models\Room;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with(['guest', 'user'])->orderBy('created_at', 'desc')->get();
        return view('admin.reservations.index', compact('reservations'));
    }

    public function create()
    {
        $guests = Guest::all();
        // Menampilkan semua kamar yang kosong (Vacant) agar lebih fleksibel saat FO membuat booking
        $rooms  = Room::with('roomType')
            ->whereIn('status', [
                'Vacant Ready (VR)',
                'Vacant Dirty (VD)', 
                'Vacant Clean (VC)', 
                'Vacant Clean Inspected (VCI)'
            ])->get();
        return view('admin.reservations.create', compact('guests', 'rooms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'guest_type'            => 'required|in:existing,new',
            'guest_id'              => 'required_if:guest_type,existing|nullable|exists:guests,id',
            'guest_identity_number' => 'required_if:guest_type,new|nullable|unique:guests,identity_number',
            'guest_first_name'      => 'required_if:guest_type,new|nullable|string',
            'guest_last_name'       => 'nullable|string',
            'guest_email'           => 'nullable|email|unique:guests,email',
            'guest_phone_number'    => 'nullable|string',
            'check_in_date'         => 'required|date',
            'check_out_date'        => 'required|date|after:check_in_date',
            'rooms'                 => 'required|array|min:1',
            'rooms.*'               => 'exists:rooms,id',
            'payment_method'        => 'required|string',
            'deposit_amount'        => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Create or get guest
            if ($validated['guest_type'] === 'new') {
                $guest = Guest::create([
                    'identity_number' => $validated['guest_identity_number'],
                    'first_name'      => $validated['guest_first_name'],
                    'last_name'       => $validated['guest_last_name'],
                    'email'           => $validated['guest_email'],
                    'phone'           => $validated['guest_phone_number'],
                ]);
                $guestId = $guest->id;
            } else {
                $guestId = $validated['guest_id'];
            }

            $checkIn   = Carbon::parse($validated['check_in_date']);
            $checkOut  = Carbon::parse($validated['check_out_date']);
            $totalDays = $checkIn->diffInDays($checkOut);

            // Generate booking number: BK-YYYYMMDD-XXXX
            $dateStr    = $checkIn->format('Ymd');
            $dailyCount = Reservation::whereDate('check_in_date', $checkIn->toDateString())->lockForUpdate()->count() + 1;
            $bookingNumber = 'BK-' . $dateStr . '-' . str_pad($dailyCount, 4, '0', STR_PAD_LEFT);

            $reservation = Reservation::create([
                'guest_id'       => $guestId,
                'user_id'        => auth()->id(),
                'booking_number' => $bookingNumber,
                'check_in_date'  => $validated['check_in_date'],
                'check_out_date' => $validated['check_out_date'],
                'total_days'     => $totalDays,
                'status'         => 'Confirmed',
                'deposit_amount' => 0,
                'payment_status' => 'Unpaid',
            ]);

            $totalRoomPrice = 0;

            foreach ($validated['rooms'] as $roomId) {
                $room = Room::with('roomType')->findOrFail($roomId);

                ReservationRoom::create([
                    'reservation_id'  => $reservation->id,
                    'room_id'         => $room->id,
                    'room_type_id'    => $room->room_type_id,
                    'price_at_booking'=> $room->roomType->price_per_night,
                ]);

                $room->update(['status' => 'Expected Arrival (EA)']);
                $totalRoomPrice += ($room->roomType->price_per_night * $totalDays);
            }

            // Ambil dari Settings, jika tidak ada gunakan default 10% dan 5%
            $taxPercent = \App\Models\Setting::getVal('tax_percentage', 10) / 100;
            $servicePercent = \App\Models\Setting::getVal('service_charge_percentage', 5) / 100;

            $tax     = $totalRoomPrice * $taxPercent;
            $service = $totalRoomPrice * $servicePercent;
            $totalAmount = $totalRoomPrice + $tax + $service;

            $inputDeposit  = (float) $validated['deposit_amount'];
            $paymentMethod = $validated['payment_method'];

            if ($paymentMethod === 'none' || $inputDeposit <= 0) {
                $finalPaymentStatus = 'Unpaid';
                $finalDepositAmount = 0;
            } else {
                if ($inputDeposit >= $totalAmount) {
                    $finalPaymentStatus = 'Paid';
                    $finalDepositAmount = $totalAmount;
                } else {
                    $finalPaymentStatus = 'Deposit_Paid';
                    $finalDepositAmount = $inputDeposit;
                }

                \App\Models\Payment::create([
                    'reservation_id'   => $reservation->id,
                    'amount'           => $finalDepositAmount,
                    'payment_date'     => now(),
                    'payment_method'   => $paymentMethod,
                    'reference_number' => 'DEP-' . strtoupper(substr(md5($reservation->id . time()), 0, 8)),
                    'status'           => 'Completed',
                ]);
            }

            $reservation->update([
                'hotel_tax'      => $tax,
                'service_charge' => $service,
                'total_amount'   => $totalAmount,
                'payment_status' => $finalPaymentStatus,
                'deposit_amount' => $finalDepositAmount,
            ]);

            DB::commit();
            return redirect()->route('admin.reservations.index')->with('success', 'Reservasi berhasil dibuat. No. Booking: ' . $bookingNumber);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Reservation $reservation)
    {
        $reservation->load([
            'guest',
            'user',
            'reservationRooms.room',
            'reservationRooms.roomType',
            'extraCharges',
            'payments',
            'fnbOrders.items.fnbMenu',
            'laundryRequests.laundryService',
        ]);
        return view('admin.reservations.show', compact('reservation'));
    }

    public function update(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'status'         => 'required|in:Confirmed,Checked_In,Checked_Out,Canceled',
            'payment_status' => 'required|in:Unpaid,Deposit_Paid,Paid,Partial',
        ]);

        if ($validated['status'] === 'Checked_In' && $validated['payment_status'] !== 'Paid') {
            return redirect()->back()->with('error', 'Tamu tidak dapat Check-In jika pembayaran belum Lunas (Paid Fully).');
        }

        $oldStatus = $reservation->status;

        $reservation->update($validated);

        if ($validated['status'] === 'Checked_In' && $oldStatus !== 'Checked_In') {
            foreach ($reservation->reservationRooms as $rr) {
                $rr->room->update(['status' => 'Occupied Clean (OC)']);
            }
        } elseif ($validated['status'] === 'Checked_Out' && $oldStatus !== 'Checked_Out') {
            foreach ($reservation->reservationRooms as $rr) {
                $rr->room->update(['status' => 'Vacant Dirty (VD)']);
            }
        } elseif ($validated['status'] === 'Canceled' && $oldStatus !== 'Canceled') {
            foreach ($reservation->reservationRooms as $rr) {
                $rr->room->update(['status' => 'Vacant Ready (VR)']);
            }
        }

        return redirect()->back()->with('success', 'Status reservasi berhasil diupdate.');
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        return redirect()->route('admin.reservations.index')->with('success', 'Reservasi berhasil dihapus.');
    }

    public function print(Reservation $reservation)
    {
        $reservation->load([
            'guest',
            'reservationRooms.room',
            'reservationRooms.roomType',
            'extraCharges',
            'payments',
        ]);
        return view('admin.reservations.print', compact('reservation'));
    }
}
