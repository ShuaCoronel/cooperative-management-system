<x-app-layout>
    <div class="min-h-screen bg-slate-50/60 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Breadcrumb & Top Actions --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <nav class="flex items-center text-sm font-medium text-slate-500">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-900 transition-colors flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Dashboard
                    </a>
                    <svg class="w-4 h-4 mx-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <span class="text-slate-900 font-semibold">Savings Ledger</span>
                </nav>

                <div class="flex items-center gap-2">
                    <a href="{{ url()->previous() }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-xs font-semibold text-slate-600 hover:bg-slate-50 hover:text-slate-900 shadow-sm transition">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back
                    </a>
                </div>
            </div>

            @foreach ($savingsAccounts as $savingsAccount)
                @php
                    $status = $savingsAccount->status instanceof \BackedEnum ? $savingsAccount->status->value : $savingsAccount->status;
                    $statusColor = match(strtolower($status ?? 'active')) {
                        'active' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                        'dormant', 'inactive' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                        'closed' => 'bg-slate-100 text-slate-600 ring-slate-500/20',
                        default => 'bg-slate-100 text-slate-700 ring-slate-400/20'
                    };

                    // Compute quick summary totals
                    $totalInflow = $savingsAccount->transactions->where('type.value', 'deposit')->sum('amount') 
                                    ?: $savingsAccount->transactions->where('type', 'deposit')->sum('amount');
                    $totalOutflow = $savingsAccount->transactions->where('type.value', 'withdrawal')->sum('amount') 
                                    ?: $savingsAccount->transactions->where('type', 'withdrawal')->sum('amount');
                @endphp

                {{-- Account Summary Card --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-7">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                        
                        {{-- Member & Account Identifiers --}}
                        <div class="flex items-start sm:items-center gap-4">
                            <div class="h-13 w-13 rounded-xl bg-slate-900 text-white flex items-center justify-center font-bold text-lg shadow-sm shrink-0">
                                {{ strtoupper(substr($savingsAccount->member->full_name ?? 'M', 0, 2)) }}
                            </div>
                            <div class="space-y-1">
                                <div class="flex items-center gap-2.5 flex-wrap">
                                    <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                                        {{ $savingsAccount->member->full_name ?? 'N/A' }}
                                    </h1>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold ring-1 ring-inset {{ $statusColor }}">
                                        {{ ucfirst($status) }}
                                    </span>
                                </div>
                                
                                <div class="flex items-center gap-3 text-xs text-slate-500 font-mono" x-data="{ copied: false }">
                                    <span>Acc: <strong class="text-slate-800">{{ $savingsAccount->account_number }}</strong></span>
                                    <button 
                                        type="button" 
                                        @click="navigator.clipboard.writeText('{{ $savingsAccount->account_number }}'); copied = true; setTimeout(() => copied = false, 2000)" 
                                        class="text-indigo-600 hover:text-indigo-700 font-sans inline-flex items-center gap-1 transition"
                                        title="Copy Account Number">
                                        <svg x-show="!copied" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                        </svg>
                                        <svg x-show="copied" x-cloak class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <span x-text="copied ? 'Copied!' : 'Copy'" class="text-[11px] font-medium"></span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Financial Snapshot Metrics --}}
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-6 border-t lg:border-t-0 pt-4 lg:pt-0">
                            {{-- Balance --}}
                            <div class="col-span-2 sm:col-span-1 bg-slate-50/80 rounded-xl p-3.5 border border-slate-100">
                                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block">Available Balance</span>
                                <div class="text-2xl font-black text-slate-900 tracking-tight mt-0.5 tabular-nums">
                                    ₱{{ number_format((float) $savingsAccount->balance, 2) }}
                                </div>
                            </div>
                            
                            {{-- Inflow --}}
                            <div class="bg-emerald-50/50 rounded-xl p-3.5 border border-emerald-100/60">
                                <span class="text-[11px] font-semibold text-emerald-600 uppercase tracking-wider block">Total Deposits</span>
                                <div class="text-base font-bold text-emerald-700 tracking-tight mt-0.5 tabular-nums">
                                    +₱{{ number_format((float) $totalInflow, 2) }}
                                </div>
                            </div>

                            {{-- Outflow --}}
                            <div class="bg-rose-50/50 rounded-xl p-3.5 border border-rose-100/60">
                                <span class="text-[11px] font-semibold text-rose-600 uppercase tracking-wider block">Total Withdrawals</span>
                                <div class="text-base font-bold text-rose-700 tracking-tight mt-0.5 tabular-nums">
                                    -₱{{ number_format((float) $totalOutflow, 2) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Main Content Grid --}}
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

                    {{-- Left: Transaction Posting Form --}}
                    <div class="lg:col-span-4" x-data="{ txType: 'deposit' }">
                        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden sticky top-6">
                            
                            {{-- Header --}}
                            <div class="px-6 py-4.5 border-b border-slate-100 bg-slate-50/40">
                                <h3 class="text-sm font-bold text-slate-900">Post Transaction</h3>
                                <p class="text-xs text-slate-500">Record a new deposit or withdrawal</p>
                            </div>

                            <form action="{{ route('admin.savings.transactions.store', $savingsAccount) }}" method="POST" class="p-6 space-y-5">
                                @csrf

                                {{-- Segmented Switcher for Transaction Type --}}
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Transaction Type</label>
                                    <div class="grid grid-cols-2 p-1 bg-slate-100 rounded-xl text-xs font-bold">
                                        <button 
                                            type="button" 
                                            @click="txType = 'deposit'"
                                            :class="txType === 'deposit' ? 'bg-white text-emerald-700 shadow-sm' : 'text-slate-600 hover:text-slate-900'"
                                            class="py-2.5 rounded-lg transition-all flex items-center justify-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                            </svg>
                                            Deposit
                                        </button>
                                        <button 
                                            type="button" 
                                            @click="txType = 'withdrawal'"
                                            :class="txType === 'withdrawal' ? 'bg-white text-rose-700 shadow-sm' : 'text-slate-600 hover:text-slate-900'"
                                            class="py-2.5 rounded-lg transition-all flex items-center justify-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-rose-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 112 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                            Withdrawal
                                        </button>
                                    </div>
                                    <input type="hidden" name="type" :value="txType">
                                    @error('type')
                                        <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Amount Field --}}
                                <div>
                                    <label for="amount" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">
                                        Amount (PHP)
                                    </label>
                                    <div class="relative rounded-xl">
                                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 font-semibold text-base">
                                            ₱
                                        </div>
                                        <input 
                                            type="number" 
                                            name="amount" 
                                            id="amount" 
                                            step="0.01" 
                                            min="0.01" 
                                            placeholder="0.00" 
                                            required
                                            class="block w-full pl-8 pr-4 py-2.5 text-base font-semibold border-slate-200 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-transparent transition placeholder:text-slate-300">
                                    </div>
                                    @error('amount')
                                        <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Date Field --}}
                                <div>
                                    <label for="transaction_date" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">
                                        Effective Date
                                    </label>
                                    <input 
                                        type="date" 
                                        name="transaction_date" 
                                        id="transaction_date" 
                                        value="{{ date('Y-m-d') }}" 
                                        required
                                        class="block w-full border-slate-200 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-transparent px-3.5 py-2.5 text-sm text-slate-700 transition">
                                    @error('transaction_date')
                                        <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Remarks Field --}}
                                <div>
                                    <label for="remarks" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">
                                        Remarks / Reference
                                    </label>
                                    <textarea 
                                        name="remarks" 
                                        id="remarks" 
                                        rows="2" 
                                        placeholder="e.g. Over-the-counter cash deposit..." 
                                        class="block w-full border-slate-200 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-transparent px-3.5 py-2.5 text-sm placeholder:text-slate-400 transition"></textarea>
                                    @error('remarks')
                                        <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Adaptive Action Button with Color & Label Cues --}}
                                <button 
                                    type="submit" 
                                    :class="txType === 'deposit' 
                                        ? 'bg-emerald-600 hover:bg-emerald-700 shadow-emerald-200' 
                                        : 'bg-rose-600 hover:bg-rose-700 shadow-rose-200'"
                                    class="w-full text-white font-semibold rounded-xl py-3 px-4 text-sm shadow-md transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                                    <span x-text="txType === 'deposit' ? 'Confirm Deposit' : 'Confirm Withdrawal'"></span>
                                </button>
                            </form>

