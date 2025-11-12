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
            // Add foreign key columns after existing columns
            $table->unsignedBigInteger('vehicle_id')->nullable()->after('sp_number');
            $table->unsignedBigInteger('division_id')->nullable()->after('kg_quantity');
            $table->unsignedBigInteger('pks_id')->nullable()->after('division_id');
            
            // Add indexes for performance
            $table->index('vehicle_id');
            $table->index('division_id');
            $table->index('pks_id');
            
            // Add foreign key constraints
            $table->foreign('vehicle_id')->references('id')->on('vehicle_numbers')->onDelete('SET NULL');
            $table->foreign('division_id')->references('id')->on('divisions')->onDelete('SET NULL');
            $table->foreign('pks_id')->references('id')->on('pks')->onDelete('SET NULL');
        });

        Schema::table('sales', function (Blueprint $table) {
            // Add production_id foreign key
            $table->unsignedBigInteger('production_id')->nullable()->after('id');
            
            // Add index for performance
            $table->index('production_id');
            
            // Add foreign key constraint
            $table->foreign('production_id')->references('id')->on('production')->onDelete('SET NULL');
        });

        Schema::table('employees', function (Blueprint $table) {
            // Add foreign key columns
            $table->unsignedBigInteger('department_id')->nullable()->after('name');
            $table->unsignedBigInteger('position_id')->nullable()->after('department_id');
            $table->unsignedBigInteger('family_composition_id')->nullable()->after('grade');
            
            // Add indexes for performance
            $table->index('department_id');
            $table->index('position_id');
            $table->index('family_composition_id');
            
            // Add foreign key constraints
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('SET NULL');
            $table->foreign('position_id')->references('id')->on('positions')->onDelete('SET NULL');
            $table->foreign('family_composition_id')->references('id')->on('family_compositions')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['family_composition_id']);
            $table->dropForeign(['position_id']);
            $table->dropForeign(['department_id']);
            $table->dropIndex(['family_composition_id']);
            $table->dropIndex(['position_id']);
            $table->dropIndex(['department_id']);
            $table->dropColumn(['family_composition_id', 'position_id', 'department_id']);
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign(['production_id']);
            $table->dropIndex(['production_id']);
            $table->dropColumn('production_id');
        });

        Schema::table('production', function (Blueprint $table) {
            $table->dropForeign(['pks_id']);
            $table->dropForeign(['division_id']);
            $table->dropForeign(['vehicle_id']);
            $table->dropIndex(['pks_id']);
            $table->dropIndex(['division_id']);
            $table->dropIndex(['vehicle_id']);
            $table->dropColumn(['pks_id', 'division_id', 'vehicle_id']);
        });
    }
};