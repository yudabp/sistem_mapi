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
        Schema::table('sales', function (Blueprint $table) {
            $table->boolean('is_taxable')->default(false)->after('customer_address');
            $table->decimal('tax_percentage', 5, 2)->default(11.00)->after('is_taxable');
            $table->decimal('tax_amount', 15, 2)->default(0)->after('tax_percentage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(['is_taxable', 'tax_percentage', 'tax_amount']);
        });
    }
};