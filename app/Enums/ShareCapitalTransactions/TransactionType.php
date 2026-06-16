<?php

namespace App\Enums\ShareCapitalTransactions;

enum TransactionType: string {

    case DEPOSIT = 'deposit';
    case WITHDRAWAL = 'withdrawal';

}