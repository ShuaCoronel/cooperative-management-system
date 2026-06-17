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

    

    /**
     * The "booted" method of the model.
     * This acts as an automatic interceptor during the model's lifecycle.
     */
    protected static function booted(): void
    {
        static::creating(function (Member $member) {
            // Only generate if one hasn't been explicitly provided
            if (empty($member->member_id_number)) {
                $year = date('Y');
                
                // 1. Find the most recent member registered THIS year
                $lastMember = static::where('member_id_number', 'LIKE', "MEMBER-{$year}-%")
                    ->orderBy('id', 'desc')
                    ->first();

                // 2. Calculate the next sequence number
                if ($lastMember) {
                    // Extract the last 5 digits (e.g., "00042") and add 1
                    $lastSequence = (int) substr($lastMember->member_id_number, -5);
                    $nextSequence = $lastSequence + 1;
                } else {
                    // If this is the very first member of the year, start at 1
                    $nextSequence = 1;
                }

                // 3. Format and attach the new ID before it hits the database
                $member->member_id_number = 'MEMBER-' . $year . '-' . str_pad($nextSequence, 5, '0', STR_PAD_LEFT);
            }
        });
    }





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
