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
            $table->string('reference_number')->nullable()->after('payment_method'); // Bank transfer reference, etc.
            $table->string('received_by')->nullable()->after('reference_number'); // Person who received the payment
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
