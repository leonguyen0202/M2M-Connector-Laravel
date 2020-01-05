<?php

use App\User;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        User::create([
            'name' => $faker->unique()->firstName(),
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123456'),
            'about' => $faker->realText(255),

            'verifyToken' => null,
            'is_active' => 1,
            'avatar' => random_image(['disk' => 'public', 'dir' => 'dummy/avatars']),

            'remember_token' => Str::random(60),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        foreach (range(1, 99) as $i) {
            User::create([
                // 'id' => \Webpatser\Uuid\Uuid::generate(4),
                'name' => $faker->unique()->firstName(),
                'email' => $faker->unique()->email,
                'password' => Hash::make('password'),
                'about' => $faker->realText(255),

                'is_active' => '1',
                'verifyToken' => '',
                'avatar' => random_image(['disk' => 'public', 'dir' => 'dummy/avatars']),

                'remember_token' => Str::random(40),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
