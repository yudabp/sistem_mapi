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
        Schema::table('family_compositions', function (Blueprint $table) {
            // Add the new name column with default value to avoid issues during data transfer
            $table->string('name', 255)->after('id');
        });
        
        // Populate the new name column with values from the number column (convert numbers to string)
        \DB::statement('UPDATE family_compositions SET name = CAST(number AS CHAR)');
        
        // Now drop the old number column
        Schema::table('family_compositions', function (Blueprint $table) {
            $table->dropColumn('number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('family_compositions', function (Blueprint $table) {
            // Add back the number column as integer
            $table->integer('number')->after('id');
        });
        
        // Populate the old number column with values from the name column (convert string to integer if it's numeric)
        \DB::statement("UPDATE family_compositions SET number = CAST(name AS SIGNED) WHERE name REGEXP '^[0-9]+$'");
        
        // Now drop the name column
        Schema::table('family_compositions', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }
};
