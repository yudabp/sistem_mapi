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
        Schema::create('financial_transactions', function (Blueprint $table) {
            $table->id();
            $table->date('transaction_date');
            $table->string('transaction_number')->unique();
            $table->enum('transaction_type', ['income', 'expense']);
            $table->decimal('amount', 15, 2);
            $table->string('source_destination')->nullable(); // Source or destination
            $table->string('received_by')->nullable(); // Who received the money
            $table->string('proof_document_path')->nullable(); // Proof document upload path
            $table->text('notes')->nullable();
            $table->string('category')->nullable(); // Category for filtering
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_transactions');
    }
};
