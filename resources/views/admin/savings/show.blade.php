<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Top Navigation & Success Messages --}}
            <div class="flex justify-between items-center">
                <a href="{{ url()->previous() }}" class="inline-flex items-center gap-1 text-sm font-medium text-indigo-600 hover:text-indigo-500 transition">
                    &larr; Back to Accounts
                </a>
            </div>

            @if (session('success'))
                <div class="rounded-md bg-green-50 p-4 border border-green-200">
                    <div class="flex">
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-green-800">{{ session('success') }}</h3>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Account Header --}}
            <div class="bg-white shadow rounded-lg border border-gray-200 p-6 flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        Savings Account: <span class="font-mono text-indigo-600">{{ $savingsAccount->account_number }}</span>
                    </h1>
                    <p class="text-sm text-gray-500 mt-1 capitalize">
                        Member: <span class="font-semibold text-gray-700">{{ $savingsAccount->member->full_name ?? 'N/A' }} {{ $savingsAccount->member->last_name ?? '' }}</span> <br>
                        {{-- &bull; just a bullet --}}
                        Status: <span class="bg-green-100 rounded-sm px-1">{{ $savingsAccount->status instanceof \BackedEnum ? $savingsAccount->status->value : $savingsAccount->status }}</span>
                    </p>
                </div>
                <div class="mt-4 md:mt-0 text-left md:text-right bg-gray-50 p-4 rounded-lg border border-gray-100">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Current Balance</span>
                    <div class="text-3xl font-extrabold text-gray-900 mt-1">
                        {{-- Safely using our exact BCMath aggregated accessor --}}
                        ₱{{ number_format((float) $savingsAccount->balance, 2) }}
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- Left Column: Transaction Form --}}
                <div class="lg:col-span-1">
                    <div class="bg-white shadow rounded-lg border border-gray-200">
                        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Post Transaction</h3>
                            <p class="mt-1 text-sm text-gray-500">Record a new deposit or withdrawal.</p>
                        </div>
                        
                        <div class="p-4 sm:p-6">
                            <form action="{{ route('admin.savings.transactions.store', $savingsAccount) }}" method="POST" class="space-y-4">
                                @csrf

                                {{-- Transaction Type --}}
                                <div>
                                    <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                                    <select id="type" name="type" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                        <option value="" disabled selected>Select type...</option>
                                        {{-- Ensure these values match your TransactionType Enum exactly! --}}
                                        <option value="deposit" @selected(old('type') == 'deposit')>Deposit</option>
                                        <option value="withdrawal" @selected(old('type') == 'withdrawal')>Withdrawal</option>
                                    </select>
                                    @error('type') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                {{-- Amount --}}
                                <div>
                                    <label for="amount" class="block text-sm font-medium text-gray-700">Amount (₱)</label>
                                    <input type="number" name="amount" id="amount" step="0.01" min="0.01" value="{{ old('amount') }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    @error('amount') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                {{-- Transaction Date --}}
                                <div>
                                    <label for="transaction_date" class="block text-sm font-medium text-gray-700">Date</label>
                                    <input type="date" name="transaction_date" id="transaction_date" value="{{ old('transaction_date', date('Y-m-d')) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    @error('transaction_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                {{-- Remarks --}}
                                <div>
                                    <label for="remarks" class="block text-sm font-medium text-gray-700">Remarks (Optional)</label>
                                    <textarea name="remarks" id="remarks" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('remarks') }}</textarea>
                                    @error('remarks') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="pt-2">
                                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Process Transaction
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Right Column: Recent Ledger --}}
                <div class="lg:col-span-2">
                    <div class="bg-white shadow rounded-lg border border-gray-200 overflow-hidden">
                        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Recent Transactions</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remarks</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($savingsAccount->transactions as $transaction)
                                        @php
                                            $type = $transaction->type instanceof \BackedEnum ? $transaction->type->value : $transaction->type;
                                            $isDeposit = $type === 'deposit';
                                        @endphp
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $isDeposit ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} capitalize">
                                                    {{ $type }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium {{ $isDeposit ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $isDeposit ? '+' : '-' }} ₱{{ number_format((float) $transaction->amount, 2) }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                {{ $transaction->remarks ?: '--' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500">
                                                No transactions recorded yet.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>