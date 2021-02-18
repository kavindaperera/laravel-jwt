<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //rest cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Truncate tables
        DB::table('role_has_permissions')->delete();
        DB::table('model_has_permissions')->delete();
        DB::table('model_has_roles')->delete();
        DB::table('permissions')->delete();
        DB::table('roles')->delete();

        // Create Roles
        $admin_role = Role::create(['guard_name' => 'web', 'name' => 'admin']);
        $editor_role = Role::create(['guard_name' => 'web', 'name' => 'editor']);
        $customer_role = Role::create(['guard_name' => 'web', 'name' => 'customer']);
        $seller_role = Role::create(['guard_name' => 'web', 'name' => 'seller']);
        // TODO - Create new roles here
    }
}
