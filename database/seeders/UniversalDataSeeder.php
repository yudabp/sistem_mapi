<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UniversalDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Menambahkan 100 data untuk semua tabel...');

        // Production - 100 data
        $this->command->info('Menambahkan 100 Production data...');
        $productions = [];
        for ($i = 1; $i <= 100; $i++) {
            $productions[] = [
                'transaction_number' => 'TRX' . date('Ym') . str_pad($i, 4, '0', STR_PAD_LEFT),
                'date' => Carbon::now()->subDays(rand(0, 90)),
                'sp_number' => 'SP' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'vehicle_number' => "BK " . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT) . chr(65 + rand(0, 25)),
                'tbs_quantity' => rand(5000, 15000) + (rand(0, 99) / 100),
                'kg_quantity' => rand(1000, 5000) + (rand(0, 99) / 100),
                'division' => 'Afdeling ' . chr(65 + rand(0, 14)),
                'pks' => 'PKS ' . chr(65 + rand(0, 11)),
                'sp_photo_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('production')->insertOrIgnore($productions);

        // Sales - 100 data
        $this->command->info('Menambahkan 100 Sales data...');
        $sales = [];
        for ($i = 1; $i <= 100; $i++) {
            $kg_quantity = rand(1000, 5000) + (rand(0, 99) / 100);
            $price_per_kg = rand(8000, 12000) + (rand(0, 99) / 100);

            $sales[] = [
                'sp_number' => 'SP' . str_pad($i + 1000, 6, '0', STR_PAD_LEFT),
                'tbs_quantity' => rand(5000, 15000) + (rand(0, 99) / 100),
                'kg_quantity' => $kg_quantity,
                'price_per_kg' => $price_per_kg,
                'total_amount' => $kg_quantity * $price_per_kg,
                'sales_proof_path' => null,
                'sale_date' => Carbon::now()->subDays(rand(0, 90)),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('sales')->insertOrIgnore($sales);

        // Vehicle Numbers - 100 data
        $this->command->info('Menambahkan 100 Vehicle Numbers data...');
        $vehicles = [];
        for ($i = 1; $i <= 100; $i++) {
            $vehicles[] = [
                'number' => "BK " . str_pad($i + 3000, 4, '0', STR_PAD_LEFT) . chr(65 + ($i % 26)),
                'description' => "Kendaraan " . ($i + 3000),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('vehicle_numbers')->insertOrIgnore($vehicles);

        // Employees - 100 data
        $this->command->info('Menambahkan 100 Employees data...');
        $employees = [];
        $firstNames = ['Ahmad', 'Budi', 'Chandra', 'Dedi', 'Eko', 'Fajar', 'Gunawan', 'Hendra', 'Iwan', 'Joko'];
        $lastNames = ['Siregar', 'Panggabean', 'Nababan', 'Sinaga', 'Simanjuntak', 'Purba', 'Damanik', 'Tarigan', 'Barus', 'Tobing'];

        for ($i = 1; $i <= 100; $i++) {
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];

            $employees[] = [
                'ndp' => 'ND' . str_pad($i + 300, 5, '0', STR_PAD_LEFT),
                'name' => $firstName . ' ' . $lastName,
                'department' => 'Produksi',
                'position' => 'Staff',
                'grade' => 'A1',
                'family_composition' => rand(1, 6),
                'monthly_salary' => rand(3000000, 8000000) + (rand(0, 999) / 100),
                'status' => 'active',
                'hire_date' => Carbon::now()->subDays(rand(30, 1825)),
                'phone' => "0812-" . str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT),
                'email' => strtolower($firstName) . '.' . strtolower($lastName) . ($i + 300) . '@company.com',
                'address' => "Address " . ($i + 300),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('employees')->insertOrIgnore($employees);

        // Master Data tambahan (dibuat lebih dulu untuk foreign key)
        $this->command->info('Menambahkan master data tambahan...');

        // Master Debt Types (dibuat dulu untuk keperluan tabel debts)
        $master_debt_types = [];
        $debt_type_names = ['Hutang Supplier', 'Hutang Bank', 'Hutang Karyawan', 'Hutang Pajak', 'Hutang Lainnya'];
        for ($i = 0; $i < count($debt_type_names); $i++) {
            $master_debt_types[] = [
                'name' => $debt_type_names[$i],
                'description' => 'Jenis hutang ' . $debt_type_names[$i],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('master_debt_types')->insertOrIgnore($master_debt_types);

        // Master BKK Expense Categories (dibuat dulu untuk keperluan tabel buku_kas_kebun)
        $master_expense_categories = [];
        $expense_names = ['Biaya Operasional', 'Gaji Buruh', 'Pupuk', 'Pestisida', 'Maintenance Alat', 'Bahan Bakar', 'Transportasi', 'Administrasi'];
        for ($i = 0; $i < count($expense_names); $i++) {
            $master_expense_categories[] = [
                'name' => $expense_names[$i],
                'description' => 'Kategori pengeluaran ' . $expense_names[$i],
                'is_debt_payment' => rand(0, 1),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('master_bkk_expense_categories')->insertOrIgnore($master_expense_categories);

        // Debts - 100 data (DH - Data Hutang)
        $this->command->info('Menambahkan 100 Debts data...');
        $creditors = ['Bank BCA', 'Bank Mandiri', 'Supplier PT. Maju Jaya', 'Supplier CV. Mitra', 'Karyawan'];
        $statuses = ['unpaid', 'paid'];

        for ($i = 1; $i <= 100; $i++) {
            $amount = rand(5000000, 50000000);
            $sisa_hutang = rand(0, $amount);
            $cicilan = $sisa_hutang > 0 ? rand(500000, 5000000) : 0;

            DB::table('debts')->insertOrIgnore([
                'amount' => $amount,
                'sisa_hutang' => $sisa_hutang,
                'cicilan_per_bulan' => $cicilan,
                'creditor' => $creditors[array_rand($creditors)],
                'due_date' => Carbon::now()->addDays(rand(30, 365)),
                'description' => 'Hutang ' . $creditors[array_rand($creditors)] . ' ' . $i,
                'debt_type_id' => rand(1, 5),
                'employee_id' => rand(1, 50),
                'status' => $statuses[array_rand($statuses)],
                'paid_date' => rand(0, 1) ? Carbon::now()->subDays(rand(1, 30)) : null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Keuangan Perusahaan - 100 data (KP - Keuangan Perusahaan)
        $this->command->info('Menambahkan 100 Keuangan Perusahaan data...');
        $transaction_types = ['income', 'expense'];
        $categories = ['Penjualan TBS', 'Operasional', 'Pembelian', 'Gaji Karyawan', 'Maintenance', 'Bahan Bakar', 'Pajak', 'Asuransi'];
        $sources = ['PT. Maju Jaya', 'CV. Mitra', 'Bank BCA', 'Tunai', 'Transfer'];

        for ($i = 1; $i <= 100; $i++) {
            $type = $transaction_types[array_rand($transaction_types)];
            $amount = rand(1000000, 25000000);

            DB::table('keuangan_perusahaan')->insertOrIgnore([
                'transaction_date' => Carbon::now()->subDays(rand(0, 90)),
                'transaction_number' => 'KP' . date('Ym') . str_pad($i, 4, '0', STR_PAD_LEFT),
                'transaction_type' => $type,
                'amount' => $amount,
                'source_destination' => $sources[array_rand($sources)],
                'received_by' => 'Admin ' . rand(1, 5),
                'category' => $categories[array_rand($categories)],
                'notes' => 'Transaksi ' . ($type == 'income' ? 'Pemasukan' : 'Pengeluaran') . ' ' . $i,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Buku Kas Kebun - 100 data (BKK - Buku Kas Kebun)
        $this->command->info('Menambahkan 100 Buku Kas Kebun data...');
        $cash_types = ['income', 'expense'];
        $cash_categories = ['Penjualan Hasil Panen', 'Biaya Operasional', 'Gaji Buruh', 'Pupuk', 'Pestisida', 'Maintenance Alat'];
        $sources = ['Penjualan TBS', 'Kas Besar', 'Bank', 'Tunai'];

        for ($i = 1; $i <= 100; $i++) {
            $type = $cash_types[array_rand($cash_types)];
            $amount = rand(500000, 15000000);

            DB::table('buku_kas_kebun')->insertOrIgnore([
                'transaction_date' => Carbon::now()->subDays(rand(0, 90)),
                'transaction_number' => 'BKK' . date('Ym') . str_pad($i, 4, '0', STR_PAD_LEFT),
                'transaction_type' => $type,
                'amount' => $amount,
                'source_destination' => $sources[array_rand($sources)],
                'received_by' => 'Kasir ' . rand(1, 3),
                'category' => $cash_categories[array_rand($cash_categories)],
                'notes' => 'Transaksi Kas ' . ($type == 'income' ? 'Pemasukan' : 'Pengeluaran') . ' ' . $i,
                'expense_category_id' => $type == 'expense' ? rand(1, 8) : null,
                'debt_id' => $type == 'expense' && rand(0, 1) ? rand(1, 100) : null,
                'kp_id' => rand(0, 1) ? rand(1, 100) : null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Users - 100 data (Manajemen User)
        $this->command->info('Menambahkan 100 Users data...');
        $users = [];
        $user_first_names = ['Admin', 'User', 'Manager', 'Supervisor', 'Staff'];
        $user_last_names = ['One', 'Two', 'Three', 'Four', 'Five'];
        $roles = ['admin', 'user', 'superadmin'];

        for ($i = 1; $i <= 100; $i++) {
            $firstName = $user_first_names[array_rand($user_first_names)];
            $lastName = $user_last_names[array_rand($user_last_names)];
            $role = $roles[array_rand($roles)];

            $users[] = [
                'name' => $firstName . ' ' . $lastName . ' ' . $i,
                'email' => strtolower($firstName . $lastName . $i) . '@example.com',
                'password' => Hash::make('password'),
                'role' => $role,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('users')->insertOrIgnore($users);

        // Master Data tambahan
        $this->command->info('Menambahkan master data tambahan...');

        // Divisions
        $divisions = [];
        for ($i = 1; $i <= 10; $i++) {
            $divisions[] = [
                'name' => 'Afdeling ' . chr(64 + $i),
                'description' => 'Division ' . $i,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('divisions')->insertOrIgnore($divisions);

        // Departments
        $departments = [];
        $dept_names = ['Produksi', 'Quality Control', 'Logistik', 'Keuangan', 'HRD'];
        for ($i = 1; $i <= 10; $i++) {
            $departments[] = [
                'name' => $dept_names[array_rand($dept_names)] . ' ' . $i,
                'description' => 'Department ' . $i,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('departments')->insertOrIgnore($departments);

        // Positions
        $positions = [];
        $pos_names = ['Manager', 'Supervisor', 'Staff', 'Operator', 'Leader'];
        for ($i = 1; $i <= 10; $i++) {
            $positions[] = [
                'name' => $pos_names[array_rand($pos_names)] . ' ' . $i,
                'description' => 'Position ' . $i,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('positions')->insertOrIgnore($positions);

        // PKS
        $pks = [];
        for ($i = 1; $i <= 10; $i++) {
            $pks[] = [
                'name' => 'PKS ' . chr(64 + $i),
                'description' => 'Pabrik Kelapa Sawit ' . chr(64 + $i) . ' - Location ' . $i,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('pks')->insertOrIgnore($pks);

        // Family Compositions
        $family_compositions = [];
        $family_types = ['Suami', 'Istri', 'Anak', 'Orang Tua'];
        for ($i = 1; $i <= 10; $i++) {
            $family_compositions[] = [
                'name' => $family_types[array_rand($family_types)] . ' ' . $i,
                'description' => 'Family Member ' . $i,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('family_compositions')->insertOrIgnore($family_compositions);

        // Employment Statuses
        $employment_statuses = [];
        $status_types = ['Kontrak', 'Tetap', 'Probation', 'Magang'];
        for ($i = 1; $i <= 10; $i++) {
            $employment_statuses[] = [
                'name' => $status_types[array_rand($status_types)] . ' ' . $i,
                'description' => 'Status ' . $i,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('employment_statuses')->insertOrIgnore($employment_statuses);

        $this->command->info('✅ Semua data berhasil ditambahkan!');
        $this->command->info('');
        $this->command->info('Total data sekarang:');
        $this->command->info('- Production: ' . DB::table('production')->count());
        $this->command->info('- Sales: ' . DB::table('sales')->count());
        $this->command->info('- Employees: ' . DB::table('employees')->count());
        $this->command->info('- Vehicle Numbers: ' . DB::table('vehicle_numbers')->count());
        $this->command->info('- Debts: ' . DB::table('debts')->count());
        $this->command->info('- Keuangan Perusahaan (KP): ' . DB::table('keuangan_perusahaan')->count());
        $this->command->info('- Buku Kas Kebun (BKK): ' . DB::table('buku_kas_kebun')->count());
        $this->command->info('- Users: ' . DB::table('users')->count());
        $this->command->info('- Divisions: ' . DB::table('divisions')->count());
        $this->command->info('- Departments: ' . DB::table('departments')->count());
        $this->command->info('- Positions: ' . DB::table('positions')->count());
        $this->command->info('- PKS: ' . DB::table('pks')->count());
        $this->command->info('- Family Compositions: ' . DB::table('family_compositions')->count());
        $this->command->info('- Employment Statuses: ' . DB::table('employment_statuses')->count());
    }
}