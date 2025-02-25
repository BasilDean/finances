<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('incomes', static function (Blueprint $table) {
            $table->string('normalized_title')->default('')->index();
        });
    }

    public function down(): void
    {
        Schema::table('incomes', static function (Blueprint $table) {
            $table->dropColumn('normalized_title');
        });
    }
};
