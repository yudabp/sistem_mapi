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
            DasboardTableSeeder::class,
            EmployeesTableSeeder::class,
            ProductionTableSeeder::class,
            SalesTableSeeder::class,
            FinancialTransactionsTableSeeder::class,
            DebtsTableSeeder::class,
        ]);
    }
}
