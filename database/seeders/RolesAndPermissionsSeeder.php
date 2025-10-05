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
        $createUsers = Permission::firstOrCreate(['name' => 'create users']);

        $viewUsers = Permission::firstOrCreate(['name' => 'view users']);
        $viewAnyUsers = Permission::firstOrCreate(['name' => 'view_any users']);
        $updateUsers = Permission::firstOrCreate(['name' => 'update users']);

        $viewPackages = Permission::firstOrCreate(['name' => 'view packages']);
        $viewAnyPackages = Permission::firstOrCreate(['name' => 'viewAny packages']);
        $createPackages = Permission::firstOrCreate(['name' => 'create packages']);
        $updatePackages = Permission::firstOrCreate(['name' => 'update packages']);
        $deletePackages = Permission::firstOrCreate(['name' => 'delete packages']);
        $restorePackages = Permission::firstOrCreate(['name' => 'restore packages']);
        $forceDeletePackages = Permission::firstOrCreate(['name' => 'force_delete packages']);

        // Create roles
        $superadmin = Role::firstOrCreate(['name' => 'super']);
        $manager = Role::firstOrCreate(['name' => 'manager']);
        $employee = Role::firstOrCreate(['name' => 'employee']);
        $visitor = Role::firstOrCreate(['name' => 'visitor']);

        // Assign permissions
        $manager->givePermissionTo($createUsers);
        $manager->givePermissionTo($viewUsers);
        $manager->givePermissionTo($updateUsers);
        $manager->givePermissionTo($viewPackages);
        $manager->givePermissionTo($viewAnyPackages);
        $manager->givePermissionTo($createPackages);
        $manager->givePermissionTo($updatePackages);
        $manager->givePermissionTo($deletePackages);
        $manager->givePermissionTo($restorePackages);
        $manager->givePermissionTo($forceDeletePackages);
        $manager->givePermissionTo($viewAnyUsers);
        $employee->givePermissionTo($updateUsers);



        // Optionally assign all permissions to superadmin
        $superadmin->givePermissionTo(Permission::all());
    }

}
