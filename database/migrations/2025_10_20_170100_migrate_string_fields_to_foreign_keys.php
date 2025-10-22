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
        // Migrate production data
        $this->migrateProductionData();
        
        // Migrate sales data
        $this->migrateSalesData();
        
        // Migrate employees data
        $this->migrateEmployeesData();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse migrations would be complex, so we'll leave the old columns for now
        // They can be cleaned up in a separate migration after verification
    }

    private function migrateProductionData(): void
    {
        // Get all production records
        $productions = DB::table('production')->get();
        
        foreach ($productions as $production) {
            $vehicleId = null;
            $divisionId = null;
            $pksId = null;

            // Find vehicle ID by number
            if (!empty($production->vehicle_number)) {
                $vehicle = DB::table('vehicle_numbers')
                    ->where('number', $production->vehicle_number)
                    ->first();
                if ($vehicle) {
                    $vehicleId = $vehicle->id;
                } else {
                    // Create new vehicle if not exists
                    $vehicleId = DB::table('vehicle_numbers')->insertGetId([
                        'number' => $production->vehicle_number,
                        'description' => 'Auto-migrated from production',
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // Find division ID by name
            if (!empty($production->division)) {
                $division = DB::table('divisions')
                    ->where('name', $production->division)
                    ->first();
                if ($division) {
                    $divisionId = $division->id;
                } else {
                    // Create new division if not exists
                    $divisionId = DB::table('divisions')->insertGetId([
                        'name' => $production->division,
                        'description' => 'Auto-migrated from production',
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // Find PKS ID by name
            if (!empty($production->pks)) {
                $pks = DB::table('pks')
                    ->where('name', $production->pks)
                    ->first();
                if ($pks) {
                    $pksId = $pks->id;
                } else {
                    // Create new PKS if not exists
                    $pksId = DB::table('pks')->insertGetId([
                        'name' => $production->pks,
                        'description' => 'Auto-migrated from production',
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // Update production record with new FK IDs
            DB::table('production')
                ->where('id', $production->id)
                ->update([
                    'vehicle_id' => $vehicleId,
                    'division_id' => $divisionId,
                    'pks_id' => $pksId,
                    'updated_at' => now(),
                ]);
        }
    }

    private function migrateSalesData(): void
    {
        // Get all sales records
        $sales = DB::table('sales')->get();
        
        foreach ($sales as $sale) {
            $productionId = null;

            // Find production ID by SP number
            if (!empty($sale->sp_number)) {
                $production = DB::table('production')
                    ->where('sp_number', $sale->sp_number)
                    ->first();
                if ($production) {
                    $productionId = $production->id;
                }
            }

            // Update sales record with production ID
            DB::table('sales')
                ->where('id', $sale->id)
                ->update([
                    'production_id' => $productionId,
                    'updated_at' => now(),
                ]);
        }
    }

    private function migrateEmployeesData(): void
    {
        // Get all employees records
        $employees = DB::table('employees')->get();
        
        foreach ($employees as $employee) {
            $departmentId = null;
            $positionId = null;
            $familyCompositionId = null;

            // Find department ID by name
            if (!empty($employee->department)) {
                $department = DB::table('departments')
                    ->where('name', $employee->department)
                    ->first();
                if ($department) {
                    $departmentId = $department->id;
                } else {
                    // Create new department if not exists
                    $departmentId = DB::table('departments')->insertGetId([
                        'name' => $employee->department,
                        'description' => 'Auto-migrated from employees',
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // Find position ID by name
            if (!empty($employee->position)) {
                $position = DB::table('positions')
                    ->where('name', $employee->position)
                    ->first();
                if ($position) {
                    $positionId = $position->id;
                } else {
                    // Create new position if not exists
                    $positionId = DB::table('positions')->insertGetId([
                        'name' => $employee->position,
                        'description' => 'Auto-migrated from employees',
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // Find family composition ID by number
            if (!empty($employee->family_composition)) {
                $familyComposition = DB::table('family_compositions')
                    ->where('number', $employee->family_composition)
                    ->first();
                if ($familyComposition) {
                    $familyCompositionId = $familyComposition->id;
                } else {
                    // Create new family composition if not exists
                    $familyCompositionId = DB::table('family_compositions')->insertGetId([
                        'number' => $employee->family_composition,
                        'description' => 'Auto-migrated from employees',
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // Update employee record with new FK IDs
            DB::table('employees')
                ->where('id', $employee->id)
                ->update([
                    'department_id' => $departmentId,
                    'position_id' => $positionId,
                    'family_composition_id' => $familyCompositionId,
                    'updated_at' => now(),
                ]);
        }
    }
};