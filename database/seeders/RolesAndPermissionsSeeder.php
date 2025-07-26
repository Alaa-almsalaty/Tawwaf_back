<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{


    public function run()
    {
        // Create permissions
        //$createUsers = Permission::firstOrCreate(['name' => 'create users']);

        $viewUsers = Permission::firstOrCreate(['name' => 'view users']);
        $viewAnyUsers = Permission::firstOrCreate(['name' => 'view_any users']);

        // Create roles
        $superadmin = Role::firstOrCreate(['name' => 'superadmin']);
        //$manager = Role::firstOrCreate(['name' => 'manager']);

        // Assign permissions
        //$manager->givePermissionTo($createUsers);
        //$manager->givePermissionTo($viewUsers);
       // $manager->givePermissionTo($viewAnyUsers);
        // Optionally assign all permissions to superadmin
        $superadmin->givePermissionTo(Permission::all());
    }

}
