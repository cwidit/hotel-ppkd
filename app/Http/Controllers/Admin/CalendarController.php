<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Reservation;
use App\Models\ReservationRoom;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index()
    {
        $startDate = Carbon::today();
        $endDate   = Carbon::today()->addDays(29);
        $days      = collect();
        for ($d = $startDate->copy(); $d->lte($endDate); $d->addDay()) {
            $days->push($d->copy());
        }

        $rooms = Room::with('roomType')->orderBy('room_number')->get();

        // Build a lookup: room_id => [date => reservation booking_number]
        $reservationRooms = ReservationRoom::with('reservation')
            ->whereHas('reservation', fn($q) => $q->whereIn('status', ['Confirmed', 'Checked_In']))
            ->get();

        $calendar = [];
        foreach ($reservationRooms as $rr) {
            $res       = $rr->reservation;
            $checkIn   = Carbon::parse($res->check_in_date);
            $checkOut  = Carbon::parse($res->check_out_date);
            $cur       = $checkIn->copy();
            while ($cur->lt($checkOut)) {
                $dateKey = $cur->format('Y-m-d');
                $calendar[$rr->room_id][$dateKey] = [
                    'booking_number' => $res->booking_number,
                    'status'         => $res->status,
                    'reservation_id' => $res->id,
                ];
                $cur->addDay();
            }
        }

        return view('admin.calendar.index', compact('rooms', 'days', 'calendar', 'startDate', 'endDate'));
    }
}
