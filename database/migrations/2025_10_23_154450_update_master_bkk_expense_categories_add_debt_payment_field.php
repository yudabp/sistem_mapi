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
        Schema::table('master_bkk_expense_categories', function (Blueprint $table) {
            // Only add the column if it doesn't exist
            if (!Schema::hasColumn('master_bkk_expense_categories', 'is_debt_payment')) {
                $table->boolean('is_debt_payment')->default(false)->after('description'); // Mark if this category is for debt payments

                // Add index
                $table->index('is_debt_payment');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_bkk_expense_categories', function (Blueprint $table) {
            $table->dropIndex(['is_debt_payment']);
            $table->dropColumn('is_debt_payment');
        });
    }
};
