<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $permissions = [
            'products.view',
            'products.create',
            'products.update',
            'products.delete',

            'categories.view',
            'categories.create',
            'categories.update',
            'categories.delete',
        ];

        forEach($permissions as $permission){
            Permission::firstOrCreate([
                'name' => $permission,
            ]);
        }
    }
}
