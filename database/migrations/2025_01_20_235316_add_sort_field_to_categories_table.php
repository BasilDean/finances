<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('categories', static function (Blueprint $table) {
            $table->integer('sort')->default(500)->after('title');
        });
    }

    public function down(): void
    {
        Schema::table('categories', static function (Blueprint $table) {
            $table->dropColumn('sort');
        });
    }
};
