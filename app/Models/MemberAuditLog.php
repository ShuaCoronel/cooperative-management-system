<?php

namespace App\Models;

use App\Enums\Log\AuditableTable;
use App\Enums\Log\AuditAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberAuditLog extends Model
{
    //

    const UPDATED_AT = null;

    protected $fillable = [
    'member_id',
    'changed_by',
    'table_name',
    'action',
    'old_values',
    'new_values',
    'created_at',

    ];


    protected function casts() : array {

        return [
            // Custom Lifecycle Enum State Mapping
            'table_name' => AuditableTable::class,
            'action'     => AuditAction::class,

            // JSON Casting: Automatically converts DB text blobs into clean PHP arrays
            'old_values' => 'array',
            'new_values' => 'array',


        ];
        
    }


    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'member_id');
    }


    /**
     * RELATIONSHIP: Links this log to the administrative User who performed the action.
     */
    public function operator(): BelongsTo {

        return $this->belongsTo(User::class, 'changed_by');

    }
    



}
