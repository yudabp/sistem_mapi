<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder50 extends Seeder
{
    /**
     * Seed the application's database with 50 records for each table.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            RoleAndPermissionSeeder::class,
            VehicleNumbersTableSeeder::class,
            DivisionsTableSeeder::class,
            PksTableSeeder::class,
            DepartmentsTableSeeder::class,
            PositionsTableSeeder::class,
            FamilyCompositionsTableSeeder::class,
            EmploymentStatusesTableSeeder::class,
            MasterDebtTypesSeeder::class,
            MasterBkkExpenseCategoriesSeeder::class,
            DebtsTableSeeder::class, // Keep original debts seeder
            // 50 records seeders
            EmployeesTableSeeder50::class,
            ProductionTableSeeder50::class,
            SalesTableSeeder50::class,
        ]);
    }
}