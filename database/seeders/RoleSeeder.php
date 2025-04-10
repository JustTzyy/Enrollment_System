<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            [
                'id' => 1,
                'role' => 'Admin',
                'description' => 'System administrator with full access',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'role' => 'Teacher',
                'description' => 'Can manage students and classes',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'role' => 'Student',
                'description' => 'Has access to learning materials and assignments',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'role' => 'Operator',
                'description' => 'Handles system operations and user management',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
