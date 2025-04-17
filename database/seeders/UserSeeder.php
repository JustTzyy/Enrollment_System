<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Address;
use App\Models\Guardian;
use App\Models\History;
use App\Models\StudentRequirement;
use App\Models\Requirement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
  public function run(): void
  {
    $faker = Faker::create();
    $requirementIDs = Requirement::pluck('id')->toArray();

    for ($i = 1; $i <= 15; $i++) {
      $firstName = $faker->firstName;
      $middleName = $faker->optional()->firstName;
      $lastName = $faker->lastName;

      // Ensure valid age
      $birthdayDate = Carbon::instance($faker->dateTimeBetween('-25 years', '-16 years'));
      $birthday = $birthdayDate->format('Y-m-d');
      $age = $birthdayDate->diffInYears(Carbon::now());


      $roleID = $faker->randomElement([3]); // randomly assign roles

      $user = User::create([
        'name' => $firstName . ($middleName ?? '') . $lastName,
        'email' => $faker->unique()->safeEmail,
        'password' => Hash::make($firstName .'123'),
        'firstName' => $firstName,
        'middleName' => $middleName,
        'lastName' => $lastName,
        'age' => $age,
        'birthday' => $birthday,
        'gender' => $faker->randomElement(['Male', 'Female', 'Other']),
        'contactNumber' => '09' . $faker->numberBetween(100000000, 999999999),
        'status' => $faker->randomElement(['active', 'inactive', 'Grade 11', 'Grade 12', 'Highschool Graduate']),
        'archive' => true,
        'roleID' => '3',
        'created_at' => now(),
        'updated_at' => now(),
      ]);

      // Address
      Address::create([
        'street' => $faker->streetAddress,
        'city' => $faker->city,
        'province' => $faker->state,
        'zipcode' => $faker->postcode,
        'userID' => $user->id,
      ]);

      // Guardian if student
      if ($roleID == 3) {
        Guardian::create([
          'firstName' => $faker->firstName,
          'middleName' => $faker->optional()->firstName,
          'lastName' => $faker->lastName,
          'contactNumber' => '09' . $faker->numberBetween(100000000, 999999999),
          'userID' => $user->id,
        ]);

        // Link random requirement(s)
        if (!empty($requirementIDs)) {
          $randomReqIDs = $faker->randomElements($requirementIDs, rand(1, 3));
          foreach ($randomReqIDs as $reqID) {
            StudentRequirement::create([
              'requirementID' => $reqID,
              'userID' => $user->id,
            ]);
          }
        }
      }

      // History
      History::create([
        'status' => 'Added',
        'userID' => $user->id,
      ]);
    }
  }
}
