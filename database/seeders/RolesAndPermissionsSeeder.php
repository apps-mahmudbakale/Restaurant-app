<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       // Reset cached roles and permissions
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'create',
            'read',
            'update',
            'delete',
        ];
        $roles = [
            'admin',
            'waiter',
            'cashier',
            'cooker'
        ];
        $entities = [
            'users',
            'roles',
            'meals',
            'sales'
        ];

        foreach ($permissions as $permission) {
            foreach ($entities as $entity) {
                 Permission::create(['name' => $permission.'-'.$entity ]);
            }
           
        }

        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }

        $role = Role::findByName('admin');
        $role->givePermissionTo(Permission::all());
        $userRole = User::find(1)->assignRole('admin');

                   // // create permissions
                   // Permission::create(['name' => 'edit articles']);
                   // Permission::create(['name' => 'delete articles']);
                   // Permission::create(['name' => 'publish articles']);
                   // Permission::create(['name' => 'unpublish articles']);

                   // // create roles and assign created permissions

                   // // this can be done as separate statements
                   // $role = Role::create(['name' => 'writer']);
                   // $role->givePermissionTo('edit articles');

                   // // or may be done by chaining
                   // $role = Role::create(['name' => 'moderator'])
                   //     ->givePermissionTo(['publish articles', 'unpublish articles']);

                   // $role = Role::create(['name' => 'super-admin']);
                   // $role->givePermissionTo(Permission::all());
}


        }
