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
        Schema::create('production', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_number')->unique();
            $table->date('date');
            $table->string('sp_number');
            $table->string('vehicle_number');
            $table->decimal('tbs_quantity', 10, 2); // TBS quantity
            $table->decimal('kg_quantity', 10, 2); // KG quantity
            $table->string('division'); // Afdeling
            $table->string('pks'); // Processing Station
            $table->string('sp_photo_path')->nullable(); // SP photo upload path
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production');
    }
};
