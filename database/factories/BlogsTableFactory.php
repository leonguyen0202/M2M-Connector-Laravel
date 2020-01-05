<?php

/** 
 * @var \Illuminate\Database\Eloquent\Factory $factory 
 */

use App\Modules\Backend\Blogs\Models\Blog;
use App\User;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Blog::class, function (Faker $faker) {
    $author = User::inRandomOrder()->first();
    return [
        'en_title' => $faker->realText(rand(10, 50)),
        'en_description' => $faker->realText(500),
        'background_image' => random_image(['disk' => 'public', 'dir' => 'dummy/posts']),
        'visits' => $faker->numberBetween($min = 1000, $max = 9000),
        'categories' => categories_seeder(),
        'author_id' => $author->id,

        'created_at' => Carbon::now()->subDays(rand(1, 29)),
        'updated_at' => Carbon::now(),
    ];
});
