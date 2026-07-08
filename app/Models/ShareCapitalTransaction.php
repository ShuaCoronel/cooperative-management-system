<?php

namespace App\Models;

// >>> [FIXED] Wrong namespace: was App\Enums\ShareCapital\TransactionType (singular - doesn't exist)
use App\Enums\ShareCapitalTransactions\TransactionType;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Override;

class ShareCapitalTransaction extends Model
{
    //
    const UPDATED_AT = null;

    protected $fillable = [
        'member_id',
        'amount',
        'type',
        'transaction_date',
        'remarks',
        'created_by',


    ];  


    #[Override]
    protected function casts(): array
    {
        return [
            'amount'=> 'decimal:2',
            'type' => TransactionType::class, //Enum folder
            'transaction_date' => 'date',

        ];
    }



    // member who own the share capital fk 
    public function member(): BelongsTo {
    
        return $this->belongsTo(Member::class);
    }

    // admin user who process the transaction fk
    public function processor(): BelongsTo {

        return $this->belongsTo(User::class,'created_by');
        
    }


}
