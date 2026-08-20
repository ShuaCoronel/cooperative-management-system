<?php

namespace App\Services;

use App\Enums\Log\AuditableTable;
use App\Enums\Log\AuditAction;
use App\Enums\Savings\SavingsTransactionType;
use App\Models\MemberAuditLog;
use App\Models\SavingsAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SavingsTransactionService
{
    /**
     * Process a savings transaction with pessimistic locking and precise math.
     */
    public function process(
        int $accountId,
        float|string $amount,
        SavingsTransactionType $type,
        string $transactionDate,
        ?string $remarks,
        int $userId
    ) {
        return DB::transaction(function () use ($accountId, $amount, $type, $transactionDate, $remarks, $userId) {
            
            // 1. Pessimistic Lock (No eager loading needed as balance() runs DB aggregation)
            $lockedAccount = SavingsAccount::where('id', $accountId)
                ->lockForUpdate()
                ->firstOrFail();

            // 2. Normalize string to exactly 2 decimal places
            $formattedAmount = number_format((float) $amount, 2, '.', '');

            // 3. Exact BCMath Validation for Withdrawals
            if ($type === SavingsTransactionType::WITHDRAWAL) {
                $balance = $lockedAccount->balance;
                
                if (bccomp($formattedAmount, $balance, 2) > 0) {
                    throw ValidationException::withMessages([
                        'amount' => ['Insufficient Savings Balance! Current Balance: ₱' . number_format((float) $balance, 2)],
                    ]);
                }
            }

            // 4. Create Transaction
            $transaction = $lockedAccount->transactions()->create([
                'amount'            => $formattedAmount,
                'type'              => $type,
                'transaction_date'  => $transactionDate,
                'remarks'           => $remarks,
                'created_by'        => $userId,
            ]);

            // 5. Create Audit Log
            MemberAuditLog::create([
                'member_id'     => $lockedAccount->member_id,
                'changed_by'    => $userId,
                'table_name'    => AuditableTable::SAVINGS_TRANSACTIONS,
                'action'        => AuditAction::CREATED,
                'old_values'    => null,
                'new_values'    => $transaction->toArray(),
            ]);

            return $transaction;
        });
    }
}