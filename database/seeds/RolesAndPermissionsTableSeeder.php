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

        Permission::create(['name' => 'access-settings']);

        Role::create(['name' => 'developer']);

        Role::create(['name' => 'super-admin',]);

        // $developerRole->givePermissionTo(Permission::where([
        //     ['name', '=', 'acceess-developer-settings']
        // ])->first());

        // $role->givePermissionTo(Permission::all());

        $admin = \App\User::where('email', 'admin@gmail.com')->first();

        $admin->assignRole('super-admin');

        $admin->assignRole('developer');

    }
}
