<?php

namespace App\Models;

use App\Enums\Loans\InterestMethod;
use App\Enums\Loans\RatePeriod;
use App\Models\LoanComaker;
use App\Models\LoanProduct;
use App\Models\Member;
use App\Models\User;
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


    protected function cast(): array {
        
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

    public function schedules(): HasMany {

        return $this->hasMany(LoanSchedule::class, 'loan_id');

    }

    public function payments() : HasMany {

        return $this->hasMany(LoanPayment::class, 'loan_id');
        
    }

}
