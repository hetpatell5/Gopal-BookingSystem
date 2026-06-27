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
        Schema::create('passengers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bus_id')->constrained()->onDelete('cascade');
            $table->string('seat_number');
            $table->string('village_name')->nullable();
            $table->string('passenger_name');
            $table->string('passenger_mobile')->nullable();
            $table->string('traveler_name')->nullable();
            $table->string('traveler_number_plate')->nullable();
            $table->string('ac_type')->default('Non Ac');
            $table->string('bus_time')->nullable();
            $table->integer('total_seats')->default(1);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('payable_amount', 10, 2)->default(0);
            $table->string('pickup_stop')->nullable();
            $table->text('note')->nullable();
            $table->string('status')->default('Booked');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('passengers');
    }
};
