<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Log\AuditableTable;
use App\Enums\Log\AuditAction;
use App\Enums\ShareCapitalTransactions\TransactionType;
use App\Models\Member;
use App\Models\MemberAuditLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ShareCapitalTransactionController extends Controller
{
    //

    public function store(Request $request, Member $member) : RedirectResponse {

        $validated = $request->validate([
            'amount'            => ['required', 'numeric', 'min:0.01'],
            'type'              => ['required', Rule::enum(TransactionType::class)],
            'transaction_date'  => ['required', 'date'],
            'remarks'           => ['nullable', 'string', 'max:1000']



        ]);


        return DB::transaction(function () use ($validated, $member) {
            $type = TransactionType::from($validated['type']);

            if ($type === TransactionType::WITHDRAWAL) {
               
            
            
                // $deposit = $member->shareCapitalTransactions()
                // ->where('type', TransactionType::DEPOSIT)
                // ->sum('amount');

                

                // $withdrawals = $member->shareCapitalTransactions()
                // ->where('type', TransactionType::WITHDRAWAL)
                // ->sum('amount');

                // depoosits and withdrawal variable not needed
                // balance already calculated using accessor in the member model


                // check accessor shareCapitalBalance in member model
                $balance = (float) $member->shareCapitalBalance;

                
                if ($validated['amount'] > $balance) {

                    throw ValidationException::withMessages([
                    'amount'=> ['Insufficient Share Capital Balance! Current Balance: '. number_format($balance, 2)],
                                   ]);
                }
            }

            



            $transaction = $member->shareCapitalTransactions()->create([
            'amount'                => $validated['amount'],
            'type'                  => $type,
            'transaction_date'      => $validated['transaction_date'],
            'remarks'               => $validated['remarks'] ?? null,
            'created_by'            => Auth::id(),



            ]);


            MemberAuditLog::create([
                'member_id'         => $member->id,
                'changed_by'        => Auth::id(),
                'table_name'        => AuditableTable::SHARE_CAPITAL_TRANSACTIONS,
                'action'            => AuditAction::CREATED,
                'old_values'        => null,
                'new_values'        =>$transaction->toArray(),

            
            ]);


            return redirect()->back()->with('success','Share Capital Transaction Succesfully Recorded and Audited' );
            

        });




        
    }



}
