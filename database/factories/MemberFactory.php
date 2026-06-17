<?php

namespace Database\Factories;

use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Auth\User;

/**
 * @extends Factory<Member>
 */
class MemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [


            //
            'user_id'           => User::factory(), 
            
            // The dynamic, crash-proof Business ID we just discussed
            'member_id_number'  => 'MEMBER-' . date('Y') . '-' . str_pad($this->faker->unique()->numberBetween(1, 99999), 5, '0', STR_PAD_LEFT),
            
            // Standard Profile Data
            'full_name'         => $this->faker->name(),
            'date_of_birth'     => $this->faker->dateTimeBetween('-60 years', '-18 years')->format('Y-m-d'),
            'sex'               => $this->faker->randomElement(['male', 'female']),
            'civil_status'      => $this->faker->randomElement(['single', 'married', 'widowed', 'separated']),
            'nationality'       => 'Filipino',
            'home_address'      => $this->faker->address(),
            'mobile_number'     => $this->faker->numerify('09#########'),
            'email'             => $this->faker->unique()->safeEmail(),
            
            // KYC / Compliance Data
            'valid_id_type'     => $this->faker->randomElement(['UMID', 'Driver\'s License', 'Passport', 'Voter\'s ID']),
            'valid_id_number'   => $this->faker->bothify('ID-####-????'),
            'tin'               => $this->faker->numerify('###-###-###-000'),
            'occupation'        => $this->faker->jobTitle(),
            'date_joined'       => $this->faker->date(),
            
            // Enums matching your database setup
            'membership_status' => 'active',
            'membership_type'   => $this->faker->randomElement(['regular', 'associate']),



        ];
    }
}
