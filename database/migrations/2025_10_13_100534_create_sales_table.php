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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('sp_number');
            $table->decimal('tbs_quantity', 10, 2)->nullable(); // TBS quantity
            $table->decimal('kg_quantity', 10, 2); // KG quantity
            $table->decimal('price_per_kg', 10, 2); // Price per kg
            $table->decimal('total_amount', 15, 2); // Automatically calculated total
            $table->string('sales_proof_path')->nullable(); // Sales proof upload path
            $table->date('sale_date')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
