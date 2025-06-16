<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('budget_history', function (Blueprint $table) {
            $table->decimal('amount', 20, 4)->change();
            $table->decimal('balance_after', 20, 4)->change();
        });
    }

    public function down(): void
    {
        Schema::table('budget_history', function (Blueprint $table) {
            $table->float('amount')->change();
            $table->float('balance_after')->change();
        });
    }
};
