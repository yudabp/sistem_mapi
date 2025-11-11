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
        // Add a new temporary column with string type
        Schema::table('employees', function (Blueprint $table) {
            $table->string('family_composition_new')->nullable();
        });
        
        // Copy data from old column to new column
        \DB::statement('UPDATE employees SET family_composition_new = CAST(family_composition AS CHAR)');
        
        // Drop the old column
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('family_composition');
        });
        
        // Rename the new column to the original name
        Schema::table('employees', function (Blueprint $table) {
            $table->renameColumn('family_composition_new', 'family_composition');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add a new temporary integer column
        Schema::table('employees', function (Blueprint $table) {
            $table->integer('family_composition_new')->default(0);
        });
        
        // Copy data back to integer column (try to convert string values to integers where possible)
        \DB::statement("UPDATE employees SET family_composition_new = CASE WHEN family_composition REGEXP '^[0-9]+$' THEN CAST(family_composition AS SIGNED) ELSE 0 END");
        
        // Drop the string column
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('family_composition');
        });
        
        // Rename the integer column back to original name
        Schema::table('employees', function (Blueprint $table) {
            $table->renameColumn('family_composition_new', 'family_composition');
        });
    }
};
