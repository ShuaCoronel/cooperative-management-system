<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\LoanPayment;
use App\Models\MemberAuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class LoanPaymentController extends Controller
{
    //

    // Display a listing of loan payments.
    public function index(Request $request) {

        $query = LoanPayment::with('loan.member','loanSchedule')->latest();

        if($request->filled('loan_id')) {
            $query->where('loan_id',$request->loan_id);

        }

        $payments = $query->paginate(20);

        return view('admin.loan-payments.index', compact('payments'));
    }





    

    // * Show the form for creating a new loan payment.
    public function create(Request $request) {
        
        // Fetch all active/defaulted/restructured loans

        $loans = Loan::with('member')
        ->where('status','!=','fully_paid')
        ->get();

        $selectedLoan = null;
        $pendingSchedules=[];


        if($request->filled('loan_id')) {
            $selectedLoan = Loan::with(['member','loanPayments','loanSchedules' => function ($q){
                $q->where('status','!=','paid')->orderBy('period_number','asc');
            }])->findOrFail($request->loan_id);

            $pendingSchedules = $selectedLoan->loanSchedules;

            

            
        }
        return view('admin.loan-payments.create',compact('loans','selectedLoan','pendingSchedules'));
    }







    // * Store a newly created loan payment using Waterfall Allocation.
    // debt interest first to get paid then principal
    public function store(Request $request)
    {
        $validated = $request->validate([
            'loan_id'           => ['required','exists:loans,id'],
            'amount_paid'       => ['required', 'numeric', 'min:0.01'],
            'payment_method'    => ['required', 'string',Rule::in(['cash','bank_transfer','check','gcash'])],
            'reference_number'  => ['nullable', 'string'],
            'payment_date'      => ['required', 'date'],
            'remarks'           => ['nullable', 'string'],

        ]);

        return DB::transaction( function () use ($validated){

        // 1. Lock the core loan row first to serialize concurrent requests
        $loan = Loan::lockForUpdate()->findOrFail($validated['loan_id']);


        // 2. Fetch, lock, and eager-load the pending schedules explicitly inside the transaction
        $pendingSchedules = $loan->loanSchedules()
            ->where('status', '!=', 'paid')
            ->orderBy('due_date', 'asc')
            ->orderBy('period_number', 'asc')
            ->with('loanPayments') // Fix #4: Eager-load payments to prevent N+1
            ->lockForUpdate()      // Fix #3: Lock the schedules to prevent race conditions
            ->get();



        $oldLoanStatus = $loan->status;
        $oldPrincipalPaidBalance = (float) $loan->loanPayments()->sum('principal_paid');

        $remainingCash = (float) $validated['amount_paid'];

        foreach($pendingSchedules as $schedule) {

            if ($remainingCash <= 0.00) {
                break;
            }

            // Calculate running ledger for this specific schedule period
            $interestPaidSoFar = (float) $schedule->loanPayments->sum('interest_paid');
            $principalPaidSoFar = (float) $schedule->loanPayments->sum('principal_paid');

            $interestDue = (float)$schedule->interest_due;
            $principalDue = (float)$schedule->principal_due;

            // max taking the highesh among the param,0.00 will avoid negative
            $interestRemaining = max(0.00, $interestDue - $interestPaidSoFar);
            $principalRemaining = max(0.00, $principalDue - $principalPaidSoFar);


            // Step 1: Waterfall Allocation -> Interest First
            $interestAllocation = min($remainingCash, $interestRemaining);
            $remainingCash -= $interestAllocation;

            // Step 2: Waterfall Allocation -> Principal Second
            $principalAllocation = min($remainingCash, $principalRemaining);
            $remainingCash -= $principalAllocation;

            $totalPeriodAllocation = $interestAllocation + $principalAllocation;

            if ($totalPeriodAllocation > 0.00) {

                LoanPayment::create([
                    'loan_id'           => $loan->id,
                    'loan_schedule_id'  => $schedule->id,
                    'amount_paid'       => $totalPeriodAllocation,
                    'principal_paid'    => $principalAllocation,
                    'interest_paid'     => $interestAllocation,
                    'penalty_paid'      => 0.00,
                    'payment_method'    => $validated['payment_method'],
                    'reference_number'  => $validated['reference_number'] ?? null,
                    'payment_date'      => $validated['payment_date'],
                    'remarks'           => $validated['remarks'] ?? null,
                    'created_by'         => Auth::id(),


                ]);


                // Evaluate and enforce schedule status dynamically
                $newInterestTotal = $interestPaidSoFar + $interestAllocation;
                $newPrincipalTotal = $principalPaidSoFar + $principalAllocation;


                $isPrincipalComplete = round($newPrincipalTotal, 2) >= round($principalDue,2);
                $isInterestComplete = round($newInterestTotal, 2) >= round($interestDue, 2);

                if ($isPrincipalComplete && $isInterestComplete) {
                    $schedule->status = 'paid';

                } else {
                    $schedule->status = 'partial';

                }

                $schedule->save();

            }
        }

        // Step 3: Handle advance principal overflow (if cash remains after satisfying all schedules)
            if (round($remainingCash, 2) > 0.00) {
                LoanPayment::create([
                    'loan_id' => $loan->id,
                    'loan_schedule_id' => null, // Pure advance principal reduction
                    'amount_paid' => $remainingCash,
                    'principal_paid' => $remainingCash,
                    'interest_paid' => 0.00,
                    'penalty_paid' => 0.00,
                    'payment_method' => $validated['payment_method'],
                    'reference_number' => $validated['reference_number'] ?? null,
                    'payment_date' => $validated['payment_date'],
                    'remarks' => trim(($validated['remarks'] ?? '') . ' [Advance Principal Overflow]'),
                    'created_by' => Auth::id(),
                ]);
            }

            // Step 4: Dynamic Loan Balance Summation & Status Enforcement
            $newTotalPrincipalPaid = (float) $loan->loanPayments()->sum('principal_paid');

            if (round($newTotalPrincipalPaid, 2) >= round((float) $loan->principal_amount, 2)) {
                $loan->status = 'fully_paid';
                $loan->save();
            }

            // Step 5: Immutable Audit Ledgering
            MemberAuditLog::create([
                'member_id' => $loan->member_id,
                'changed_by' => Auth::id(),
                'table_name' => 'loans',
                'action' => 'updated',
                'old_values' => [
                    'loan_id' => $loan->id,
                    'status' => $oldLoanStatus,
                    'principal_paid_balance' => $oldPrincipalPaidBalance,
                ],
                'new_values' => [
                    'loan_id' => $loan->id,
                    'status' => $loan->status,
                    'principal_paid_balance' => $newTotalPrincipalPaid,
                    'transaction_amount_paid' => (float) $validated['amount_paid'],
                    'interest_method' => $loan->interest_method->value,
                    'principal_amount' => (float) $loan->principal_amount,
                ],
            ]);


            return redirect()->route('admin.loan-payments.index', ['loan_id' => $loan->id])
                ->with('success', 'Loan payment successfully recorded and allocated via Waterfall ledger.');  

        });
    }
    

    
}
