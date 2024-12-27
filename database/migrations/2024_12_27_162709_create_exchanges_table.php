<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('exchanges', function (Blueprint $table) {
            $table->id();
            $table->float('amount_from');
            $table->foreignId('account_from')->constrained('accounts', 'id');
            $table->string('currency_from');
            $table->float('amount_to');
            $table->foreignId('account_to')->constrained('accounts', 'id');
            $table->string('currency_to');
            $table->float('exchange_rate');
            $table->float('oficial_rate')->default(0);
            $table->foreignId('user_id');
            $table->string('slug')->unique()->nullable();
            $table->unsignedBigInteger('expense_id')->nullable();
            $table->unsignedBigInteger('income_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exchanges');
    }
};
