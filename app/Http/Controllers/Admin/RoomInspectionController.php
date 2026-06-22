<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RoomInspection;
use App\Models\Room;
use App\Models\Reservation;
use App\Models\ExtraCharge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoomInspectionController extends Controller
{
    // Map inspection_result → room status
    private const RESULT_TO_STATUS = [
        'Clean_Available' => 'Vacant Ready (VR)',
        'Dirty'           => 'Vacant Dirty (VD)',
        'Damaged'         => 'Out of Order (OOO)',
    ];

    public function index()
    {
        $inspections = RoomInspection::with(['room', 'user', 'reservation.guest'])->orderBy('created_at', 'desc')->get();
        return view('admin.room_inspections.index', compact('inspections'));
    }

    public function create()
    {
        $rooms = Room::with('roomType')->whereIn('status', [
            'Vacant Dirty (VD)',
            'Occupied Dirty (OD)',
            'Make Up Room (MUR)',
            'Check-Out (CO)',
        ])->get();
        return view('admin.room_inspections.create', compact('rooms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id'          => 'required|exists:rooms,id',
            'reservation_id'   => 'nullable|exists:reservations,id',
            'inspection_result'=> 'required|in:Clean_Available,Dirty,Damaged',
            'notes'            => 'nullable|string',
            'damages_charge'   => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $room = Room::findOrFail($validated['room_id']);

            $inspection = RoomInspection::create([
                'room_id'          => $validated['room_id'],
                'reservation_id'   => $validated['reservation_id'],
                'user_id'          => auth()->id(),
                'inspection_date'  => now(),
                'inspection_result'=> $validated['inspection_result'],
                'notes'            => $validated['notes'],
                'damages_charge'   => $validated['damages_charge'] ?? 0,
            ]);

            // Update room status based on result
            $newStatus = self::RESULT_TO_STATUS[$validated['inspection_result']];
            $room->update(['status' => $newStatus]);

            // If damaged and there is a reservation, add extra charge
            $damagesCharge = (float) ($validated['damages_charge'] ?? 0);
            if ($validated['inspection_result'] === 'Damaged' && $damagesCharge > 0 && $validated['reservation_id']) {
                ExtraCharge::create([
                    'reservation_id' => $validated['reservation_id'],
                    'user_id'        => auth()->id(),
                    'charge_type'    => 'Damage',
                    'name'           => 'Kerusakan Kamar ' . $room->room_number . ': ' . ($validated['notes'] ?? 'Lihat catatan inspeksi'),
                    'amount'         => $damagesCharge,
                ]);
            }

            DB::commit();
            return redirect()->route('admin.room-inspections.index')
                ->with('success', 'Inspeksi berhasil disimpan. Status kamar ' . $room->room_number . ' → ' . $newStatus);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan inspeksi: ' . $e->getMessage())->withInput();
        }
    }

    public function show(RoomInspection $roomInspection)
    {
        $roomInspection->load(['room', 'user', 'reservation.guest']);
        return view('admin.room_inspections.show', compact('roomInspection'));
    }

    public function destroy(RoomInspection $roomInspection)
    {
        $roomInspection->delete();
        return redirect()->route('admin.room-inspections.index')->with('success', 'Data inspeksi dihapus.');
    }
}
