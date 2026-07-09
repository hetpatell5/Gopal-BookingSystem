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
        Schema::create('agent_tickets', function (Blueprint $table) {
            $table->id();
            $table->date('sale_date')->nullable();
            $table->string('agent_name');
            $table->decimal('seat_price', 10, 2)->default(0);
            $table->decimal('commission_percentage', 5, 2)->default(0);
            $table->decimal('commission_amount', 10, 2)->default(0);
            $table->decimal('net_amount', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_tickets');
    }
};
