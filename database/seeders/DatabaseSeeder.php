<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Member;
use App\Models\SavingsAccount;
use App\Models\User;
use Carbon\Carbon;
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


        $john = User::create([
        'name' => 'John Doe',
        'email' => 'member_test@gmail.com',
        'password' => Hash::make('member123'),
        'role' => UserRole::MEMBER,

        ]);


        $johm_member = Member::create([  
        'user_id' => $john->id,
        'member_id_number' => 'MEMBER-2026-00001',
        'full_name' => 'John Doe',
        'date_of_birth' => '1111-01-1',
        'sex' => 'male',
        'civil_status' => 'single',
        'nationality' => 'filipino',
        'home_address' => '17 sulasok st.',
        'mobile_number' => '123456789',
        'email' => 'member_test@gmail.com',
        'valid_id_type' => 'Drivers License',
        'valid_id_number' => '081799',
        'tin' => '123-425-789',
        'occupation' => 'software engineer',
        'membership_status' => 'active',
        'date_joined' => '2026-6-12',



        ]);



        SavingsAccount::create([
            'member_id' => $johm_member->id,
            'account_number'    => '123-456-789',
            'product_type'      => 'regular',
            'status'            => 'active',
            'opened_at'         => Carbon::parse('2025-8-17')


        ]);

    }
}
