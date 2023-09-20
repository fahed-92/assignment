<?php

namespace Database\Seeders;

use App\Models\Course;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        for ($x = 0; $x < 10; $x++) {
            Course::create([
                'name' => $faker->title(),
                'description' => $faker->paragraph(3),
                'image' => $faker->imageUrl(),
                'teacher_id' => rand(1 , 5)
            ]);
        }
    }
}
