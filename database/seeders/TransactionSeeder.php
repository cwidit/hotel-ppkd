<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Guest;
use App\Models\Reservation;
use App\Models\ReservationRoom;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\Payment;
use App\Models\FnbOrder;
use App\Models\FnbOrderItem;
use App\Models\FnbMenu;
use App\Models\LaundryRequest;
use App\Models\LaundryService;
use App\Models\ExtraCharge;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TransactionSeeder extends Seeder
{
    public function run()
    {
        // Pastikan ada room dan menu sebelum seeding transaksi
        if (Room::count() == 0 || FnbMenu::count() == 0) {
            $this->command->info('Please run DummyDataSeeder first.');
            return;
        }

        DB::beginTransaction();
        try {
            // 1. Seed Guests
            $guests = [
                ['first_name' => 'Budi', 'last_name' => 'Santoso', 'identity_number' => '3201012345678901', 'phone' => '081234567890', 'email' => 'budi@example.com'],
                ['first_name' => 'Siti', 'last_name' => 'Aminah', 'identity_number' => '3201012345678902', 'phone' => '081234567891', 'email' => 'siti@example.com'],
                ['first_name' => 'Andi', 'last_name' => 'Wijaya', 'identity_number' => '3201012345678903', 'phone' => '081234567892', 'email' => 'andi@example.com'],
                ['first_name' => 'Dewi', 'last_name' => 'Lestari', 'identity_number' => '3201012345678904', 'phone' => '081234567893', 'email' => 'dewi@example.com'],
            ];

            $guestModels = [];
            foreach ($guests as $g) {
                $guestModels[] = Guest::create($g);
            }

            // 2. Seed Reservations (Past 7 days up to next 7 days)
            $statuses = ['Confirmed', 'Checked_In', 'Checked_Out', 'Canceled'];
            $rooms = Room::with('roomType')->get();
            
            $userId = 1; // Assuming Admin is 1

            foreach ($guestModels as $index => $guest) {
                $checkIn = Carbon::now()->subDays(rand(1, 5))->startOfDay();
                $totalDays = rand(1, 3);
                $checkOut = $checkIn->copy()->addDays($totalDays);
                
                // Select a random room
                $room = $rooms->random();

                $totalRoomPrice = $room->roomType->price_per_night * $totalDays;
                $tax = $totalRoomPrice * 0.10;
                $service = $totalRoomPrice * 0.05;
                $totalAmount = $totalRoomPrice + $tax + $service;

                // Random status
                $status = $statuses[array_rand($statuses)];
                if ($checkOut->isPast() && $status != 'Canceled') {
                    $status = 'Checked_Out';
                }

                $paymentStatus = 'Paid';
                if ($status == 'Confirmed') $paymentStatus = 'Deposit_Paid';
                if ($status == 'Canceled') $paymentStatus = 'Unpaid';

                $reservation = Reservation::create([
                    'guest_id' => $guest->id,
                    'user_id' => $userId,
                    'booking_number' => 'BKG-SD-' . time() . $index,
                    'check_in_date' => $checkIn,
                    'check_out_date' => $checkOut,
                    'total_days' => $totalDays,
                    'status' => $status,
                    'hotel_tax' => $tax,
                    'service_charge' => $service,
                    'deposit_amount' => $paymentStatus == 'Paid' ? $totalAmount : ($paymentStatus == 'Deposit_Paid' ? $totalAmount / 2 : 0),
                    'total_amount' => $totalAmount,
                    'payment_status' => $paymentStatus,
                ]);

                // Create ReservationRoom
                ReservationRoom::create([
                    'reservation_id' => $reservation->id,
                    'room_id' => $room->id,
                    'room_type_id' => $room->room_type_id,
                    'price_at_booking' => $room->roomType->price_per_night,
                ]);

                // Create Payment if not unpaid
                if ($paymentStatus != 'Unpaid') {
                    Payment::create([
                        'reservation_id' => $reservation->id,
                        'amount' => $reservation->deposit_amount,
                        'payment_date' => $checkIn,
                        'payment_method' => 'Cash',
                        'reference_number' => 'PAY-SD-' . time() . $index,
                        'status' => 'Completed',
                    ]);
                }

                // 3. Seed FnB Order for some reservations
                if ($status == 'Checked_In' || $status == 'Checked_Out') {
                    if (rand(0, 1)) {
                        $menus = FnbMenu::inRandomOrder()->take(2)->get();
                        $orderAmount = 0;
                        
                        $fnbOrder = FnbOrder::create([
                            'reservation_id' => $reservation->id,
                            'user_id' => $userId,
                            'order_date' => $checkIn->copy()->addHours(12),
                            'status' => 'Delivered',
                            'total_order_amount' => 0,
                            'created_at' => $checkIn->copy()->addHours(12)
                        ]);

                        foreach ($menus as $menu) {
                            $qty = rand(1, 2);
                            FnbOrderItem::create([
                                'fnb_order_id' => $fnbOrder->id,
                                'fnb_menu_id' => $menu->id,
                                'quantity' => $qty,
                                'price_at_order' => $menu->price,
                            ]);
                            $orderAmount += ($menu->price * $qty);
                        }
                        
                        $fnbOrder->update(['total_order_amount' => $orderAmount]);

                        ExtraCharge::create([
                            'reservation_id' => $reservation->id,
                            'user_id' => $userId,
                            'charge_type' => 'FnB',
                            'name' => 'Pemesanan FnB Order #' . $fnbOrder->id,
                            'amount' => $orderAmount,
                            'created_at' => $fnbOrder->created_at
                        ]);
                        
                        // Increase reservation total
                        $reservation->update(['total_amount' => $reservation->total_amount + $orderAmount]);
                    }

                    // 4. Seed Laundry
                    if (rand(0, 1)) {
                        $service = LaundryService::inRandomOrder()->first();
                        $qty = rand(1, 3);
                        $laundryAmount = $service->price * $qty;

                        $laundryReq = LaundryRequest::create([
                            'reservation_id' => $reservation->id,
                            'user_id_fo' => $userId,
                            'laundry_service_id' => $service->id,
                            'quantity' => $qty,
                            'request_date' => $checkIn->copy()->addHours(15),
                            'total_laundry_amount' => $laundryAmount,
                            'status' => 'Delivered',
                            'notes' => 'Tolong disetrika rapi',
                            'created_at' => $checkIn->copy()->addHours(15)
                        ]);

                        ExtraCharge::create([
                            'reservation_id' => $reservation->id,
                            'user_id' => $userId,
                            'charge_type' => 'Laundry',
                            'name' => 'Layanan Laundry Request #' . $laundryReq->id . ' - ' . $service->name,
                            'amount' => $laundryAmount,
                            'created_at' => $laundryReq->created_at
                        ]);

                        // Increase reservation total
                        $reservation->update(['total_amount' => $reservation->total_amount + $laundryAmount]);
                    }
                }
            }

            DB::commit();
            $this->command->info('Transactions seeded successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error seeding transactions: ' . $e->getMessage());
        }
    }
}
