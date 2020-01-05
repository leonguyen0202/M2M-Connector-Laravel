<?php

use App\Modules\Backend\Categories\Models\Category;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Faker\Factory;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        Category::create([
            'title' => 'ReactJs',
            'hex_color' => $faker->hexcolor(),
            'background_image' => random_image(['disk' => 'public', 'dir' => 'dummy/categories']),
            'description' => $faker->realtext(100),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Category::create([
            'title' => 'VueJs',
            'hex_color' => $faker->hexcolor(),
            'background_image' => random_image(['disk' => 'public', 'dir' => 'dummy/categories']),
            'description' => $faker->realtext(100),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Category::create([
            'title' => 'Internet of Things',
            'hex_color' => $faker->hexcolor(),
            'background_image' => random_image(['disk' => 'public', 'dir' => 'dummy/categories']),
            'description' => $faker->realtext(100),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Category::create([
            'title' => 'Laravel',
            'hex_color' => $faker->hexcolor(),
            'background_image' => random_image(['disk' => 'public', 'dir' => 'dummy/categories']),
            'description' => $faker->realtext(100),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Category::create([
            'title' => 'Code Igniter',
            'hex_color' => $faker->hexcolor(),
            'background_image' => random_image(['disk' => 'public', 'dir' => 'dummy/categories']),
            'description' => $faker->realtext(100),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Category::create([
            'title' => 'Symfony 4',
            'hex_color' => $faker->hexcolor(),
            'background_image' => random_image(['disk' => 'public', 'dir' => 'dummy/categories']),
            'description' => $faker->realtext(100),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Category::create([
            'title' => 'Web Programming',
            'hex_color' => $faker->hexcolor(),
            'background_image' => random_image(['disk' => 'public', 'dir' => 'dummy/categories']),
            'description' => $faker->realtext(100),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Category::create([
            'title' => 'MongoDB',
            'hex_color' => $faker->hexcolor(),
            'background_image' => random_image(['disk' => 'public', 'dir' => 'dummy/categories']),
            'description' => $faker->realtext(100),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Category::create([
            'title' => 'MySQL',
            'hex_color' => $faker->hexcolor(),
            'background_image' => random_image(['disk' => 'public', 'dir' => 'dummy/categories']),
            'description' => $faker->realtext(100),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Category::create([
            'title' => 'PostgreSQL',
            'hex_color' => $faker->hexcolor(),
            'background_image' => random_image(['disk' => 'public', 'dir' => 'dummy/categories']),
            'description' => $faker->realtext(100),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Category::create([
            'title' => 'Security in Computing',
            'hex_color' => $faker->hexcolor(),
            'background_image' => random_image(['disk' => 'public', 'dir' => 'dummy/categories']),
            'description' => $faker->realtext(100),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Category::create([
            'title' => 'Systems Administration',
            'hex_color' => $faker->hexcolor(),
            'background_image' => random_image(['disk' => 'public', 'dir' => 'dummy/categories']),
            'description' => $faker->realtext(100),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Category::create([
            'title' => 'User-Centered Design',
            'hex_color' => $faker->hexcolor(),
            'background_image' => random_image(['disk' => 'public', 'dir' => 'dummy/categories']),
            'description' => $faker->realtext(100),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Category::create([
            'title' => 'Data Communication',
            'hex_color' => $faker->hexcolor(),
            'background_image' => random_image(['disk' => 'public', 'dir' => 'dummy/categories']),
            'description' => $faker->realtext(100),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Category::create([
            'title' => 'Net-Centric Computing',
            'hex_color' => $faker->hexcolor(),
            'background_image' => random_image(['disk' => 'public', 'dir' => 'dummy/categories']),
            'description' => $faker->realtext(100),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Category::create([
            'title' => 'PHP',
            'hex_color' => $faker->hexcolor(),
            'background_image' => random_image(['disk' => 'public', 'dir' => 'dummy/categories']),
            'description' => $faker->realtext(100),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
