<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('laundry_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id_fo')->constrained('users')->onDelete('cascade');
            $table->foreignId('user_id_hk')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('laundry_service_id')->nullable()->constrained('laundry_services')->onDelete('set null');
            $table->integer('quantity')->default(1);
            $table->text('notes')->nullable();
            $table->dateTime('request_date');
            $table->enum('status', ['Pending', 'Processing', 'Delivered', 'Completed', 'Canceled'])->default('Pending');
            $table->decimal('total_laundry_amount', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laundry_requests');
    }
};
