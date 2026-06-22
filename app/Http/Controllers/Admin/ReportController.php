<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Reservation;
use App\Models\Payment;
use App\Models\FnbOrder;
use App\Models\LaundryRequest;
use App\Models\RoomInspection;

class ReportController extends Controller
{
    public function dailyReport(Request $request)
    {
        $date       = $request->input('date', Carbon::today()->format('Y-m-d'));
        $targetDate = Carbon::parse($date);
        $user       = auth()->user();

        $data = ['date' => $date, 'role' => 'Administrator'];

        if ($user->hasRole('Administrator')) {
            $data['revenue']      = Payment::whereDate('payment_date', $targetDate)->where('status', 'Completed')->sum('amount');
            $data['new_bookings'] = Reservation::whereDate('created_at', $targetDate)->count();
            $data['check_ins']    = Reservation::whereDate('check_in_date', $targetDate)->count();
            $data['check_outs']   = Reservation::whereDate('check_out_date', $targetDate)->count();
        }

        if ($user->hasRole('Administrator') || $user->hasRole('Front Office')) {
            $data['fo_role']        = true;
            $data['check_ins_data'] = Reservation::with('guest')->whereDate('check_in_date', $targetDate)->get();
            $data['check_outs_data']= Reservation::with('guest')->whereDate('check_out_date', $targetDate)->get();
        }

        if ($user->hasRole('Administrator') || $user->hasRole('Food & Beverage')) {
            $data['fnb_role']    = true;
            $data['fnb_orders']  = FnbOrder::with('reservation.guest')->whereDate('created_at', $targetDate)->get();
            $data['fnb_revenue'] = $data['fnb_orders']->sum('total_order_amount');
        }

        if ($user->hasRole('Administrator') || $user->hasRole('Housekeeping')) {
            $data['hk_role']          = true;
            $data['room_inspections'] = RoomInspection::with('room')->whereDate('inspection_date', $targetDate)->get();
            $data['laundry_requests'] = LaundryRequest::with('reservation.guest')->whereDate('created_at', $targetDate)->get();
        }

        return view('admin.reports.daily', compact('data'));
    }

    public function advancedReport(Request $request)
    {
        $user = auth()->user();
        $preset = $request->input('preset', 'this_month');
        
        $from = Carbon::today()->startOfMonth();
        $to = Carbon::today()->endOfMonth();

        if ($preset === 'this_week') {
            $from = Carbon::today()->startOfWeek();
            $to = Carbon::today()->endOfWeek();
        } elseif ($preset === 'last_week') {
            $from = Carbon::today()->subWeek()->startOfWeek();
            $to = Carbon::today()->subWeek()->endOfWeek();
        } elseif ($preset === 'last_month') {
            $from = Carbon::today()->subMonth()->startOfMonth();
            $to = Carbon::today()->subMonth()->endOfMonth();
        } elseif ($preset === 'custom') {
            $from = Carbon::parse($request->input('from_date', Carbon::today()->startOfMonth()));
            $to = Carbon::parse($request->input('to_date', Carbon::today()));
        }

        $data = [
            'preset' => $preset,
            'from' => $from->format('Y-m-d'),
            'to' => $to->format('Y-m-d'),
        ];

        // Ensure to includes end of day
        $toEndOfDay = $to->copy()->endOfDay();

        if ($user->hasRole('Administrator')) {
            $data['revenue'] = Payment::whereBetween('payment_date', [$from, $toEndOfDay])->where('status', 'Completed')->sum('amount');
            $data['new_bookings'] = Reservation::whereBetween('created_at', [$from, $toEndOfDay])->count();
            $data['check_ins'] = Reservation::whereBetween('check_in_date', [$from, $toEndOfDay])->count();
            $data['check_outs'] = Reservation::whereBetween('check_out_date', [$from, $toEndOfDay])->count();
        }

        if ($user->hasRole('Administrator') || $user->hasRole('Front Office')) {
            $data['fo_role'] = true;
            $data['reservations'] = Reservation::with('guest')
                ->whereBetween('created_at', [$from, $toEndOfDay])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('admin.reports.advanced', compact('data'));
    }

    public function exportCsv(Request $request)
    {
        $type  = $request->input('type', 'reservations');
        $from  = $request->input('from', Carbon::today()->startOfMonth()->format('Y-m-d'));
        $to    = $request->input('to',   Carbon::today()->format('Y-m-d'));

        $filename = $type . '_' . $from . '_to_' . $to . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = match($type) {
            'payments'   => fn() => $this->streamPayments($from, $to),
            'fnb_orders' => fn() => $this->streamFnbOrders($from, $to),
            default      => fn() => $this->streamReservations($from, $to),
        };

        return response()->stream($callback, 200, $headers);
    }

    private function streamReservations(string $from, string $to): void
    {
        $handle = fopen('php://output', 'w');
        fputcsv($handle, ['No', 'Booking Number', 'Nama Tamu', 'Check-In', 'Check-Out', 'Total Malam', 'Status', 'Payment Status', 'Total Amount', 'Deposit', 'Dibuat Oleh']);

        Reservation::with(['guest', 'user'])
            ->whereBetween('check_in_date', [$from, $to])
            ->orderBy('check_in_date')
            ->chunk(200, function ($rows) use ($handle) {
                static $i = 0;
                foreach ($rows as $r) {
                    $i++;
                    fputcsv($handle, [
                        $i,
                        $r->booking_number,
                        $r->guest->first_name . ' ' . $r->guest->last_name,
                        $r->check_in_date,
                        $r->check_out_date,
                        $r->total_days,
                        $r->status,
                        $r->payment_status,
                        $r->total_amount,
                        $r->deposit_amount,
                        $r->user->name ?? '-',
                    ]);
                }
            });

        fclose($handle);
    }

    private function streamPayments(string $from, string $to): void
    {
        $handle = fopen('php://output', 'w');
        fputcsv($handle, ['No', 'Booking Number', 'Nama Tamu', 'Tanggal Bayar', 'Metode', 'Referensi', 'Jumlah', 'Status']);

        Payment::with(['reservation.guest'])
            ->whereBetween('payment_date', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->orderBy('payment_date')
            ->chunk(200, function ($rows) use ($handle) {
                static $i = 0;
                foreach ($rows as $p) {
                    $i++;
                    fputcsv($handle, [
                        $i,
                        $p->reservation->booking_number ?? '-',
                        optional($p->reservation->guest)->first_name . ' ' . optional($p->reservation->guest)->last_name,
                        $p->payment_date,
                        str_replace('_', ' ', $p->payment_method),
                        $p->reference_number,
                        $p->amount,
                        $p->status,
                    ]);
                }
            });

        fclose($handle);
    }

    private function streamFnbOrders(string $from, string $to): void
    {
        $handle = fopen('php://output', 'w');
        fputcsv($handle, ['No', 'Order ID', 'Booking Number', 'Nama Tamu', 'Tanggal Order', 'Status', 'Total']);

        FnbOrder::with(['reservation.guest'])
            ->whereBetween('order_date', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->orderBy('order_date')
            ->chunk(200, function ($rows) use ($handle) {
                static $i = 0;
                foreach ($rows as $o) {
                    $i++;
                    fputcsv($handle, [
                        $i,
                        $o->id,
                        $o->reservation->booking_number ?? '-',
                        optional($o->reservation->guest)->first_name . ' ' . optional($o->reservation->guest)->last_name,
                        $o->order_date,
                        $o->status,
                        $o->total_order_amount,
                    ]);
                }
            });

        fclose($handle);
    }
}
