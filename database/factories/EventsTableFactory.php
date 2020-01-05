<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Modules\Backend\Events\Models\Event;
use App\User;
use Carbon\Carbon;
use Faker\Factory;
use Faker\Generator as Faker;

$factory->define(Event::class, function (Faker $faker) {
    $author = User::inRandomOrder()->first();
    $faker->addProvider(new \Faker\Provider\Youtube($faker));
    return [
        'en_title' => $faker->realText(rand(10, 50)),
        'background_image' => random_image(['disk' => 'public', 'dir' => 'dummy/events']),
        'en_description' => $faker->realText(1000),
        'categories' => categories_seeder(),
        'author_id' => $author->id,
        'qr_code' => $faker->youtubeUri(),
        'participants' => participants_seeder(rand(1, 50)),
        'event_date' => Carbon::now()->addDays(rand(1, 4)),
        'promotion' => '0',
        'is_completed' => $faker->randomElement([0, 1]),

        'created_at' => Carbon::now()->subDays(rand(1, 29)),
        'updated_at' => Carbon::now(),
    ];
});
