<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user for company data input
        User::factory()->create([
            'name' => 'Admin Perusahaan',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
        ]);

        // Create direksi user (Superadmin)
        User::factory()->create([
            'name' => 'Direksi Perusahaan',
            'email' => 'direksi@example.com',
            'password' => Hash::make('password'),
            'role' => 'direksi',
        ]);

        // Create direktur user (Director/Rektor)
        User::factory()->create([
            'name' => 'Direktur Perusahaan',
            'email' => 'direktur@example.com',
            'password' => Hash::make('password'),
            'role' => 'direksi',
        ]);
    }
}
