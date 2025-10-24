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
            // Only add reference_number column if it doesn't exist
            if (!Schema::hasColumn('hutang_pembayaran', 'reference_number')) {
                $table->string('reference_number')->nullable()->after('payment_method'); // Bank transfer reference, etc.
            }
            
            // Only add received_by column if it doesn't exist
            if (!Schema::hasColumn('hutang_pembayaran', 'received_by')) {
                $table->string('received_by')->nullable()->after('reference_number'); // Person who received the payment
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hutang_pembayaran', function (Blueprint $table) {
            $table->dropColumn(['reference_number', 'received_by']);
        });
    }
};
