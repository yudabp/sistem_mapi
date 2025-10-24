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
        Schema::create('buku_kas_kebun', function (Blueprint $table) {
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
            $table->unsignedBigInteger('kp_id')->nullable(); // Foreign key to keuangan_perusahaan
            $table->timestamps();
            
            // Add indexes for performance
            $table->index('transaction_date');
            $table->index('transaction_type');
            $table->index('category');
            $table->index('kp_id');
            
            // Add foreign key constraint
            $table->foreign('kp_id')->references('id')->on('keuangan_perusahaan')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku_kas_kebun');
    }
};