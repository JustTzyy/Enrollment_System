<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Section;
use Faker\Factory as Faker;

class SectionSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $strandIDs = [4, 5, 6, 7, 8];

        for ($i = 0; $i < 20; $i++) {
            Section::create([
                'section' => 'Section ' . strtoupper($faker->bothify('??#')),
                'room' => 'Room ' . $faker->numberBetween(100, 500),
                'description' => $faker->sentence(),
                'strandID' => $faker->randomElement($strandIDs),
            ]);
        }
    }
}
