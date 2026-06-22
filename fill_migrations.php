<?php

$dir = __DIR__ . '/database/migrations';
$files = scandir($dir);

$schemaMap = [
    'create_room_types_table' => <<<'EOT'
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('capacity')->default(2);
            $table->decimal('price_per_night', 15, 2);
            $table->boolean('has_breakfast')->default(false);
            $table->timestamps();
EOT,
    'create_rooms_table' => <<<'EOT'
            $table->id();
            $table->foreignId('room_type_id')->constrained()->onDelete('cascade');
            $table->string('room_number')->unique();
            $table->string('status')->default('Vacant Ready (VR)');
            $table->timestamps();
EOT,
    'create_guests_table' => <<<'EOT'
            $table->id();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('identity_number')->unique();
            $table->string('phone');
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
EOT,
    'create_reservations_table' => <<<'EOT'
            $table->id();
            $table->foreignId('guest_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // FO user
            $table->string('booking_number')->unique();
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->integer('total_days');
            $table->enum('status', ['Confirmed', 'Checked_In', 'Checked_Out', 'Canceled'])->default('Confirmed');
            $table->decimal('hotel_tax', 15, 2)->default(0);
            $table->decimal('service_charge', 15, 2)->default(0);
            $table->decimal('deposit_amount', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->enum('payment_status', ['Unpaid', 'Deposit_Paid', 'Paid', 'Partial'])->default('Unpaid');
            $table->timestamps();
EOT,
    'create_reservation_rooms_table' => <<<'EOT'
            $table->id();
            $table->foreignId('reservation_id')->constrained()->onDelete('cascade');
            $table->foreignId('room_id')->constrained()->onDelete('cascade');
            $table->foreignId('room_type_id')->constrained()->onDelete('cascade');
            $table->decimal('price_at_booking', 15, 2);
            $table->boolean('extra_bed')->default(false);
            $table->timestamps();
EOT,
    'create_payments_table' => <<<'EOT'
            $table->id();
            $table->foreignId('reservation_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->dateTime('payment_date');
            $table->enum('payment_method', ['Cash', 'Bank_Transfer', 'QRIS', 'Credit_Card']);
            $table->string('reference_number')->nullable();
            $table->timestamps();
EOT,
    'create_fnb_menus_table' => <<<'EOT'
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 15, 2);
            $table->enum('status', ['Available', 'Unavailable'])->default('Available');
            $table->timestamps();
EOT,
    'create_fnb_orders_table' => <<<'EOT'
            $table->id();
            $table->foreignId('reservation_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // FnB user
            $table->dateTime('order_date');
            $table->enum('status', ['Pending', 'Processing', 'Delivered', 'Completed', 'Canceled'])->default('Pending');
            $table->decimal('total_order_amount', 15, 2)->default(0);
            $table->timestamps();
EOT,
    'create_fnb_order_items_table' => <<<'EOT'
            $table->id();
            $table->foreignId('fnb_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('fnb_menu_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('price_at_booking', 15, 2);
            $table->timestamps();
EOT,
    'create_laundry_services_table' => <<<'EOT'
            $table->id();
            $table->string('name');
            $table->decimal('price', 15, 2);
            $table->timestamps();
EOT,
    'create_laundry_requests_table' => <<<'EOT'
            $table->id();
            $table->foreignId('reservation_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id_fo')->constrained('users')->onDelete('cascade');
            $table->foreignId('user_id_hk')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('request_date');
            $table->enum('status', ['Pending', 'Processing', 'Delivered', 'Completed', 'Canceled'])->default('Pending');
            $table->decimal('total_laundry_amount', 15, 2)->default(0);
            $table->timestamps();
EOT,
    'create_room_inspections_table' => <<<'EOT'
            $table->id();
            $table->foreignId('reservation_id')->constrained()->onDelete('cascade');
            $table->foreignId('room_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // HK User
            $table->dateTime('inspection_date');
            $table->enum('inspection_result', ['Clean_Available', 'Dirty', 'Damaged']);
            $table->text('notes')->nullable();
            $table->decimal('damages_charge', 15, 2)->default(0);
            $table->timestamps();
EOT,
    'create_extra_charges_table' => <<<'EOT'
            $table->id();
            $table->foreignId('reservation_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // FO user
            $table->string('name');
            $table->decimal('amount', 15, 2);
            $table->timestamps();
EOT,
];

foreach ($files as $file) {
    if ($file === '.' || $file === '..') continue;
    
    foreach ($schemaMap as $tableKey => $schemaContent) {
        if (strpos($file, $tableKey) !== false) {
            $path = $dir . '/' . $file;
            $content = file_get_contents($path);
            
            // Regex to replace inside Schema::create block
            $pattern = '/Schema::create\([\'"].+?[\'"], function \(Blueprint \$table\) \{(.*?)\}\);/s';
            $replacement = "Schema::create('" . str_replace('create_', '', str_replace('_table', '', $tableKey)) . "', function (Blueprint \$table) {\n" . $schemaContent . "\n        });";
            
            // Just replace the block
            $newContent = preg_replace($pattern, $replacement, $content);
            file_put_contents($path, $newContent);
            echo "Updated $file\n";
        }
    }
}
