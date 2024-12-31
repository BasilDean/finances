<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->decimal('total', 20, 2)->default(0)->after('currency');
            $table->decimal('total_paid', 20, 2)->default(0)->after('total');
            $table->decimal('total_due', 20, 2)->default(0)->after('total_paid');
            $table->date('date')->after('total_due')->comment('Date of payment');
            $table->decimal('credit_percent', 5, 2)->default(0)->after('total_paid');
            $table->date('deadline')->after('credit_percent')->nullable()->comment('Deadline for payment');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('budget_id')->constrained()->cascadeOnDelete();
            $table->index(['user_id', 'budget_id']);
            $table->unique('slug');
            $table->string('slug')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('total');
            $table->dropColumn('total_paid');
            $table->dropColumn('total_due');
            $table->dropColumn('credit_percent');
            $table->dropColumn('deadline');
            $table->dropColumn('user_id');
            $table->dropColumn('budget_id');

            $table->dropUnique(['slug']);
            $table->string('slug')->nullable(false)->change();
        });
    }
};
