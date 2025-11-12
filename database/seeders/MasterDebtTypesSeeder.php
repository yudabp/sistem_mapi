<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterDebtTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $debtTypes = [
            ['name' => 'Hutang Supplier', 'description' => 'Hutang kepada supplier pemasok barang', 'is_active' => true],
            ['name' => 'Hutang Bank', 'description' => 'Hutang kepada bank atau lembaga keuangan', 'is_active' => true],
            ['name' => 'Hutang Gaji Karyawan', 'description' => 'Hutang gaji atau tunjangan karyawan', 'is_active' => true],
            ['name' => 'Hutang Pihak Ketiga', 'description' => 'Hutang kepada pihak ketiga lainnya', 'is_active' => true],
            ['name' => 'Hutang Operasional', 'description' => 'Hutang terkait operasional harian', 'is_active' => true],
            ['name' => 'Hutang Lain-lain', 'description' => 'Hutang Lainnya', 'is_active' => true],
        ];

        DB::table('master_debt_types')->insert($debtTypes);
    }
}
