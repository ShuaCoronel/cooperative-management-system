<?php

namespace App\Enums\Loans;

/**
 * Explanation: This is a "Backed Enum" (indicated by ': string').
 * It forces the application to strictly use only these two options.
 */
enum RatePeriod: string
{
    // CASE NAME = 'Database Value'
    case MONTHLY = 'monthly';
    case ANNUAL = 'annual';
}