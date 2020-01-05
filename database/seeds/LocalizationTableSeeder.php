<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LocalizationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('localization')->insert([
            'locale_code' => 'en',
            'locale_name' => 'English',
            'locale_icon' => 'english.png',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('localization')->insert([
            'locale_code' => 'vi',
            'locale_name' => 'Vietnamese',
            'locale_icon' => 'vietnamese.png',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
