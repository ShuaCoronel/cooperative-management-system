<?php

namespace App\Models;

use App\Enums\Loans\LoanScheduleStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Override;

class LoanSchedule extends Model
{
    //

    //

    protected $fillable = [
        'loan_id',
        'period_number',
        'due_date',
        'principal_due',
        'interest_due',
        'total_due',
        'status',


    ];

    #[Override]
    protected function casts(): array
    {
        return[
            'period_number'=> 'integer',
            'due_date' => 'date',
            'principal_due' => 'decimal:2',
            'interest_due' => 'decimal:2',
            'total_due' => 'decimal:2',

            'status' => LoanScheduleStatus::class, 

        ];
    }

    public function loan(): BelongsTo {
        // always check the column name in our erd or backend
        return $this->belongsTo(Loan::class,'loan_id');

    }

    public function loanPayments(): HasMany {

        return $this->hasMany(LoanPayment::class, 'loan_schedule_id');
    }


}
