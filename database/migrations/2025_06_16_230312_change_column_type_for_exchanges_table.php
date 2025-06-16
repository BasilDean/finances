<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('exchanges', function (Blueprint $table) {
            $table->decimal('amount_from', 20, 4)->change();
            $table->decimal('amount_to', 20, 4)->change();
            $table->decimal('exchange_rate', 20, 4)->change();
            $table->decimal('oficial_rate', 20, 4)->change();
        });
    }

    public function down(): void
    {
        Schema::table('exchanges', function (Blueprint $table) {
            $table->float('amount_from')->change();
            $table->float('amount_to')->change();
            $table->float('exchange_rate')->change();
            $table->float('oficial_rate')->change();
        });
    }
};
