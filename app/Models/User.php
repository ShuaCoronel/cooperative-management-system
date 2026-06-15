<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\UserRole;
use App\Models\LoanPayment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */

    // hash
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */


    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' =>UserRole::class, //enum
        ];
    }


    // relationship this is the owner and has one connected child
    //Member Connection
    public function member()
    {
        return $this->hasOne(Member::class, 'user_id');
    }


    //shareCapitalTransaction Model Connection
    public function processedShareCapitalTransactions(): HasMany {

        return $this->hasMany(ShareCapitalTransaction::class, 'created_by');

    }


    // Loan Payment connection
    public function processedLoanPayments() : HasMany {

        return $this->hasMany(LoanPayment::class, 'created_by');
        
    }


    public function declaredDividends() : HasMany {

        return $this->hasMany(DividendDeclaration::class, 'created_by');
        
    }


}
