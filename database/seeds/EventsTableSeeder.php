<?php

use App\Modules\Backend\Events\Models\Event;
// use App\User;
// use Carbon\Carbon;
// use Faker\Factory;
use Illuminate\Database\Seeder;
// use Illuminate\Support\Facades\DB;

class EventsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Event::class, 1000)->create();
        // $faker = Factory::create();
        // $faker->addProvider(new \Faker\Provider\Youtube($faker));

        // DB::beginTransaction();

        // for ($i = 0; $i < 1000; $i++) {
        //     $author = User::inRandomOrder()->first();
        //     Event::create([
        //         // 'title' => $faker->sentence(),
        //         'title' => $faker->realText(rand(10,50)),
        //         'background_image' => random_image(['disk' => 'public', 'dir' => 'dummy/events']),
        //         // 'description' => $faker->paragraph(rand(50, 100), true),
        //         'description' => $faker->realText(1000),
        //         'categories' => categories_seeder(),
        //         'author_id' => $author->id,

        //         // 'qr_code' => $faker->randomElement([null, $faker->url()]),
        //         'qr_code' => $faker->youtubeUri(),
        //         'participants' => participants_seeder(rand(1,50)),
        //         'event_date' => Carbon::now()->addDays(rand(1, 4)),

        //         'promotion' => '0',
        //         'is_completed' => $faker->randomElement([0, 1]),

        //         'created_at' => Carbon::now()->subDays(rand(1, 29)),
        //         'updated_at' => Carbon::now(),
        //     ]);
        // }

        // DB::commit();

        for ($i = 0; $i < 3; $i++) {
            $event = Event::query()->where([
                'is_completed' => '0'
            ])->inRandomOrder()->first();

            $event->promotion = '1';

            $event->save();
        }
    }
}
