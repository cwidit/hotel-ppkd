<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Reservation;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('reservation')->orderBy('created_at', 'desc')->get();
        return view('admin.payments.index', compact('payments'));
    }

    public function create(Request $request)
    {
        $reservations = Reservation::whereIn('payment_status', ['Unpaid', 'Deposit_Paid', 'Partial'])->get();
        $selectedReservation = null;
        if ($request->has('reservation_id')) {
            $selectedReservation = Reservation::find($request->reservation_id);
        }
        return view('admin.payments.create', compact('reservations', 'selectedReservation'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'reservation_id' => 'required|exists:reservations,id',
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string',
            'reference_number' => 'nullable|string',
            'payment_date' => 'required|date',
        ]);

        $payment = Payment::create([
            'reservation_id' => $validated['reservation_id'],
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'reference_number' => $validated['reference_number'],
            'payment_date' => $validated['payment_date'],
            'status' => 'Completed',
        ]);

        // Update reservation payment status
        $reservation = Reservation::find($validated['reservation_id']);
        $totalPaid = $reservation->payments()->where('status', 'Completed')->sum('amount');
        $totalPaid += $reservation->deposit_amount; // Include deposit

        if ($totalPaid >= $reservation->total_amount) {
            $reservation->update(['payment_status' => 'Paid']);
        } else {
            $reservation->update(['payment_status' => 'Partial']);
        }

        return redirect()->route('admin.payments.index')->with('success', 'Pembayaran berhasil diproses.');
    }

    public function show(Payment $payment)
    {
        $payment->load('reservation.guest');
        return view('admin.payments.show', compact('payment'));
    }

    public function destroy(Payment $payment)
    {
        // Pembayaran tidak boleh dihapus begitu saja biasanya, tapi untuk dummy:
        $payment->delete();
        return redirect()->route('admin.payments.index')->with('success', 'Data pembayaran dihapus.');
    }
}
