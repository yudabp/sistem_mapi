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
        Schema::create('hutang_pembayaran', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('debt_id');
            $table->decimal('payment_amount', 15, 2);
            $table->date('payment_date');
            $table->string('payment_method')->nullable();
            $table->string('reference_number')->nullable();
            $table->text('notes')->nullable();
            $table->string('proof_document_path')->nullable();
            $table->unsignedBigInteger('bkk_id')->nullable();
            $table->timestamps();
            
            // Add foreign key constraints
            $table->foreign('debt_id')->references('id')->on('debts')->onDelete('cascade');
            $table->foreign('bkk_id')->references('id')->on('buku_kas_kebun')->onDelete('set null');
            
            // Add indexes for better performance
            $table->index('debt_id');
            $table->index('payment_date');
            $table->index('payment_method');
            $table->index('reference_number');
            $table->index('bkk_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hutang_pembayaran');
    }
};
