<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SavingsAccount extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [

    'member_id',
    'account_number',
    'account_type',
    'status'

    ];


    public function member(): BelongsTo {

        return $this->belongsTo(Member::class, 'member_id');

    }


    public function transactions(): HasMany {

        return $this->hasMany(SavingsTransaction::class,'savings_account_id');

    }




}
