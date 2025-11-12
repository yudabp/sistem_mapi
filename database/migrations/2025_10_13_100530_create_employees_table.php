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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('ndp')->unique(); // Employee ID
            $table->string('name');
            $table->string('department');
            $table->string('position');
            $table->string('grade')->nullable();
            $table->integer('family_composition')->default(0);
            $table->decimal('monthly_salary', 15, 2);
            $table->enum('status', ['active', 'inactive', 'resigned'])->default('active');
            $table->date('hire_date')->nullable();
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
