<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('passengers', function (Blueprint $table) {
            $table->string('hisab_person_name')->nullable()->after('is_hisab_completed');
            $table->date('hisab_collection_date')->nullable()->after('hisab_person_name');
            $table->string('hisab_mobile_number', 20)->nullable()->after('hisab_collection_date');
        });
    }

    public function down(): void
    {
        Schema::table('passengers', function (Blueprint $table) {
            $table->dropColumn(['hisab_person_name', 'hisab_collection_date', 'hisab_mobile_number']);
        });
    }
};
