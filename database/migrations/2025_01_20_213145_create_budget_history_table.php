<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('budget_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('budget_id')->constrained('budgets')->cascadeOnDelete();
            $table->decimal('amount', 15, 2);
            $table->enum('operation_type', ['income', 'expense']);
            $table->string('description')->nullable();
            $table->decimal('balance_after', 15, 2);
            $table->timestamp('performed_at');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('budget_history');
    }
};
