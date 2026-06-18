<?php

namespace App\Models;

use App\Enums\ShareCapitalTransactions\TransactionType;
use Illuminate\Database\Eloquent\Model;
use Override;

class SavingsTransaction extends Model
{
    //
    // updated at is null to make the savings transaction immutable
    // to avoid updates and forgery
    const UPDATED_AT = null;



    // makes the id not editable for forgery
    // protected $guarded = ['id'];


    protected $fillable = [ 
        'savings_account_id',
        'amount',
        'type',
        'transaction_date',
        'remarks',
        'created_by'

    ];


    #[Override]
    protected function casts(): array
    {
        return [
            'amount'                => 'decimal:2',
            'type'                  => TransactionType::class,
            'transaction_date'      => 'date',

        ];    

    }

    
    // relationship
    public function account() {

        return $this->belongsTo(SavingsAccount::class, 'savings_account_id');

    }

    public function processor() {

        return $this->belongsTo(User::class,'created_by');
    
    }
    

}
