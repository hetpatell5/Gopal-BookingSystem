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
        Schema::table('personal_accounts', function (Blueprint $table) {
            $table->string('bus_name')->nullable()->after('slip_date');
            $table->string('bus_number')->nullable()->after('bus_name');
            $table->string('manager_name')->nullable()->after('bus_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personal_accounts', function (Blueprint $table) {
            $table->dropColumn(['bus_name', 'bus_number', 'manager_name']);
        });
    }
};
