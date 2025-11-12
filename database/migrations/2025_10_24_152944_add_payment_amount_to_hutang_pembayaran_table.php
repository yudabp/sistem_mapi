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
        Schema::table('hutang_pembayaran', function (Blueprint $table) {
            // Only add payment_amount column if it doesn't exist
            if (!Schema::hasColumn('hutang_pembayaran', 'payment_amount')) {
                $table->decimal('payment_amount', 15, 2)->after('debt_id')->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hutang_pembayaran', function (Blueprint $table) {
            $table->dropColumn('payment_amount');
        });
    }
};
