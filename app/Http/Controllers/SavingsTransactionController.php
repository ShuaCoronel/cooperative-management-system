<?php

namespace App\Http\Controllers;

use App\Enums\Log\AuditableTable;
use App\Enums\Log\AuditAction;
use App\Enums\ShareCapitalTransactions\TransactionType;
use App\Models\MemberAuditLog;
use App\Models\SavingsAccount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;


class SavingsTransactionController extends Controller
{
    //
    public function store(Request $request, SavingsAccount $savingsAccount): RedirectResponse {

        $validated = $request->validate([
            'amount'                => ['required','numeric','min:0.01'],
            'type'                  => ['required', Rule::enum(TransactionType::class)],
            'transaction_date'      => ['required', 'date'],
            'remarks'               => ['nullable', 'string', 'max:1000'],

        
        ]);

        return DB::transaction(function () use ($validated, $savingsAccount) {
            $type = TransactionType::from($validated['type']);

                if ($type === TransactionType::WITHDRAWAL) {

                $deposits = $savingsAccount->transactions()
                ->where('type',TransactionType::DEPOSIT)
                ->sum('amount');


                $withdrawals = $savingsAccount->transactions()->
                where('type', TransactionType::WITHDRAWAL)->
                sum('amount');


                $balance = (float)$deposits - (float)$withdrawals;

                if ($validated['amount'] > $balance) {
                    throw ValidationException::withMessages([
                        'amount' => ['Insufficient Savings Balance! Current Balance: '. number_format($balance,2)]

                    ]);

                }
            }


            $transaction = $savingsAccount->transactions()->create([

                'amount'            => $validated['amount'],
                'type'              => $type, // use enum instance object directly
                'transaction_date'  => $validated['transaction_date'],
                'remarks'            => $validated['remarks'] ?? null,
                'created_by'        => Auth::id(),

            ]);


            MemberAuditLog::create([
                'member_id'     => $savingsAccount->member_id,
                'changed_by'    => Auth::id(),
                'table_name'    => AuditableTable::SAVINGS_TRANSACTIONS,
                'action'        => AuditAction::CREATED,
                'old_values'    => null,
                'new_values'    => $transaction->toArray(),
            ]);
            
            return redirect()->back()->with('success', 'Savings Transaction Succesfully Recorded and Audited');

        });

    }

}
