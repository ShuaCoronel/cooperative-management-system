<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DividendAllocation extends Model
{
    //

    const UPDATED_AT = null;

    protected $fillable = [
        'dividend_declaration_id',
        'member_id',
        'time_weighted_share_capital',
        'allocated_amount',


    ];

    protected function casts(): array {

        return[
            'time_weighted_share_capital'=> 'decimal:4',
            'allocated_amount' => 'decimal:4',

        ];

    }


    public function declaration() : BelongsTo {

        return $this->belongsTo(DividendDeclaration::class,'dividend_declaration_id');
        
    }


    public function member(): BelongsTo {

        return $this->belongsTo(Member::class,'member_id');

    }


}
