<?php

namespace App\Models;

use App\Models\Member;
use Illuminate\Database\Eloquent\Model;

class SavingsTransaction extends Model
{
    //
    // updated at is null to make the savings transaction immutable
    // to avoid updates and forgery
    const UPDATED_AT = null;



    // makes the id not editable for forgery
    protected $guarded = ['id'];


    
    // relationship
    public function member() {

        return $this->belongsTo(Member::class);

    }

    public function account() {

        return $this->belongsTo(SavingsAccount::class,'savings_account_id');
    
    }
    

}
