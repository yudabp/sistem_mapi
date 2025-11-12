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
        Schema::table('production', function (Blueprint $table) {
            // Make TBS quantity nullable
            $table->decimal('tbs_quantity', 10, 2)->nullable()->change();
            // Make vehicle_number nullable to allow empty No Pol
            $table->string('vehicle_number')->nullable()->change();
            // vehicle_id is already nullable based on the table structure
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('production', function (Blueprint $table) {
            // Make columns not nullable again
            $table->decimal('tbs_quantity', 10, 2)->nullable(false)->change();
            $table->string('vehicle_number')->nullable(false)->change();
        });
    }
};
