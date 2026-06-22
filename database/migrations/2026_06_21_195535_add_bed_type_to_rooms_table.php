<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('rooms', 'bed_type')) {

            Schema::table('rooms', function (Blueprint $table) {
                $table->enum('bed_type', [
                    'Twin Bed',
                    'King Bed'
                ])->default('King Bed');
            });

        } 
    }

    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn('bed_type');
        });
    }
};