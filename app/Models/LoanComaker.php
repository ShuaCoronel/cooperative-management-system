<?php

namespace App\Models;
use App\Enums\Loans\LoanComakerStatus;
use App\Models\Loan;
use App\Models\Member;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoanComaker extends Model
{
    //
    //erd no updated column, need to turn off
    // other scenarios where updated_at column can be rename
    const UPDATED_AT = null;

    protected $fillable = [
        'loan_id',
        'member_id',
        'status', // enum


    ];

    protected function casts(): array {

        return [
            'status' => LoanComakerStatus::class,

        ];
    }

    public function loan():BelongsTo {

        return $this->belongsTo(Loan::class, 'loan_id');

    }

    public function guarantor(): BelongsTo {

        return $this->belongsTo(Member::class,'member_id');

    }



}
