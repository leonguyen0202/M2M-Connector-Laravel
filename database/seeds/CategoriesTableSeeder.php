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
            'description' => 'React is a JavaScript library for building user interfaces. It is maintained by Facebook and a community of individual developers and companies.',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Category::create([
            'title' => 'VueJs',
            'hex_color' => $faker->hexcolor(),
            'background_image' => random_image(['disk' => 'public', 'dir' => 'dummy/categories']),
            'description' => 'Vue.js is an open-source Model–view–viewmodel JavaScript framework for building user interfaces and single-page applications. It is maintained by members from various companies such as Netlify',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Category::create([
            'title' => 'Internet of Things',
            'hex_color' => $faker->hexcolor(),
            'background_image' => random_image(['disk' => 'public', 'dir' => 'dummy/categories']),
            'description' => 'The Internet of Things, or IoT, is a system of interrelated computing devices, mechanical, and digital machines ... that are provided with unique identifiers (UIDs) and the ability to transfer data over a network.',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Category::create([
            'title' => 'Laravel',
            'hex_color' => $faker->hexcolor(),
            'background_image' => random_image(['disk' => 'public', 'dir' => 'dummy/categories']),
            'description' => 'Laravel is a free, open-source PHP web framework, created by Taylor Otwell and intended for the development of web applications following the model–view–controller architectural pattern and based on Symfony.',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Category::create([
            'title' => 'Code Igniter',
            'hex_color' => $faker->hexcolor(),
            'background_image' => random_image(['disk' => 'public', 'dir' => 'dummy/categories']),
            'description' => 'CodeIgniter is an open-source software rapid development web framework, for use in building dynamic web sites with PHP.',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Category::create([
            'title' => 'Symfony 4',
            'hex_color' => $faker->hexcolor(),
            'background_image' => random_image(['disk' => 'public', 'dir' => 'dummy/categories']),
            'description' => 'Symfony is a PHP web application framework and a set of reusable PHP components/libraries. It was published as free software on October 18, 2005 and released under the MIT license.',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Category::create([
            'title' => 'Web Programming',
            'hex_color' => $faker->hexcolor(),
            'background_image' => random_image(['disk' => 'public', 'dir' => 'dummy/categories']),
            'description' => 'This is an RMIT course that teaches web technologies such as HTML, CSS, JavaScript, Bootstrap 4, ReactJS, Redux, and MongoDB',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Category::create([
            'title' => 'MongoDB',
            'hex_color' => $faker->hexcolor(),
            'background_image' => random_image(['disk' => 'public', 'dir' => 'dummy/categories']),
            'description' => "MongoDB is a cross-platform document-oriented database program. Classified as a NoSQL database program, MongoDB uses JSON-like documents with schema",
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Category::create([
            'title' => 'MySQL',
            'hex_color' => $faker->hexcolor(),
            'background_image' => random_image(['disk' => 'public', 'dir' => 'dummy/categories']),
            'description' => "MySQL is an open-source relational database management system. Its name is a combination of 'MY', the name of co-founder Michael Widenius's daughter, and 'SQL', the abbreviation for Structured Query Language.",
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Category::create([
            'title' => 'PostgreSQL',
            'hex_color' => $faker->hexcolor(),
            'background_image' => random_image(['disk' => 'public', 'dir' => 'dummy/categories']),
            'description' => 'PostgreSQL, also known as Postgres, is a free and open-source relational database management system emphasizing extensibility and technical standards compliance.',
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
            'description' => 'This is an RMIT course that focuses on analyzing how user-friendly a software product is and teaches students about the professional process of usability testing.',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Category::create([
            'title' => 'Data Communication',
            'hex_color' => $faker->hexcolor(),
            'background_image' => random_image(['disk' => 'public', 'dir' => 'dummy/categories']),
            'description' => 'This is an RMIT course that focuses on teaching students how information travels around from the source to the destination, as well as teaching students how to design a professional network.',
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
            'description' => 'PHP is a general-purpose programming language originally designed for web development.',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
