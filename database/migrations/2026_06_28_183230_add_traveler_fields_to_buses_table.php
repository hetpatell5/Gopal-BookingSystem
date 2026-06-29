<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('buses', function (Blueprint $table) {
            $table->string('traveler_name')->nullable()->after('seat_layout');
            $table->string('traveler_number_plate')->nullable()->after('traveler_name');
        });
    }

    public function down(): void
    {
        Schema::table('buses', function (Blueprint $table) {
            $table->dropColumn(['traveler_name', 'traveler_number_plate']);
        });
    }
};