{{--
    REVIEW AND VULNERABILITIES:

    1. Business Logic in View (MVC Violation):
       - The calculations for $totalInflow, $totalOutflow, and the $statusColor match expression are "crowded"
         within the template. This logic belongs in the SavingsAccount model (as accessors) or a Service class.
       - Hardcoding the transaction date default to date('Y-m-d') in the view can cause timezone discrepancies
         between the user's browser and the server.

    2. Performance & N+1 Query Risk:
       - Accessing $savingsAccount->transactions inside a @foreach loop of $savingsAccounts will trigger
         multiple database queries (N+1 problem) if the relationship was not eager-loaded in the controller
         using ->with('transactions').
       - Loading and rendering every transaction for every account on a single page is not scalable. As
         transaction history grows, the page will suffer from high memory usage and slow DOM rendering.

    3. Financial Precision Issues:
       - Casting currency amounts to (float) for calculation and display is a vulnerability. Floating-point
         math is imprecise for financial data. Use the `bcmath` extension or store amounts as integers (cents).

    4. Security & Authorization:
       - Missing Authorization Checks: The "Post Transaction" form is exposed without @can or @role checks.
         An authenticated user with access to the dashboard might bypass UI restrictions to post transactions.
       - ID Exposure: The route uses $savingsAccount directly, likely exposing the primary key in the URL.

    5. UX & Validation:
       - There is no client-side check to prevent a withdrawal amount from exceeding the available balance.
       - The ledger table is not paginated, which will eventually break the UI layout.
--}}
</div>
                    </div>

                    {{-- Right: Activity Ledger Table --}}
                    <div class="lg:col-span-8">
                        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                            
                            {{-- Table Header --}}
                            <div class="px-6 py-4.5 border-b border-slate-100 flex items-center justify-between bg-slate-50/40">
                                <div>
                                    <h3 class="text-sm font-bold text-slate-900">Ledger Activity</h3>
                                    <p class="text-xs text-slate-500">
                                        {{ $savingsAccount->transactions->count() }} {{ Str::plural('entry', $savingsAccount->transactions->count()) }} recorded
                                    </p>
                                </div>

                                @if($savingsAccount->transactions->isNotEmpty())
                                    <button type="button" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-xs font-semibold text-slate-700 hover:bg-slate-50 transition shadow-xs">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        Export
                                    </button>
                                @endif
                            </div>

                            {{-- Responsive Table --}}
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="border-b border-slate-100 text-[11px] font-semibold text-slate-400 uppercase tracking-wider bg-slate-50/20">
                                            <th class="px-6 py-3.5">Date</th>
                                            <th class="px-6 py-3.5">Type</th>
                                            <th class="px-6 py-3.5">Description</th>
                                            <th class="px-6 py-3.5 text-right">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 text-sm">
                                        @forelse($savingsAccount->transactions as $transaction)
                                            @php
                                                $type = $transaction->type instanceof \BackedEnum ? $transaction->type->value : $transaction->type;
                                                $isDeposit = $type === 'deposit';
                                            @endphp
                                            <tr class="hover:bg-slate-50/60 transition-colors">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="font-medium text-slate-800">
                                                        {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('M d, Y') }}
                                                    </div>
                                                    <div class="text-[11px] text-slate-400 font-mono">
                                                        #{{ $transaction->id }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if($isDeposit)
                                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-xs font-semibold bg-emerald-50 text-emerald-700">
                                                            <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                                            </svg>
                                                            Deposit
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-xs font-semibold bg-rose-50 text-rose-700">
                                                            <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 112 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                            </svg>
                                                            Withdrawal
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4">
                                                    <p class="text-slate-600 line-clamp-1 text-sm font-normal">
                                                        {{ $transaction->remarks ?: '—' }}
                                                    </p>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                                    <span class="font-bold tabular-nums {{ $isDeposit ? 'text-emerald-600' : 'text-rose-600' }}">
                                                        {{ $isDeposit ? '+' : '-' }}₱{{ number_format((float) $transaction->amount, 2) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="px-6 py-16 text-center">
                                                    <div class="flex flex-col items-center justify-center max-w-xs mx-auto">
                                                        <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center text-slate-400 mb-3">
                                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                            </svg>
                                                        </div>
                                                        <h4 class="text-sm font-semibold text-slate-800">No transactions recorded</h4>
                                                        <p class="text-xs text-slate-500 mt-1">
                                                            Use the form on the left to post the member's initial deposit or transaction.
                                                        </p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            @endforeach

        </div>
    </div>
</x-app-layout>