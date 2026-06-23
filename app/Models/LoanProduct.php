<?php

namespace App\Models;

use App\Enums\Loans\InterestMethod;
use App\Enums\Loans\RatePeriod;
use Illuminate\Database\Eloquent\Model;

class LoanProduct extends Model
{
    //

    protected $fillable = [

        'name',
        'interest_method',
        'default_rate',
        'rate_period',
        'max_term_months',
        'is_active',

    ];  

    protected function casts(): array {

        return [
            'default_rate' => 'decimal:2',
            'max_term_months' => 'decimal:2',
            'is_active' => 'boolean',

            //appEnums link
            'interest_method' => InterestMethod::class,
            'rate_period' => RatePeriod::class

        ];



    }


}
