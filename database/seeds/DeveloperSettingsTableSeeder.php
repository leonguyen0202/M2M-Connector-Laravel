<?php

use Illuminate\Database\Seeder;

use App\Modules\Backend\DeveloperSettings\Models\Developer;

class DeveloperSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Developer::create([
            'type' => 'upload',
            'details' => [
                'default' => [
                    'disk' => [
                        'default'
                    ],
                    'path' => [
                        'images/upload/'
                    ]
                ],
                'posts' => [
                    'disk' => [
                        'default'
                    ],
                    'path' => [
                        'images/upload/posts/'
                    ]
                ],
                'events' => [
                    'disk' => [
                        'default'
                    ],
                    'path' => [
                        'images/upload/events/'
                    ]
                ],
            ]
        ]);
    }
}
