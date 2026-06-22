<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FnbOrder;
use App\Models\FnbOrderItem;
use App\Models\FnbMenu;
use App\Models\Reservation;
use App\Models\ExtraCharge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FnbOrderController extends Controller
{
    public function index()
    {
        $orders = FnbOrder::with(['reservation.guest'])->orderBy('created_at', 'desc')->get();
        return view('admin.fnb_orders.index', compact('orders'));
    }

    public function create()
    {
        $reservations = Reservation::with('guest')->where('status', 'Checked_In')->get();
        $menus = FnbMenu::where('status', 'Available')->get();
        return view('admin.fnb_orders.create', compact('reservations', 'menus'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'reservation_id'  => 'required|exists:reservations,id',
            'menu_id'         => 'required|array|min:1',
            'menu_id.*'       => 'exists:fnb_menus,id',
            'quantity'        => 'required|array',
            'quantity.*'      => 'integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            $order = FnbOrder::create([
                'reservation_id' => $validated['reservation_id'],
                'user_id'        => auth()->id(),
                'order_date'     => now(),
                'status'         => 'Pending',
                'total_order_amount' => 0,
            ]);

            $totalAmount  = 0;
            $itemSummary  = [];

            foreach ($validated['menu_id'] as $index => $menuId) {
                $qty = (int) ($validated['quantity'][$index] ?? 0);
                if ($qty <= 0) {
                    continue;
                }

                $menu     = FnbMenu::findOrFail($menuId);
                $subtotal = $menu->price * $qty;

                FnbOrderItem::create([
                    'fnb_order_id'  => $order->id,
                    'fnb_menu_id'   => $menu->id,
                    'quantity'      => $qty,
                    'price_at_order'=> $menu->price,
                ]);

                $totalAmount  += $subtotal;
                $itemSummary[] = $menu->name . ' x' . $qty;
            }

            $order->update(['total_order_amount' => $totalAmount]);

            ExtraCharge::create([
                'reservation_id' => $validated['reservation_id'],
                'user_id'        => auth()->id(),
                'charge_type'    => 'FnB',
                'name'           => 'FnB Order #' . $order->id . ': ' . implode(', ', $itemSummary),
                'amount'         => $totalAmount,
            ]);

            DB::commit();
            return redirect()->route('admin.fnb-orders.index')->with('success', 'Pemesanan FnB berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat pesanan: ' . $e->getMessage())->withInput();
        }
    }

    public function show(FnbOrder $fnbOrder)
    {
        $fnbOrder->load(['reservation.guest', 'items.fnbMenu']);
        return view('admin.fnb_orders.show', compact('fnbOrder'));
    }

    public function edit(FnbOrder $fnbOrder)
    {
        return view('admin.fnb_orders.edit', compact('fnbOrder'));
    }

    public function update(Request $request, FnbOrder $fnbOrder)
    {
        $validated = $request->validate([
            'status' => 'required|in:Pending,Processing,Delivered,Completed,Canceled',
        ]);

        $fnbOrder->update($validated);

        return redirect()->route('admin.fnb-orders.index')->with('success', 'Status pesanan FnB berhasil diperbarui.');
    }

    public function destroy(FnbOrder $fnbOrder)
    {
        $fnbOrder->delete();
        return redirect()->route('admin.fnb-orders.index')->with('success', 'Pesanan FnB dihapus.');
    }
}
