<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $this->call(DeveloperSettingsTableSeeder::class);
        $this->call(LocalizationTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(RolesAndPermissionsTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);
        // $this->call(BlogsTableSeeder::class);
        $this->call(EventsTableSeeder::class);
        Model::reguard();
    }
}
