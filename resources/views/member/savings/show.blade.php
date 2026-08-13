<!-- <div>
     Nothing in life is to be feared, it is only to be understood. Now is the time to understand more, so that we may fear less. - Maria Skłodowska-Curie
</div> -->


<!-- 
<app-layout>

    <div class="max-w-7x mx-auto px-4 sm:px-6 lg:px-8 py-8">
        top navigation header
         <div class="mb-6 flex items-center justify-between">
            <div>
                <a href="{{ route('member.dashboard')}}" class=" text-sm font-medium text-indigo-600 hover:text-indigo-500 flex items-center gap-1">
                &larr; Back to Dashboard 
                </a>
            <h1>

                @foreach ( $member->savingsAccounts as $savingsAccount )
                    <p class="text-sm text-gray-500 capitalize">
                    Product: {{ $savingsAccount->product_type }}
                    Account:Number: {{$savingsAccount->account_number}}


                    </p>

                @endforeach
                
            </h1>

            </div>


            

         </div>



    </div>




 </app-layout> 

-->







<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        {{-- Top Navigation & Header --}}
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <a href="{{ route('member.dashboard') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 flex items-center gap-1 mb-2 sm:mb-0">
                    <button class="bg-blue-500">
                        &larr; Back to Dashboard
                    </button>
                </a>

            </div>
                
            

            <div class="bg-white p-4 rounded-lg shadow border border-gray-200 text-left sm:text-right w-full sm:w-auto">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Current Balance</span>
                <div class="text-2xl font-extrabold text-gray-900 mt-1">
                    {{-- Note: This relies on the balance() accessor on the SavingsAccount Model --}}
                    ₱{{ number_format($savingsAccount->balance, 2) }}
                </div>
                <div class="mt-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ ($savingsAccount->status instanceof \BackedEnum ? $savingsAccount->status->value : $savingsAccount->status) === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }} capitalize">
                        {{ $savingsAccount->status instanceof \BackedEnum ? $savingsAccount->status->value : $savingsAccount->status }}
                    </span>
                </div>
            </div>
        </div>


        <div class="mb-3 p-3">
                <h1 class="mt-2 text-2xl font-bold text-gray-900">
                    Passbook Ledger: {{ $savingsAccount->account_number }}
                </h1>
                <p class="text-sm text-gray-500 capitalize mt-1">
                    Product: {{ str_replace('_', ' ', $savingsAccount->product_type instanceof \BackedEnum ? $savingsAccount->product_type->value : $savingsAccount->product_type) }} &bull; 
                    Opened on {{ \Carbon\Carbon::parse($savingsAccount->opened_at)->format('F d, Y') }}
                </p>
        </div>
    

        {{-- Passbook Transaction Table --}}
        <div class="bg-white shadow overflow-hidden sm:rounded-lg border border-gray-200">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Chronological Transaction History</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">All deposits, withdrawals, and interest postings.</p>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remarks / Ref</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Deposit (+)</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Withdrawal (-)</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Running Balance</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php
                            // Initialize running balance accumulator for chronological passbook rendering
                            $runningBalance = 0;
                        @endphp

                        @forelse($savingsAccount->transactions->sortBy([['transaction_date', 'asc'], ['id', 'asc']]) as $transaction)
                            @php
                                $type = $transaction->type instanceof \BackedEnum ? $transaction->type->value : $transaction->type;
                                $isDeposit = $type === 'deposit';
                                
                                // Dynamically accumulate running balance row-by-row in RAM
                                if ($isDeposit) {
                                    $runningBalance += $transaction->amount;
                                } else {
                                    $runningBalance -= $transaction->amount;
                                }
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $transaction->remarks ?: 'Regular Transaction' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium {{ $isDeposit ? 'text-green-600' : 'text-gray-300' }}">
                                    {{ $isDeposit ? '₱' . number_format($transaction->amount, 2) : '—' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium {{ !$isDeposit ? 'text-red-600' : 'text-gray-300' }}">
                                    {{ !$isDeposit ? '₱' . number_format($transaction->amount, 2) : '—' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold text-gray-900 bg-gray-50/50">
                                    ₱{{ number_format($runningBalance, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">
                                    No transactions recorded in this ledger yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>





