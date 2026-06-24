<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Reservation;
use App\Models\Payment;
use App\Models\FnbOrder;
use App\Models\LaundryRequest;
use App\Models\RoomInspection;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('Housekeeping')) {
            return $this->housekeepingDashboard();
        }

        if ($user->hasRole('Food & Beverage')) {
            return $this->fnbDashboard();
        }

        if ($user->hasRole('Front Office')) {
            return $this->foDashboard();
        }

        // Administrator (default)
        return $this->adminDashboard();
    }

    private function adminDashboard()
    {
        $totalRooms = Room::count();
        $vacantRooms = Room::where('status', 'Vacant Ready (VR)')->count();
        $occupiedRooms = Room::where('status', 'like', '%Occupied%')->count();
        $dirtyRooms = Room::whereIn('status', ['Vacant Dirty (VD)', 'Occupied Dirty (OD)', 'Make Up Room (MUR)'])->count();

        $todayRevenue = Payment::whereDate('payment_date', today())->where('status', 'Completed')->sum('amount');
        $yesterdayRevenue = Payment::whereDate('payment_date', today()->subDay())
            ->where('status', 'Completed')
            ->sum('amount');
        $revenueTrend = $yesterdayRevenue > 0 ? round((($todayRevenue - $yesterdayRevenue) / $yesterdayRevenue) * 100, 1) : null;

        $pendingFnb = FnbOrder::where('status', 'Pending')->count();
        $pendingLaundry = LaundryRequest::where('status', 'Pending')->count();

        $todayCheckins = Reservation::whereDate('check_in_date', today())->count();
        $todayCheckouts = Reservation::whereDate('check_out_date', today())->count();

        $recentReservations = Reservation::with('guest')->orderBy('created_at', 'desc')->take(5)->get();

        // Calendar Occupancy (Next 14 Days)
        $startDate = today();
        $endDate = today()->addDays(13);
        $dateRange = [];
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dateRange[] = $date->copy();
        }

        // Ambil semua kamar dengan tipe
        $rooms = Room::with('roomType')->orderBy('room_number')->get();

        // Ambil reservasi yang berada dalam rentang tanggal
        $reservations = Reservation::with(['guest', 'reservationRooms'])
            ->whereIn('status', ['Confirmed', 'Checked_In'])
            ->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('check_in_date', [$startDate, $endDate])
                    ->orWhereBetween('check_out_date', [$startDate, $endDate])
                    ->orWhere(function ($q2) use ($startDate, $endDate) {
                        $q2->where('check_in_date', '<=', $startDate)->where('check_out_date', '>=', $endDate);
                    });
            })
            ->get();

        // Map reservasi ke dalam struktur data [room_id][date] = reservation
        $calendarData = [];
        foreach ($rooms as $room) {
            foreach ($dateRange as $date) {
                $calendarData[$room->id][$date->format('Y-m-d')] = null;
            }
        }

        foreach ($reservations as $res) {
            foreach ($res->reservationRooms as $rr) {
                if (isset($calendarData[$rr->room_id])) {
                    $start = Carbon::parse($res->check_in_date)->startOfDay();
                    $end = Carbon::parse($res->check_out_date)->startOfDay();

                    for ($d = $start->copy(); $d->lt($end); $d->addDay()) {
                        $dateStr = $d->format('Y-m-d');
                        if (isset($calendarData[$rr->room_id][$dateStr])) {
                            $calendarData[$rr->room_id][$dateStr] = $res;
                        }
                    }
                }
            }
        }

        $roomStatuses = [
            'Vacant' => Room::where('status', 'like', '%Vacant%')->count(),
            'Occupied' => Room::where('status', 'like', '%Occupied%')->count(),
            'Maintenance' => Room::where('status', 'like', '%Out of%')->count(),
        ];
        $floors = Room::with('roomType')->orderBy('floor')->orderBy('room_number')->get()->groupBy('floor');

        return view('dashboard', compact('totalRooms', 'vacantRooms', 'occupiedRooms', 'dirtyRooms','todayRevenue', 'yesterdayRevenue', 'revenueTrend','pendingFnb', 'pendingLaundry','todayCheckins', 'todayCheckouts','recentReservations', 'rooms', 'dateRange','calendarData', 'roomStatuses','floors'));
    }

    private function foDashboard()
    {
        $vacantRooms = Room::where('status', 'Vacant Ready (VR)')->count();
        $occupiedRooms = Room::where('status', 'like', '%Occupied%')->count();
        $todayCheckins = Reservation::with('guest')->whereDate('check_in_date', today())->get();
        $todayCheckouts = Reservation::with('guest')->whereDate('check_out_date', today())->get();
        $pendingFnb = FnbOrder::where('status', 'Pending')->count();
        $pendingLaundry = LaundryRequest::where('status', 'Pending')->count();

        return view('dashboard.fo', compact('vacantRooms', 'occupiedRooms', 'todayCheckins', 'todayCheckouts', 'pendingFnb', 'pendingLaundry'));
    }

    private function housekeepingDashboard()
    {
        $dirtyStatuses = ['Vacant Dirty (VD)', 'Occupied Dirty (OD)', 'Make Up Room (MUR)', 'Check-Out (CO)'];
        $dirtyRooms = Room::with('roomType')->whereIn('status', $dirtyStatuses)->get();
        $pendingInspections = RoomInspection::whereDate('created_at', today())->count();
        $pendingLaundry = LaundryRequest::where('status', 'Pending')->count();
        $processingLaundry = LaundryRequest::where('status', 'Processing')->count();

        return view('dashboard.hk', compact('dirtyRooms', 'pendingInspections', 'pendingLaundry', 'processingLaundry'));
    }

    private function fnbDashboard()
    {
        $newOrders = FnbOrder::where('status', 'Pending')->with('reservation.guest')->get();
        $processingOrders = FnbOrder::where('status', 'Processing')->with('reservation.guest')->get();
        $deliveredToday = FnbOrder::where('status', 'Delivered')->whereDate('updated_at', today())->count();
        $todayRevenue = FnbOrder::whereDate('order_date', today())->sum('total_order_amount');

        return view('dashboard.fnb', compact('newOrders', 'processingOrders', 'deliveredToday', 'todayRevenue'));
    }
}
