<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Savings\SavingsTransactionType;
use App\Http\Controllers\Controller;
use App\Models\SavingsAccount;
use App\Services\SavingsTransactionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SavingsTransactionController extends Controller
{
    /**
     * Store a newly created savings transaction.
     */
    public function store(
        Request $request, 
        SavingsAccount $savingsAccount, 
        SavingsTransactionService $transactionService
    ): RedirectResponse {
        
        $validated = $request->validate([
            'amount'           => ['required', 'numeric', 'min:0.01'],
            'type'             => ['required', Rule::enum(SavingsTransactionType::class)],
            'transaction_date' => ['required', 'date'],
            'remarks'          => ['nullable', 'string', 'max:1000'],
        ]);

        // Delegate business logic and locking to the service
        $transactionService->process(
            accountId: $savingsAccount->id,
            amount: $validated['amount'],
            type: SavingsTransactionType::from($validated['type']),
            transactionDate: $validated['transaction_date'],
            remarks: $validated['remarks'] ?? null,
            userId: Auth::id()
        );
        
        return redirect()->back()->with('success', 'Savings Transaction Successfully Recorded and Audited');
    }
}