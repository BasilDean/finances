<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            // Adding default value while preserving existing data
            $table->integer('amount_calculated')->default(0)->change();
        });
    }

    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            // Remove the default constraint, keeping column intact
            $table->integer('amount_calculated')->default(null)->change();
        });
    }
};
