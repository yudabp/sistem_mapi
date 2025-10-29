<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Create permissions
        $permissions = [
            'view-data' => 'Can view all data',
            'export-data' => 'Can export data',
            'manage-users' => 'Can manage users',
            'manage-production' => 'Can manage production data',
            'manage-sales' => 'Can manage sales data',
            'manage-employees' => 'Can manage employee data',
            'manage-financial' => 'Can manage financial data',
            'manage-debts' => 'Can manage debt data',
            'full-access' => 'Full access to all features',
        ];

        foreach ($permissions as $name => $description) {
            Permission::firstOrCreate([
                'name' => $name,
            ], [
                'guard_name' => 'web',
            ]);
        }

        // Create roles
        $direksiRole = Role::firstOrCreate([
            'name' => 'direksi',
        ], [
            'guard_name' => 'web',
        ]);

        $superadminRole = Role::firstOrCreate([
            'name' => 'superadmin',
        ], [
            'guard_name' => 'web',
        ]);

        // Assign permissions to Direksi role
        $direksiRole->givePermissionTo([
            'view-data',
            'export-data',
        ]);

        // Assign all permissions to Superadmin role
        $superadminRole->givePermissionTo(Permission::all());

        // Assign roles to existing users (first user gets superadmin, others get direksi)
        $users = User::all();

        foreach ($users as $index => $user) {
            if ($index === 0) {
                // First user becomes superadmin
                $user->assignRole('superadmin');
            } else {
                // Other users become direksi
                $user->assignRole('direksi');
            }
        }

        $this->command->info('Roles and permissions created successfully!');
        $this->command->info('Users have been assigned roles.');
    }
}