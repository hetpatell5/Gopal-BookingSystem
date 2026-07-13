<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('passengers', function (Blueprint $table) {
            if (!Schema::hasColumn('passengers', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('payable_amount');
            }
            if (!Schema::hasColumn('passengers', 'payment_collected_by')) {
                $table->string('payment_collected_by')->nullable()->after('payment_method');
            }
        });
    }

    public function down(): void
    {
        Schema::table('passengers', function (Blueprint $table) {
            $table->dropColumnIfExists('payment_method');
            $table->dropColumnIfExists('payment_collected_by');
        });
    }
};
