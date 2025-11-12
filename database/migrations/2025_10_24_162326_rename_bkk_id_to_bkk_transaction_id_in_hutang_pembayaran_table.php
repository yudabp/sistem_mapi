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
            // Drop the foreign key constraint first
            $table->dropForeign(['bkk_id']);
            
            // Rename the column
            $table->renameColumn('bkk_id', 'bkk_transaction_id');
            
            // Add the foreign key constraint back with the new column name
            $table->foreign('bkk_transaction_id')->references('id')->on('buku_kas_kebun')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hutang_pembayaran', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['bkk_transaction_id']);
            
            // Rename the column back
            $table->renameColumn('bkk_transaction_id', 'bkk_id');
            
            // Add the foreign key constraint back with the original column name
            $table->foreign('bkk_id')->references('id')->on('buku_kas_kebun')->onDelete('set null');
        });
    }
};
