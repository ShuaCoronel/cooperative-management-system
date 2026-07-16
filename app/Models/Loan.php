<?php

namespace App\Models;

use App\Enums\Loans\InterestMethod;
use App\Enums\Loans\RatePeriod;
use App\Models\LoanComaker;
use App\Models\LoanProduct;
use App\Models\Member;
use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Loan extends Model
{
    //

    protected $fillable = [
        'member_id',
        'loan_product_id',
        'purpose',
        'principal_amount',
        'interest_rate',
        'interest_method',
        'rate_period',
        'term_months',
        'release_date',
        'due_date',
        'status',
        'created_by'

    ];


    // >>> [FIXED] Method name was 'cast()' (singular) — Laravel expects 'casts()' (plural). Without this, all enum/decimal casts failed silently.
    protected function casts(): array {
        
        return [

            'principal_amount' => 'decimal:2',
            'interest_rate' => 'decimal:2',
            
            'interest_method' => InterestMethod::class,
            'rate_period' => RatePeriod::class,

            'term_months' => 'integer',
            'release_date' => 'date',
            'due_date' => 'date',

        ];
    }


 
    public function member(): BelongsTo {

        return $this->belongsTo(Member::class,'member_id');

    }

   //loan product are like just the static product available like emergency loan, etc
    public function product(): BelongsTo {

        return $this->belongsTo(LoanProduct::class,'loan_product_id');

    }


    // admin who approve and created the loan log
    public function creator() : BelongsTo {
        
        // parameter rule: created by is the column on child table, 3rd parameter not needed
        // for it was automatically the PK id
        return $this->belongsTo(User::class,'created_by');
        
    }

    public function comakers(): HasMany {

        return $this->hasMany(LoanComaker::class, 'loan_id');

    }

    public function loanSchedules(): HasMany {

        return $this->hasMany(LoanSchedule::class, 'loan_id');

    }

    public function loanPayments() : HasMany {

        return $this->hasMany(LoanPayment::class, 'loan_id');
        
    }





    // Calculate Remaining balance need to pay, an accessor
    // a virtual column that is non static and calculates dynamically
   protected function remainingBalance(): Attribute{
        return Attribute::make(
            get: function() {
                // Bug #2 Fix: Performance short-circuit for closed loans
                if ($this->status === 'fully_paid') {
                    return 0.00;
                }

                // Bug #1 Fix: Audit-compliant calculation that completely bypasses 
                // any pre-filtered 'loanSchedules' collections to guarantee data integrity.
                $originalPrincipal = (float) $this->principal_amount;
                $principalPaid = (float) $this->loanPayments->sum('principal_paid');
                
                return $originalPrincipal - $principalPaid;
            }
        );
    }





    /**
     * UI Accessor: Calculates total payoff obligation (Principal + Interest)
     * Used exclusively for the Member Dashboard UX.
     */
    protected function payoffAmount(): Attribute {
        return Attribute::make(
            get: function() {
                if ($this->status === 'fully_paid') {
                    return 0.00;
                }

                // Total expected debt
                $totalPrincipal = (float) $this->principal_amount;
                $totalInterestExpected = (float) $this->loanSchedules->sum('interest_due');
                
                // Total actually paid towards debt
                $principalPaid = (float) $this->loanPayments->sum('principal_paid');
                $interestPaid = (float) $this->loanPayments->sum('interest_paid');

                // Return the total remaining obligation (cannot go below 0)
                return max(0.00, ($totalPrincipal + $totalInterestExpected) - ($principalPaid + $interestPaid));
            }
        );
    }

       

}
