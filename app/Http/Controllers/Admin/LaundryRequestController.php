<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LaundryRequest;
use App\Models\LaundryService;
use App\Models\Reservation;
use App\Models\ExtraCharge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaundryRequestController extends Controller
{
    public function index()
    {
        $requests = LaundryRequest::with(['reservation.guest', 'laundryService'])->orderBy('created_at', 'desc')->get();
        return view('admin.laundry_requests.index', compact('requests'));
    }

    public function create()
    {
        $reservations = Reservation::with('guest')->where('status', 'Checked_In')->get();
        $services = LaundryService::all();
        return view('admin.laundry_requests.create', compact('reservations', 'services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'reservation_id'    => 'required|exists:reservations,id',
            'laundry_service_id'=> 'required|exists:laundry_services,id',
            'quantity'          => 'required|integer|min:1',
            'notes'             => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $service     = LaundryService::findOrFail($validated['laundry_service_id']);
            $totalAmount = $service->price * $validated['quantity'];

            $laundryReq = LaundryRequest::create([
                'reservation_id'    => $validated['reservation_id'],
                'user_id_fo'        => auth()->id(),
                'laundry_service_id'=> $validated['laundry_service_id'],
                'quantity'          => $validated['quantity'],
                'notes'             => $validated['notes'],
                'request_date'      => now(),
                'status'            => 'Pending',
                'total_laundry_amount' => $totalAmount,
            ]);

            ExtraCharge::create([
                'reservation_id' => $validated['reservation_id'],
                'user_id'        => auth()->id(),
                'charge_type'    => 'Laundry',
                'name'           => 'Laundry: ' . $service->name . ' (x' . $validated['quantity'] . ')',
                'amount'         => $totalAmount,
            ]);

            DB::commit();
            return redirect()->route('admin.laundry-requests.index')->with('success', 'Request Laundry berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat request: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(LaundryRequest $laundryRequest)
    {
        return view('admin.laundry_requests.edit', compact('laundryRequest'));
    }

    public function update(Request $request, LaundryRequest $laundryRequest)
    {
        $validated = $request->validate([
            'status' => 'required|in:Pending,Processing,Delivered,Completed,Canceled',
        ]);

        // Bila di-cancel, update juga HK user jika ada
        if ($validated['status'] === 'Processing') {
            $laundryRequest->update([
                'status'      => 'Processing',
                'user_id_hk'  => auth()->id(),
            ]);
        } else {
            $laundryRequest->update($validated);
        }

        return redirect()->route('admin.laundry-requests.index')->with('success', 'Status Laundry berhasil diperbarui.');
    }

    public function destroy(LaundryRequest $laundryRequest)
    {
        $laundryRequest->delete();
        return redirect()->route('admin.laundry-requests.index')->with('success', 'Request Laundry dihapus.');
    }
}
