<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Financial Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-indigo-500 flex justify-between items-center">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">Welcome back, {{ $member->full_name }}!</h3>
                    <p class="text-gray-600 mt-1">Cooperative ID: <span class="font-mono bg-gray-100 px-2 py-1 rounded">{{ $member->member_id_number }}</span></p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Membership Status</p>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 capitalize">
                        {{ $member->membership_status }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 relative">
                    <div class="absolute top-0 right-0 p-4 opacity-10">
                        <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path></svg>
                    </div>
                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Share Capital</h4>
                    <p class="mt-2 text-3xl font-bold text-gray-900">₱0.00</p>
                    <p class="mt-2 text-sm text-indigo-600 hover:text-indigo-900 cursor-pointer">View ledger &rarr;</p>
                </div>


            {{-- // SAVINGS --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 relative">
                    <div>
                        <div class="absolute top-0 right-0 p-4 opacity-10">
                            <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
                        </div>
                        <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Savings Accounts</h4>
                    </div>
                    @forelse ($member->savingsAccounts as $account)
                        <div class="min-h-20 overflow-hidden shadow-sm sm:rounded-lg py-2 mt-1 relative">
                            <p class="text-xs">Account Number: {{ $account->account_number }}</p>
                            <p class="mt-2 text-3xl font-bold text-gray-900">₱ {{ $account->balance }}</p>
                            <p class="mt-2 text-sm text-indigo-600 hover:text-indigo-900 cursor-pointer">View passbook &rarr;</p>

                        </div>
                    @empty
                        <p> Please open a savings account </p>
                        
   
                    @endforelse
                    {{-- <p class="mt-2 text-3xl font-bold text-gray-900">₱0.00</p> --}}
                    
                </div>




                {{-- LOAN --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 relative">
                    <div class="absolute top-0 right-0 p-4 opacity-10 text-red-500">
                        <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd"></path></svg>
                    </div>
                    

                    
                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Active Loan Balance</h4>
                     
                        @forelse ($member->loans->where('status','active') as $loan)
                            <div class="min-h-20 overflow-hidden shadow-sm sm:rounded-lg py-2 mt-1 relative">
                                <p class="text-xs">Loan: {{ $loan->product->name }}</p>
                                <p class="text-xs">Interest Method: {{ $loan->product->interest_method->value }}</p>
                                <p class="text-xs">Interest Amount: {{ $loan->loanSchedules->sum('interest_due') }}</p>
                                <p class="text-xs">Principal Amount: {{ $loan->principal_amount }}</p>
                                <p class="text-xs">Remaining Principal: ₱{{ number_format($loan->remainingBalance, 2) }}</p>
                                <p class="mt-2 text-3xl font-bold text-gray-900"><span class="text-xs">Payoff: </span>₱{{ number_format($loan->payoffAmount, 2) }}</p>

             
                            
                            </div>

                            
                        @empty
                           <div class="min-h-20 overflow-hidden shadow-sm sm:rounded-lg py-2 mt-1 relative">
                            <p class="text-xs">No active loan</p>
                            <p class="mt-2 text-3xl font-bold text-red-600">₱0.00</p>
                            
                            
                        </div>
                        @endforelse

        
                    
                    
                    <p class="mt-2 text-sm text-red-600 hover:text-red-900 cursor-pointer">View schedule &rarr;</p>
                </div>

            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Recent Transactions</h3>
                <div class="text-center py-6 text-gray-500">
                    <p>No recent transactions to display.</p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>