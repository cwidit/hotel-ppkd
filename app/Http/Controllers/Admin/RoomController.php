<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::with('roomType')->get();
        return view('admin.rooms.index', compact('rooms'));
    }

    public function create()
    {
        $roomTypes = RoomType::all();
        $statuses = [
            'Vacant Ready (VR)', 'Vacant Dirty (VD)', 'Vacant Clean (VC)', 'Vacant Clean Inspected (VCI)', 
            'Occupied Clean (OC)', 'Occupied Dirty (OD)', 'Occupied No Baggage (ONB)', 'Expected Arrival (EA)', 
            'Expected Departure (ED)', 'Complimentary (Comp)', 'Out of Order (OOO)', 'Out of Service (OOS)', 
            'Do Not Disturb (DND)', 'Sleep Out (SO)', 'Skipper', 'Make Up Room (MUR)', 'Turn Down Service (TDS)', 
            'House Use (HU)', 'Lock Out (LO)', 'Late Check Out (LCO)', 'Early Check In (ECI)', 
            'Extra Bed (EB)', 'Incognito', 'VIP', 'No Show (NS)'
        ];
        return view('admin.rooms.create', compact('roomTypes', 'statuses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_type_id' => 'required|exists:room_types,id',
            'room_number' => 'required|string|unique:rooms,room_number',
            'status' => 'required|string',
        ]);

        Room::create($validated);

        return redirect()->route('admin.rooms.index')->with('success', 'Kamar berhasil ditambahkan.');
    }

    public function edit(Room $room)
    {
        $roomTypes = RoomType::all();
        $statuses = [
            'Vacant Ready (VR)', 'Vacant Dirty (VD)', 'Vacant Clean (VC)', 'Vacant Clean Inspected (VCI)', 
            'Occupied Clean (OC)', 'Occupied Dirty (OD)', 'Occupied No Baggage (ONB)', 'Expected Arrival (EA)', 
            'Expected Departure (ED)', 'Complimentary (Comp)', 'Out of Order (OOO)', 'Out of Service (OOS)', 
            'Do Not Disturb (DND)', 'Sleep Out (SO)', 'Skipper', 'Make Up Room (MUR)', 'Turn Down Service (TDS)', 
            'House Use (HU)', 'Lock Out (LO)', 'Late Check Out (LCO)', 'Early Check In (ECI)', 
            'Extra Bed (EB)', 'Incognito', 'VIP', 'No Show (NS)'
        ];
        return view('admin.rooms.edit', compact('room', 'roomTypes', 'statuses'));
    }

    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'room_type_id' => 'required|exists:room_types,id',
            'room_number' => 'required|string|unique:rooms,room_number,'.$room->id,
            'status' => 'required|string',
        ]);

        $room->update($validated);

        return redirect()->route('admin.rooms.index')->with('success', 'Kamar berhasil diupdate.');
    }

    public function updateStatus(Request $request, Room $room)
    {
        $request->validate(['status' => 'required|string']);
        $room->update(['status' => $request->status]);
        return redirect()->route('admin.rooms.index')->with('success', 'Status kamar ' . $room->room_number . ' berhasil diubah.');
    }

    public function destroy(Room $room)
    {
        $room->delete();
        return redirect()->route('admin.rooms.index')->with('success', 'Kamar berhasil dihapus.');
    }
}
