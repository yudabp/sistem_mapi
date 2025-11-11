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
        Schema::table('employees', function (Blueprint $table) {
            // Drop the unique index on ndp if it exists
            // The default Laravel unique index name would be 'employees_ndp_unique'
            if (Schema::hasIndex('employees', 'employees_ndp_unique')) {
                $table->dropUnique('employees_ndp_unique');
            } else {
                // If the named unique index doesn't exist, try the array format
                $table->dropUnique(['ndp']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('ndp')->unique(); // This will recreate the unique constraint
        });
    }
};
