<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'manage settings',
            'manage pages',
            'manage posts',
            'manage users',
            'manage all rpps',
            'create rpp',
            'view rpp',
            'delete rpp',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        // Create roles and assign permissions
        $adminRole = Role::findOrCreate('admin');
        $adminRole->givePermissionTo(Permission::all());

        $userRole = Role::findOrCreate('user');
        $userRole->givePermissionTo(['create rpp', 'view rpp', 'delete rpp']);

        // Alias client role for backward compatibility
        $clientRole = Role::findOrCreate('client');
        $clientRole->givePermissionTo(['create rpp', 'view rpp', 'delete rpp']);
    }
}
