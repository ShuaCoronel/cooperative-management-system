<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Member;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        User::create([
        'name' => 'System Admin',
        'email' => 'admin_coop@gmail.com',
        'password' => Hash::make('admin123'),
        'role' => UserRole::ADMIN,

        ]);


        Member::create([
        'member_id_number' => 'MEMBER-2026-00001',
        'full_name' => 'John Doe',
        'date_of_birth' => '1111-01-1',
        'sex' => 'male',
        'civil_status' => 'single',
        'nationality' => 'filipino',
        'home_address' => '17 sulasok st.',
        'mobile_number' => '123456789',
        'email' => 'membertest@gmail.com',
        'valid_id_type' => 'Drivers License',
        'valid_id_number' => '081799',
        'tin' => '123-425-789',
        'occupation' => 'software engineer',
        'membership_status' => 'active',
        'date_joined' => '2026-6-12',



        ]);

    }
}
