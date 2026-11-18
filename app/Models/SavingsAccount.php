<?php

namespace App\Models;

use App\Enums\Savings\ProductType;
use App\Enums\Savings\SavingsTransactionType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class SavingsAccount extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
    'ulid',
    'member_id',
    'account_number',
    'account_nickname',
    'product_type',
    'status',
    'opened_at',
    

    ];

   protected $casts = [

    'opened_at' => 'date',
    'product_type'  => ProductType::class,

   ];   



   //ULID generate a ULID when creating a new savings account.
   protected static function booted()
    {
        static::creating(function ($savingsAccount) {
            if (empty($savingsAccount->ulid)) {
                $savingsAccount->ulid = (string) Str::ulid();
            }
        });
    }


    /**
     * Use 'ulid' for route model binding instead of the numeric id.
     */
    public function getRouteKeyName(): string
    {
        return 'ulid';
    }


    public function member(): BelongsTo {

        return $this->belongsTo(Member::class, 'member_id');

    }


    public function transactions(): HasMany {

        return $this->hasMany(SavingsTransaction::class,'savings_account_id');

    }



    // accessor for account nickname for multiple savings accounts
    protected function displayNickname() : Attribute {

        return Attribute::make( 
            get: fn() => $this->account_nickname ?? $this->account_number     
        
        
        );

    }


    // note cline fixed
  // >>> [FIXED] Balance accessor was fully commented out / broken. Now computes actual balance from transactions.
  protected function balance(): Attribute {

    return Attribute::make(

        get: function() {

            // $deposits = (float) $this->transactions()->where('type','deposit')->sum('amount');
            // $withdrawals = (float) $this->transactions()->where('type','withdrawal')->sum('amount');

            $deposits = $this->transactions()
            ->where('type', SavingsTransactionType::DEPOSIT)->sum('amount') ?? 0;

            $withdrawals = $this->transactions()
            ->where('type', SavingsTransactionType::WITHDRAWAL)->sum('amount') ?? 0;



            return bcsub((string) $deposits, (string) $withdrawals, 2);

        }
    );


  }
    




}
