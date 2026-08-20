<?php

namespace App\Enums\Savings;


enum SavingsTransactionType: string {

    case DEPOSIT = 'deposit';
    case WITHDRAWAL = 'withdrawal';

}
