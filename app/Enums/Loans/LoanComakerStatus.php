<?php

namespace App\Enums\Loans;

/**
 * It prevents random or typo-ridden strings from being saved.
 */
enum LoanComakerStatus: string
{
    // When a member signs up to guarantee a loan
    case ACTIVE = 'active';

    // Used if the loan is fully paid off or the risk obligation naturally expires.
    case RELEASED = 'released';

    // Used if a co-maker backs out mid-loan and is substituted by another member.
    case REPLACED = 'replaced';
}