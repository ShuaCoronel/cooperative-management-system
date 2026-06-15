<?php

namespace App\Enums\Loans;

enum LoanScheduleStatus: string {

// for monthly dues or loan schedule payment, it will track like waterfall
// paying the 1st month to complete first in queue before pouring the payment to the next sched


case PENDING = 'pending';
case PAID = 'paid';
case PARTIAL = 'partial';
case OVERDUE = 'overdue';


}