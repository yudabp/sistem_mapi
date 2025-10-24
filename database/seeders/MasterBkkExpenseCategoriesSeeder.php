<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterBkkExpenseCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Operasional', 'description' => 'Biaya operasional harian kebun', 'is_debt_payment' => false, 'is_active' => true],
            ['name' => 'Maintenance', 'description' => 'Biaya perawatan alat dan infrastruktur', 'is_debt_payment' => false, 'is_active' => true],
            ['name' => 'Pembelian Pupuk', 'description' => 'Pembelian pupuk dan pestisida', 'is_debt_payment' => false, 'is_active' => true],
            ['name' => 'Gaji Karyawan', 'description' => 'Pembayaran gaji dan tunjangan karyawan', 'is_debt_payment' => false, 'is_active' => true],
            ['name' => 'Pembayaran Hutang', 'description' => 'Khusus untuk pembayaran cicilan hutang', 'is_debt_payment' => true, 'is_active' => true],
            ['name' => 'Transportasi', 'description' => 'Biaya transportasi dan pengiriman', 'is_debt_payment' => false, 'is_active' => true],
            ['name' => 'Listrik & Air', 'description' => 'Biaya utilitas listrik dan air', 'is_debt_payment' => false, 'is_active' => true],
            ['name' => 'Lain-lain', 'description' => 'Biaya lain-lain yang tidak terkategori', 'is_debt_payment' => false, 'is_active' => true],
        ];

        DB::table('master_bkk_expense_categories')->insert($categories);
    }
}
