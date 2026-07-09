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
        Schema::table('agent_tickets', function (Blueprint $table) {
            $table->string('bus_name')->nullable()->after('agent_name');
            $table->integer('total_seats')->default(1)->after('bus_name');
            $table->decimal('total_amount', 10, 2)->default(0)->after('seat_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agent_tickets', function (Blueprint $table) {
            //
        });
    }
};
