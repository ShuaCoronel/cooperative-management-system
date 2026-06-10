<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    //
    use SoftDeletes;


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
        
    }
