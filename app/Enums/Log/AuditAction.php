<?php

namespace App\Enums\Log;

enum AuditAction: string {

    case CREATED = 'created';
    case UPDATED = 'updated';
    case DELETED = 'deleted';
    case RESTORED = 'restored';

}