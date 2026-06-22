<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class HousekeepingController extends Controller
{
    private const DIRTY_STATUSES = [
        'Vacant Dirty (VD)',
        'Occupied Dirty (OD)',
        'Make Up Room (MUR)',
        'Check-Out (CO)',
    ];

    public function index()
    {
        $dirtyRooms = Room::with('roomType')
            ->whereIn('status', self::DIRTY_STATUSES)
            ->orderBy('room_number')
            ->get();

        $allRooms = Room::with('roomType')->orderBy('room_number')->get();

        $stats = [
            'dirty'      => Room::whereIn('status', self::DIRTY_STATUSES)->count(),
            'available'  => Room::where('status', 'Vacant Ready (VR)')->count(),
            'occupied'   => Room::where('status', 'like', 'Occupied%')->count(),
            'ooo'        => Room::where('status', 'like', 'Out of Order%')->count(),
        ];

        return view('admin.housekeeping.index', compact('dirtyRooms', 'allRooms', 'stats'));
    }

    public function markClean(Request $request, Room $room)
    {
        $request->validate([
            'new_status' => 'required|in:Vacant Clean (VC),Vacant Ready (VR)',
        ]);

        $room->update(['status' => $request->new_status]);

        return redirect()->route('admin.housekeeping.index')
            ->with('success', 'Kamar ' . $room->room_number . ' berhasil diperbarui ke status "' . $request->new_status . '".');
    }
}
