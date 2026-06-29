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
        Schema::table('buses', function (Blueprint $table) {
            $table->enum('bus_type', ['Personal', 'Commission'])->default('Personal')->after('plate_number');
            $table->integer('total_seats')->default(40)->after('bus_type');
            $table->enum('ac_non_ac', ['AC', 'Non AC'])->default('Non AC')->after('total_seats');
            $table->enum('seat_layout', ['1x2', '2x2'])->default('2x2')->after('ac_non_ac');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('buses', function (Blueprint $table) {
            $table->dropColumn(['bus_type', 'total_seats', 'ac_non_ac', 'seat_layout']);
        });
    }
};
