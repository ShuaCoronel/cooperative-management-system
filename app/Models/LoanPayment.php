<?php

namespace App\Models;

use App\Models\Loan;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoanPayment extends Model
{
    //
    // to avoid forgery of payment, update should not be allowed, avoid track of updates 
    const UPDATED_AT = null;


    protected $fillable = [
        'loan_id',
        'amount_paid',
        'principal_paid',
        'interest_paid',
        'penalty_paid',
        'payment_date',
        'remarks',

        'created_by',


    ];  

    protected function casts() : array {

        return [
            'amount_paid' => 'decimal:2',
            'principal_paid' => 'decimal:2',
            'interest_paid' => 'decimal:2',
            'penalty_paid' => 'decimal:2',

            'payment_date' => 'date',

        ];

        
    }



    // the connection to parent table
    public function loan() : BelongsTo {
        
        return $this->belongsTo(Loan::class, 'loan_id');
        
    }


    public function collector(): BelongsTo {

        return $this->belongsTo(User::class, 'created_by');

    }


}
