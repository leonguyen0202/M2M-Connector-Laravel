<?php

use App\Modules\Backend\Blogs\Models\Blog;
use App\User;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;

// use Illuminate\Support\Facades\DB;

class BlogsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // factory(Blog::class, 2000)->create();

        $faker = Factory::create();

        // DB::beginTransaction();
        for ($i = 0; $i < 500; $i++) {
            $author = User::inRandomOrder()->first();
            Blog::create([
                // 'title' => $faker->sentence(),
                'en_title' => $faker->unique()->realText(rand(10, 255)),
                'background_image' => random_image(['disk' => 'public', 'dir' => 'dummy/posts']),
                // 'background_image' => random_image(['disk' => 'web', 'dir' => 'img/frontend/upload/post']),
                'visits' => $faker->numberBetween($min = 1000, $max = 9000),
                // 'description' => $faker->paragraph(rand(50, 100), true),
                'en_description' => $faker->realText(500),
                'categories' => categories_seeder(),
                'author_id' => $author->id,

                'created_at' => Carbon::now()->subDays(rand(1, 29)),
                'updated_at' => Carbon::now(),
            ]);
        }
        // DB::comit();
    }
}
