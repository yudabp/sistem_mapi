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
        Schema::create('debts', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 15, 2);
            $table->string('creditor'); // Who is owed
            $table->date('due_date')->nullable();
            $table->text('description')->nullable();
            $table->string('proof_document_path')->nullable(); // Proof document upload path
            $table->enum('status', ['unpaid', 'paid'])->default('unpaid');
            $table->date('paid_date')->nullable(); // When the debt was paid
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('debts');
    }
};
