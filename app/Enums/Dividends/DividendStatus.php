<?php

namespace App\Enums\Dividends;

/**
 * Explanation: Restricts a declaration to explicit structural states.
 * Draft allows adjustments; Finalized locks the calculations permanently.
 */

// these is supposed the earnings of the share capital using time weighted distribution
enum DividendStatus: string
{
    case DRAFT = 'draft';
    case FINALIZED = 'finalized';
}