<?php

namespace App\Enums\Log;

enum AuditableTable: string {

    case MEMBERS = 'members';
    case LOANS = 'loans';
    case SAVINGS_TRANSACTIONS = 'savings_transactions';
    case SHARE_CAPITAL_TRANSACTIONS = 'share_capital_transactions';
    case DIVIDEND_DECLARATIONS = 'dividend_declarations';
    case DIVIDEND_ALLOCATIONS = 'dividend_allocations';

}