<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Role::where('name', 'ADMIN')->firstOrFail();

        $customer = Role::where('name', 'CUSTOMER')->firstOrFail();

        $adminPermissions = Permission::whereIn('name', [
            'products.view',
            'products.create',
            'products.update',
            'products.delete',
            'categories.view',
            'categories.create',
            'categories.update',
            'categories.delete',
        ])->pluck('id');

        $customerPermissions = Permission::whereIn('name', [
            'products.view',
            'categories.view',
        ])->pluck('id');

        $admin->permissions()->sync($adminPermissions);

        $customer->permissions()->sync($customerPermissions);
    }
}