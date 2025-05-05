<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('expenses', static function (Blueprint $table) {
            $table->dateTime('date')->nullable()->after('has_items');
        });
    }

    public function down(): void
    {
        Schema::table('expenses', static function (Blueprint $table) {
            $table->dropColumn('date');
        });
    }
};
