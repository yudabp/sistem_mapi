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
            // DasboardTableSeeder::class,
            VehicleNumbersTableSeeder::class,
            DivisionsTableSeeder::class,
            PksTableSeeder::class,
            DepartmentsTableSeeder::class,
            PositionsTableSeeder::class,
            FamilyCompositionsTableSeeder::class,
            EmploymentStatusesTableSeeder::class,
            EmployeesTableSeeder::class,
            ProductionTableSeeder::class,
            SalesTableSeeder::class,
            MasterDebtTypesSeeder::class,
            MasterBkkExpenseCategoriesSeeder::class,
            // FinancialTransactionsTableSeeder::class,
            DebtsTableSeeder::class,
        ]);
    }
}
