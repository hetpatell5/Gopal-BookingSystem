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
        Schema::create('personal_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('ref_no')->nullable();
            $table->date('slip_date')->nullable();
            $table->decimal('grease_cost', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('toll_tax', 10, 2)->default(0);
            $table->decimal('diesel_liter', 10, 2)->default(0);
            $table->decimal('diesel_rate', 10, 2)->default(0);
            $table->decimal('diesel_amount', 10, 2)->default(0);
            $table->decimal('driver_salary', 10, 2)->default(0);
            $table->decimal('conductor_salary', 10, 2)->default(0);
            $table->decimal('parking', 10, 2)->default(0);
            $table->decimal('parchuran', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_accounts');
    }
};
