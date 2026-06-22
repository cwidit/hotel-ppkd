<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Room Types
        $standard = \App\Models\RoomType::create([
            'name'          => 'Standard Room',
            'description'   => 'Kamar standar dengan fasilitas dasar.',
            'price_per_night'=> 350000,
            'capacity'      => 2,
            'has_breakfast' => false,
        ]);

        $deluxe = \App\Models\RoomType::create([
            'name'          => 'Deluxe Room',
            'description'   => 'Kamar lebih luas dengan pemandangan kota.',
            'price_per_night'=> 600000,
            'capacity'      => 2,
            'has_breakfast' => true,
        ]);

        $superior = \App\Models\RoomType::create([
            'name'          => 'Superior Room',
            'description'   => 'Kamar superior dengan kingsbed dan work desk.',
            'price_per_night'=> 700000,
            'capacity'      => 2,
            'has_breakfast' => true,
        ]);

        $suite = \App\Models\RoomType::create([
            'name'          => 'Suite Room',
            'description'   => 'Kamar premium dengan ruang tamu terpisah.',
            'price_per_night'=> 1500000,
            'capacity'      => 4,
            'has_breakfast' => true,
        ]);

        // 2. Rooms
        $roomData = [
            ['room_number' => '101', 'room_type_id' => $standard->id],
            ['room_number' => '102', 'room_type_id' => $standard->id],
            ['room_number' => '103', 'room_type_id' => $standard->id],
            ['room_number' => '201', 'room_type_id' => $deluxe->id],
            ['room_number' => '202', 'room_type_id' => $deluxe->id],
            ['room_number' => '203', 'room_type_id' => $deluxe->id],
            ['room_number' => '301', 'room_type_id' => $superior->id],
            ['room_number' => '302', 'room_type_id' => $superior->id],
            ['room_number' => '401', 'room_type_id' => $suite->id],
            ['room_number' => '402', 'room_type_id' => $suite->id],
        ];

        foreach ($roomData as $data) {
            \App\Models\Room::create([
                'room_number' => $data['room_number'],
                'room_type_id' => $data['room_type_id'],
                'status' => 'Vacant Ready (VR)',
            ]);
        }

        // 3. FnB Menus
        \App\Models\FnbMenu::create(['name' => 'Nasi Goreng Spesial', 'price' => 45000, 'status' => 'Available']);
        \App\Models\FnbMenu::create(['name' => 'Mie Goreng Seafood', 'price' => 50000, 'status' => 'Available']);
        \App\Models\FnbMenu::create(['name' => 'Kopi Hitam', 'price' => 15000, 'status' => 'Available']);
        \App\Models\FnbMenu::create(['name' => 'Jus Jeruk', 'price' => 20000, 'status' => 'Available']);

        // 4. Laundry Services
        \App\Models\LaundryService::create(['name' => 'Cuci + Setrika (Kg)', 'price' => 15000]);
        \App\Models\LaundryService::create(['name' => 'Cuci Kering (Pcs)', 'price' => 5000]);
        \App\Models\LaundryService::create(['name' => 'Setrika Saja (Pcs)', 'price' => 3000]);
    }
}
