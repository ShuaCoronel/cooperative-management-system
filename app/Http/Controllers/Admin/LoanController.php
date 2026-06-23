<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\LoanProduct;
use App\Models\LoanSchedule;
use App\Models\Member;
use App\Models\MemberAuditLog;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
{
    //

    public function store(Request $request, string $member_id_number ): RedirectResponse {

        $validated = $request->validate([
        'loan_product_id'       => ['required','exists:loan_products,id'],
        'purpose'               => ['nullable','string'],
        'principal_amount'      => ['required','numeric','min:1'],
        'term_months'           => ['required', 'integer', 'min:1'],
        'release_date'          => ['required', 'date'],



        ]);


        $member = Member::where('member_id_number', $member_id_number)->firstOrFail();


        $product = LoanProduct::where('id', $validated['loan_product_id'])
        ->where('is_active', true)
        ->firstOrFail();


        if ($validated['term_months'] > $product->max_term_months) {
            return redirect()->back()->withErrors(['term_months' 
            => 'Term exceeds maximum allowed for this product']);
        
        }

        DB::transaction( function () use ( $validated, $product, $member) {
            $releaseDate = Carbon::parse($validated['release_date']);
            $dueDate = $releaseDate->copy()->addMonths($validated['term_months']);
            
                
            $loan = Loan::create([
            'member_id'         =>$member->id,
            'loan_product_id'   =>$product->id,
            'purpose'           => $validated['purpose'],
            'principal_amount'  => $validated['principal_amount'],
            'interest_rate'     => $product->default_rate,
            'interest_method'   => $product->interest_method,
            'rate_period'       => $product->rate_period,
            'term_months'       => $validated['term_months'],
            'release_date'      => $releaseDate->toDateString(),
            'due_date'          => $dueDate->toDateString(),
            'status'            => 'active',
            'created_by'        => Auth::id(),

            ]);

            $this->generateSchedule($loan);

            MemberAuditLog::create([

            'member_id'         =>$member->id,
            'changed_by'        =>Auth::id(),
            'table_name'        =>'loans',
            'action'            =>'created',
            'old_values'        =>null,
            'new_values'        =>  [
                'loan_id'           =>  $loan->id,
                'principal_amount'  => $loan->principal_amount,
                'interest_method'   => $loan->interest_method,
                ],
        ]);




        }); 
        return redirect()->back()->with('success','Loan Created and Amortization schedule generated securely');
    
    }

    protected function generateSchedule(Loan $loan): void {

        $principal   =   $loan->principal_amount;
        $rate               =   $loan->interest_rate / 100;
        $months             =   $loan->term_months;
        $currentDate        =   Carbon::parse($loan->release_date);

        // standardize monthly rate
        $monthlyRate = $loan->rate_period === 'annual' ? $rate /12: $rate;

        $schedules = []; 
        $balance = $principal;

        if ($loan->interest_method === 'flat') {
            $monthlyPrincipal   = $principal  / $months;
            $monthlyInterest    = $principal * $monthlyRate;
            $totalMonthly       = $monthlyPrincipal + $monthlyInterest;


            for ( $i = 1; $i <= $months; $i++ ) {
                // schedule append [] shortcut to avoid clearing the scheds every iteration, simply append
                // array of arrays for each month payment
                $schedules[] = [
                    'loan_id'           =>$loan->id,
                    'period_number'     =>$i,
                    'due_date'          =>$currentDate->copy()->addMonths($i)->toDateString(),
                    'principal_due'     =>round($monthlyPrincipal, 2),
                    'interest_due'      =>round($monthlyInterest, 2),
                    'total_due'         =>round($totalMonthly, 2),
                    'status'            => 'pending',


                ];

            }

        }   else {
            //diminishing reducing balance
             // we could use simpler syntax for power pow() ** stands for power
             //$principal * 1+$monthlyRate ** $months 
            $monthlyPayment = $monthlyRate > 0 
            ? $principal * ($monthlyRate * pow(1+$monthlyRate,$months)) / (pow(1 + $monthlyRate,$months)-1)
            : $principal / $months;


            for($i = 1; $i <= $months; $i++ ) {
                $interestForMonth   = $balance * $monthlyRate;
                $principalForMonth  = $monthlyPayment - $interestForMonth;

                // Handle rounding differences on the final tranche to ensure zero balance
                if($i === $months) {
                    $principalForMonth = $balance;
                    $monthlyPayment = $principalForMonth + $interestForMonth;


                }

                $schedules[] = [
                    'loan_id'           =>$loan->id,
                    'period_number'     =>$i,
                    'due_date'          =>$currentDate->copy()->addMonths($i)->toDateString(),
                    'principal_due'     =>round($principalForMonth, 2),
                    'interest_due'      =>round($interestForMonth, 2),
                    'total_due'         =>round($monthlyPayment, 2),
                    'status'            => 'pending',


                ];

                $balance -= $principalForMonth;

            }

        }

        //Bulk insert to minimize DB CALLS during generation
        LoanSchedule::insert($schedules);

    }



}
