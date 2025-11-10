<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            // Make fields nullable except for the required ones (name, position, monthly_salary)
            // Note: name and monthly_salary stay as NOT NULL as they are required
            $table->string('ndp')->nullable()->change();
            $table->string('department')->nullable()->change();
            $table->string('position')->nullable()->change();
            $table->enum('status', ['active', 'inactive', 'resigned'])->nullable()->change();
        });
        
        // Update the default value for family_composition to allow NULL
        \DB::statement('ALTER TABLE employees ALTER COLUMN family_composition DROP DEFAULT');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            // Revert back to not nullable
            $table->string('ndp')->nullable(false)->unique()->change();
            $table->string('department')->nullable(false)->change();
            $table->string('position')->nullable(false)->change();
            $table->enum('status', ['active', 'inactive', 'resigned'])->nullable(false)->default('active')->change();
        });
        
        // Set the default value back for family_composition
        \DB::statement('ALTER TABLE employees ALTER COLUMN family_composition SET DEFAULT 0');
    }
};
