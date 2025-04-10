<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class RequirementsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $requirements = [
            ['name' => 'Personal Data Sheet', 'description' => 'Official personal data record'],
            ['name' => 'Enrollment Form', 'description' => 'Required for enrollment processing'],
            ['name' => 'Good Moral', 'description' => 'Certification of good conduct'],
            ['name' => 'Form 137', 'description' => 'Academic record from previous school'],
            ['name' => 'Birth Certificate', 'description' => 'Proof of identity and birth'],
            ['name' => 'Grade Card', 'description' => 'Latest report card'],
            ['name' => 'National Career Assessment Examination', 'description' => 'NCAE result for career guidance'],
        ];
        DB::table('requirements')->insert($requirements);

    }
}
