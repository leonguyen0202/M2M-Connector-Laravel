<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Webpatser\Uuid\Uuid;

class RolesAndPermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'view-event']);
        Permission::create(['name' => 'create-event']);
        Permission::create(['name' => 'update-event']);
        Permission::create(['name' => 'delete-event']);

        Permission::create(['name' => 'view-user']);
        Permission::create(['name' => 'create-user']);
        Permission::create(['name' => 'update-user']);
        Permission::create(['name' => 'delete-user']);

        Permission::create(['name' => 'view-post']);
        Permission::create(['name' => 'create-post']);
        Permission::create(['name' => 'update-post']);
        Permission::create(['name' => 'delete-post']);

        Permission::create(['name' => 'view-categories']);
        Permission::create(['name' => 'create-categories']);
        Permission::create(['name' => 'update-categories']);
        Permission::create(['name' => 'delete-categories']);

        Permission::create(['name' => 'access-dashboard']);
        Permission::create(['name' => 'edit-any']);

        Permission::create(['name' => 'acceess-developer-settings']);

        $developerRole = Role::create([
            'name' => 'developer'
        ]);

        $developerRole->givePermissionTo(Permission::where([
            ['name', '=', 'acceess-developer-settings']
        ])->first());

        $role = Role::create([
            'name' => 'super-admin',
        ]);

        $role->givePermissionTo(Permission::all());

        $developer = \App\User::where('email', 'admin@gmail.com')->first();

        $developer->assignRole('super-admin');

        $developer->assignRole('developer');

    }
}
