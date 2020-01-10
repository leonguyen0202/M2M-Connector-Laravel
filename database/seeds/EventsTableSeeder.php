<?php

use App\Modules\Backend\Events\Models\Event;
use App\User;
use Carbon\Carbon;
use Faker\Factory;
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
        // factory(Event::class, 1000)->create();
        $faker = Factory::create();
        $faker->addProvider(new \Faker\Provider\Youtube($faker));

        // DB::beginTransaction();

        for ($i = 0; $i < 200; $i++) {
            $author = User::inRandomOrder()->first();
            Event::create([
                'en_title' => $faker->unique()->realText(rand(10, 100)),
                'background_image' => random_image(['disk' => 'public', 'dir' => 'dummy/events']),
                'en_description' => $faker->realText(1000),
                'categories' => categories_seeder(),
                'author_id' => $author->id,
                'qr_code' => $faker->youtubeUri(),
                'participants' => participants_seeder(rand(1, 50)),
                
                'type' => $faker->randomElement(['member', 'event']),
                'start' => Carbon::now()->addDays(rand(5, 10)),
                'end' => Carbon::now()->addDays(rand(11, 20)),
                
                // 'event_date' => $faker->randomElement([ Carbon::now()->subDays(rand(1, 20)) , Carbon::now()->addDays(rand(1, 60)) ]),
                'promotion' => '0',
                'is_completed' => $faker->randomElement([0, 1]),

                'created_at' => Carbon::now()->subDays(rand(1, 29)),
                'updated_at' => Carbon::now(),
            ]);
        }

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
