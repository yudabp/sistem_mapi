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
        Schema::table('debts', function (Blueprint $table) {
            $table->decimal('sisa_hutang', 15, 2)->nullable()->after('amount'); // Remaining debt amount
            $table->decimal('cicilan_per_bulan', 15, 2)->nullable()->after('sisa_hutang'); // Monthly installment
            $table->unsignedBigInteger('debt_type_id')->nullable()->after('description'); // Foreign key to debt types

            // Add foreign key constraint
            $table->foreign('debt_type_id')->references('id')->on('master_debt_types')->onDelete('set null');

            // Add indexes
            $table->index('debt_type_id');
            $table->index('sisa_hutang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('debts', function (Blueprint $table) {
            $table->dropForeign(['debt_type_id']);
            $table->dropIndex(['debt_type_id']);
            $table->dropIndex(['sisa_hutang']);
            $table->dropColumn(['sisa_hutang', 'cicilan_per_bulan', 'debt_type_id']);
        });
    }
};
