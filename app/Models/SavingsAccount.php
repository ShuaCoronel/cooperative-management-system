<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Casts\Attribute;
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
    'product_type',
    'status',
    'opened_at',
    

    ];

   protected $casts = [

    'opened_at' => 'date',

   ];   


    public function member(): BelongsTo {

        return $this->belongsTo(Member::class, 'member_id');

    }


    public function transactions(): HasMany {

        return $this->hasMany(SavingsTransaction::class,'savings_account_id');

    }



    // note cline fixed
  // >>> [FIXED] Balance accessor was fully commented out / broken. Now computes actual balance from transactions.
  protected function balance(): Attribute {

    return Attribute::make(

        get: function() {

            $deposits = (float) $this->transactions()->where('type','deposit')->sum('amount');
            $withdrawals = (float) $this->transactions()->where('type','withdrawal')->sum('amount');

            return number_format($deposits - $withdrawals,2);

        }
    );


  }
    




}
