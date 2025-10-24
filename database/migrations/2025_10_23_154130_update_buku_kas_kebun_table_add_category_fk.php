<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('buku_kas_kebun', function (Blueprint $table) {
            $table->unsignedBigInteger('expense_category_id')->nullable()->after('category'); // Foreign key to expense categories
            $table->unsignedBigInteger('debt_id')->nullable()->after('expense_category_id'); // Foreign key to debts (for payment tracking)

            // Add foreign key constraints
            $table->foreign('expense_category_id')->references('id')->on('master_bkk_expense_categories')->onDelete('set null');
            $table->foreign('debt_id')->references('id')->on('debts')->onDelete('set null');

            // Add indexes
            $table->index('expense_category_id');
            $table->index('debt_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('buku_kas_kebun', function (Blueprint $table) {
            $table->dropForeign(['expense_category_id']);
            $table->dropForeign(['debt_id']);
            $table->dropIndex(['expense_category_id']);
            $table->dropIndex(['debt_id']);
            $table->dropColumn(['expense_category_id', 'debt_id']);
        });
    }
};
