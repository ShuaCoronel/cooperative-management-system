<?php

namespace App\Models;

use App\Enums\Dividends\DividendStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DividendDeclaration extends Model
{
    //

    protected $fillable = [
        'period_start',
        'period_end',
        'total_amount',
        'declaration_date',
        'status', // enum (draft, finalized)
        'created_by',
    ];

    protected function casts() : array {

        return [
            'period_start' => 'date',
            'period_end' => 'date',
            'total_amount' => 'decimal:2',

            'status' => DividendStatus::class,

        ];
        
    }



    public function creator() : BelongsTo {

        return $this->belongsTo(User::class, 'created_by');
        
    }


    public function allocations(): HasMany {

    return $this->hasMany(DividendAllocation::class, 'dividend_declaration_id');

    }


}
