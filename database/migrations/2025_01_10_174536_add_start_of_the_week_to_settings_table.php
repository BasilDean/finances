<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->enum('start_of_the_week', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', ' sunday'])->default('monday');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('start_of_the_week');
        });
    }
};