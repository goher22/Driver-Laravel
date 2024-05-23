<?php
namespace Database\Seeders;

use App\Permission;
use App\Role;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'id' => 1,
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'email_verified_at' => now(),
            'password' => Hash::make('admin'),
            'is_superadmin' => true,
        ]);

        $adminRole = Role::create([
            'id' => 1,
            'name' => 'admin',
            'guard_name' => 'web'
        ]);

        $permissions = array(
            ['name' => 'users_access', 'display_name' => 'Access', 'group_name' => 'Users', 'group_slug' => 'users', 'guard_name' => 'web'],
            ['name' => 'users_create', 'display_name' => 'Create', 'group_name' => 'Users', 'group_slug' => 'users', 'guard_name' => 'web'],
            ['name' => 'users_show', 'display_name' => 'Show', 'group_name' => 'Users', 'group_slug' => 'users', 'guard_name' => 'web'],
            ['name' => 'users_edit', 'display_name' => 'Edit', 'group_name' => 'Users', 'group_slug' => 'users', 'guard_name' => 'web'],
            ['name' => 'users_delete', 'display_name' => 'Delete', 'group_name' => 'Users', 'group_slug' => 'users', 'guard_name' => 'web'],
            ['name' => 'users_ban', 'display_name' => 'Ban/Activate User', 'group_name' => 'Users', 'group_slug' => 'users', 'guard_name' => 'web'],
            ['name' => 'users_activity', 'display_name' => 'Activity Log', 'group_name' => 'Users', 'group_slug' => 'users', 'guard_name' => 'web'],

            ['name' => 'vehicles_access', 'display_name' => 'Access', 'group_name' => 'Vehicles', 'group_slug' => 'vehicles', 'guard_name' => 'web'],
            ['name' => 'vehicles_create', 'display_name' => 'Create', 'group_name' => 'Vehicles', 'group_slug' => 'vehicles', 'guard_name' => 'web'],
            ['name' => 'vehicles_show', 'display_name' => 'Show', 'group_name' => 'Vehicles', 'group_slug' => 'vehicles', 'guard_name' => 'web'],
            ['name' => 'vehicles_edit', 'display_name' => 'Edit', 'group_name' => 'Vehicles', 'group_slug' => 'vehicles', 'guard_name' => 'web'],
            ['name' => 'vehicles_delete', 'display_name' => 'Delete', 'group_name' => 'Vehicles', 'group_slug' => 'vehicles', 'guard_name' => 'web'],

            ['name' => 'document_show', 'display_name' => 'Show', 'group_name' => 'Document', 'group_slug' => 'document', 'guard_name' => 'web'],

            ['name' => 'roles_access', 'display_name' => 'Access', 'group_name' => 'Roles', 'group_slug' => 'roles', 'guard_name' => 'web'],
            ['name' => 'roles_create', 'display_name' => 'Create', 'group_name' => 'Roles', 'group_slug' => 'roles', 'guard_name' => 'web'],
            ['name' => 'roles_show', 'display_name' => 'Show', 'group_name' => 'Roles', 'group_slug' => 'roles', 'guard_name' => 'web'],
            ['name' => 'roles_edit', 'display_name' => 'Edit', 'group_name' => 'Roles', 'group_slug' => 'roles', 'guard_name' => 'web'],
            ['name' => 'roles_delete', 'display_name' => 'Delete', 'group_name' => 'Roles', 'group_slug' => 'roles', 'guard_name' => 'web'],

            ['name' => 'permissions_access', 'display_name' => 'Access', 'group_name' => 'Permissions', 'group_slug' => 'permissions', 'guard_name' => 'web'],
            ['name' => 'permissions_create', 'display_name' => 'Create', 'group_name' => 'Permissions', 'group_slug' => 'permissions', 'guard_name' => 'web'],
            ['name' => 'permissions_show', 'display_name' => 'Show', 'group_name' => 'Permissions', 'group_slug' => 'permissions', 'guard_name' => 'web'],
            ['name' => 'permissions_edit', 'display_name' => 'Edit', 'group_name' => 'Permissions', 'group_slug' => 'permissions', 'guard_name' => 'web'],
            ['name' => 'permissions_delete', 'display_name' => 'Delete', 'group_name' => 'Permissions', 'group_slug' => 'permissions', 'guard_name' => 'web'],

            ['name' => 'activitylog_access', 'display_name' => 'Access', 'group_name' => 'Activity Log', 'group_slug' => 'activitylog', 'guard_name' => 'web'],
            ['name' => 'activitylog_show', 'display_name' => 'Show', 'group_name' => 'Activity Log', 'group_slug' => 'activitylog', 'guard_name' => 'web'],
            ['name' => 'activitylog_delete', 'display_name' => 'Delete', 'group_name' => 'Activity Log', 'group_slug' => 'activitylog', 'guard_name' => 'web'],
        );

        Permission::insert($permissions);

        $getPermissions = Permission::get();

        $assignPermissions = $getPermissions->map(function($item){
            return [$item->name];
        });

        $user->assignRole($adminRole);
        $adminRole->givePermissionTo($assignPermissions);


        $userRole = Role::create([
            'id' => 3,
            'name' => 'user',
            'guard_name' => 'web'
        ]);
        
        $assignUserPermissions = $getPermissions->map(function($item){
            $restrictedPerms = [
                'users_delete', 
                'users_ban', 
                'users_activity', 
                'roles_delete', 
                'permissions_delete', 
                'activitylog_delete'
            ];
            if(!in_array($item->name, $restrictedPerms)){
                return [$item->name];
            }
        });
        $userRole->givePermissionTo($assignUserPermissions);

        $driverRole = Role::create([
            'id' => 2,
            'name' => 'driver',
            'guard_name' => 'web'
        ]);

        $assignDriverPermissions = $getPermissions->map(function($item){
            $restrictedPerms = [
                'vehicles_access', 
                'vehicles_create',
                'vehicles_show',
                'vehicles_edit',
                'vehicles_delete'
            ];
            if(in_array($item->name, $restrictedPerms)){
                return [$item->name];
            }
        });
        $driverRole->givePermissionTo($assignDriverPermissions);
    }
}
