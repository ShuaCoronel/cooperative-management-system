<?php

namespace App\Enums\ShareCapital;

enum TransactionType: string {

    case DEPOSIT = 'deposit';
    case WITHDRAWAL = 'withdrawal';

}