<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Member;
use App\Models\LoanProduct;
use App\Models\Loan;
use App\Models\LoanSchedule;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class LoanTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Get or Create an Audit User (For 'created_by' fields)
        $user = User::where('role','=','admin')->first() ?? User::create([
            'name' => 'System Administrator',
            'email' => 'admin@coop.com',
            'password' => bcrypt('password'), // Matches schema field name
            'role' => 'admin',
        ]);

       

        // 2. Get or Create a Member 
        // Member::first() ?? use this coalescense if no data yet
        $member =  Member::create([
            // 'member_id_number' => 'MEMBER-2026-001',
            'full_name' => 'Dianne Camille',
            'date_of_birth' => '1999-08-17',
            'sex' => 'female',
            'civil_status' => 'single',
            'nationality' => 'Filipino',
            'home_address' => 'Sulasok Street, Angeles City',
            'mobile_number' => '09123456789',
            'date_joined' => now()->toDateString(),
            'membership_status' => 'active',
            'membership_type' => 'regular',
            'valid_id_type' => 'Drivers License',
            'valid_id_number' => 'N01-XX-XXXXX',
            'occupation'      => 'Business Woman'
        ]);

        // 3. Get or Create a Loan Product
        $product = LoanProduct::first() ?? LoanProduct::create([
            'name' => 'Regular Personal Loan',
            'interest_method' => 'flat',
            'rate_period' => 'monthly',
            'default_rate' => 5.00,
            'max_term_months' => 12,
            'is_active' => true,
        ]);

        // 4. Create an Active Test Loan (With snapshots from Product + Maturity Date)
        $termMonths = 3;
        $loan = Loan::create([
            'member_id' => $member->id,
            'loan_product_id' => $product->id,
            'purpose' => 'Emergency Medical Expenses',
            'principal_amount' => 12000.00,
            'interest_rate' => 5.00,
            'interest_method' => 'flat',
            'rate_period' => 'monthly',
            'term_months' => $termMonths,
            'release_date' => now()->toDateString(),
            'due_date' => Carbon::now()->addMonths($termMonths)->toDateString(), // Stated maturity limit
            'status' => 'active',
            'created_by' => $user->id,
        ]);

        // 5. Generate 3 Amortization Schedules with status matches
        for ($i = 1; $i <= $termMonths; $i++) {
            LoanSchedule::create([
                'loan_id' => $loan->id,
                'period_number' => $i,
                'due_date' => Carbon::now()->addMonths($i)->toDateString(),
                'principal_due' => 4000.00,
                'interest_due' => 600.00,
                'total_due' => 4600.00,
                'status' => 'pending', // Matches your schema ENUM status definition
            ]);
        }

        // Print feedback indicators directly to your command prompt console
        $this->command->info('══ Cooperative Seeder Configuration Complete ══');
        $this->command->info('👤 User Agent ID: ' . $user->id);
        $this->command->info('👤 Member Profile: ' . $member->full_name);
        $this->command->info('⚙️  Product Profile: ' . $product->name);
        $this->command->info('✅ Test Loan Details: #' . $loan->id . ' - Staged 3 Amortization Schedules.');
    }
}