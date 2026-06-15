<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    //
    // softdeletes, even deleted accidentally, it logs on softdeleted table
    use SoftDeletes;


    protected $guarded = ['id'];


    protected $fillable = [
        'user_id',
        'member_id_number',
        'full_name',
        'date_of_birth',
        'sex',
        'civil_status',
        'nationality',
        'home_address',
        'mobile_number',
        'email',
        'valid_id_type',
        'valid_id_number',
        'tin',
        'occupation',
        'date_joined',
        'membership_status',
        'membership_type',
        'deleted_by',
        'deletion_reason',

    ];


    protected $casts = [
    'date_of_birth' => 'date',
    'date_joined' => 'date',


    ];

    public function user() {

    return $this->belongsTo(User::class, 'user_id');

    }

    public function savingsTransactions() {

        return $this->hasMany(SavingsTransaction::class);

    }

    public function savingsAccounts() {

        return $this->hasMany(SavingsAccount::class);
        
    }

    public function shareCapitalTransactions() : HasMany {
        return $this->hasMany(ShareCapitalTransaction::class, 'member_id');
    }


    public function dividendAllocation() : HasMany {

        return $this->hasMany(DividendAllocation::class,'member_id');
        
    }

        
    }
