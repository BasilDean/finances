<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('incomes', static function (Blueprint $table) {
            $table->dateTime('date')->nullable()->after('currency');
        });
    }

    public function down(): void
    {
        Schema::table('incomes', static function (Blueprint $table) {
            $table->dropColumn('date');
        });
    }
};
