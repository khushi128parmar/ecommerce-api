<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::firstOrCreate(['name' => 'admin']);

        $customer = Role::firstOrCreate(['name' => 'customer']);

        $permissions = [

            'dashboard-view',

            'category-list',
            'category-create',
            'category-edit',
            'category-delete',

            'brand-list',
            'brand-create',
            'brand-edit',
            'brand-delete',

            'product-list',
            'product-create',
            'product-edit',
            'product-delete',

            'order-list',
            'order-update',

            'coupon-list',
            'coupon-create',
            'coupon-edit',
            'coupon-delete',
        ];

        foreach ($permissions as $permission) {

            Permission::firstOrCreate([
                'name' => $permission
            ]);
        }

        $admin->givePermissionTo(Permission::all());
    }
}