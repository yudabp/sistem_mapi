<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            RoleAndPermissionSeeder::class,
            // DasboardTableSeeder::class,
            VehicleNumbersTableSeeder::class,
            DivisionsTableSeeder::class,
            PksTableSeeder::class,
            DepartmentsTableSeeder::class,
            PositionsTableSeeder::class,
            FamilyCompositionsTableSeeder::class,
            EmploymentStatusesTableSeeder::class,
            MasterDebtTypesSeeder::class,
            MasterBkkExpenseCategoriesSeeder::class,
            // Uncomment the next line if you want to use the 50-record seeders instead of basic ones
            // EmployeesTableSeeder50::class, ProductionTableSeeder50::class, SalesTableSeeder50::class,
            // Or use the basic seeders
            EmployeesTableSeeder::class,
            ProductionTableSeeder::class,
            SalesTableSeeder::class,
            // FinancialTransactionsTableSeeder::class,
            DebtsTableSeeder::class,
        ]);
    }
}
