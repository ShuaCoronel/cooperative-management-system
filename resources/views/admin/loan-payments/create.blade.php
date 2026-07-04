

 <x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Record Loan Payment')}}
            </h2>

            <a href="{{ route('admin.loan-payments.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                &larr; Back to Ledger
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    <strong class="font-bold">
                        Something went wrong!
                    </strong>
                    <ul class="mt-2 list-disc list-outside text-sm">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                            
                        @endforeach
                    </ul>       
                </div>
                @endif

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <header class="mb-4">
                    <h2 class="text-lg font-medium text-gray-900">Step 1: Select Member Loan</h2>
                    <p class="mt-1 text-sm text-gray-600">Choose an active loan to view its pending amortization schedules.</p>
                </header>


                <form action="{{ route('admin.loan-payments.create') }}" method="get">
                    <div class=""> 
                        {{-- max-w-xl this adjust the div box placement --}}
                        <x-input-label for="loan_id" value="Active Loans" class="text-center" />
                        <select name="loan_id" id="loan_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" onchange="this.form.submit()">
                            <option class="text-center" value="">-- Select a Loan Account --</option>
                            @foreach($loans as $loan)
                                <option value="{{ $loan->id }}" {{ request('loan_id') == $loan->id ? 'selected' : '' }} class="justify-between">
                                    Loan #{{ $loan->id }} - {{ $loan->member->member_id_number ?? 'Unknown' }} |
                                    {{ $loan->member->full_name }}  
                                     | (Principal: ₱{{ number_format($loan->principal_amount, 2) }})
                                    
                                    
                                </option>
                            @endforeach
                        </select>

                        {{-- <x-input-label for="full_name" value="Members name"/>
                        <select name="name" id="full_name" class="text-center block w-full border-red-600 rounded">
                            <option value="$loan->member->full_name">name</option>
                        </select> --}}
                        

                    </div>
                </form>
            </div>      

            @if($selectedLoan)
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <header class="mb-6">
                        <h2 class="text-lg font-medium text-gray-900">Step 2: Enter Payment Details</h2>
                        <p class="mt-1 text-sm text-gray-600">
                            The system will automatically allocate this payment to interest first, then principal, based on the oldest unpaid schedules.
                        </p>
                    </header>

                    <form method="POST" action="{{ route('admin.loan-payments.store') }}" class="space-y-6">
                        @csrf
                        <input type="hidden" name="loan_id" value="{{ $selectedLoan->id }}">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="amount_paid" value="Payment Amount (₱)" />
                                <x-text-input id="amount_paid" name="amount_paid" type="number" step="0.01" min="0.01" class="mt-1 block w-full" :value="old('amount_paid')" required autofocus />
                            </div>

                            <div>
                                <x-input-label for="payment_date" value="Payment Date" />
                                <x-text-input id="payment_date" name="payment_date" type="date" class="mt-1 block w-full" :value="old('payment_date', now()->toDateString())" required />
                            </div>

                            <div>
                                <x-input-label for="payment_method" value="Payment Method" />
                                <select id="payment_method" name="payment_method" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    <option value="check" {{ old('payment_method') == 'check' ? 'selected' : '' }}>Check</option>
                                </select>
                            </div>

                            <div>
                                <x-input-label for="reference_number" value="Reference/Check Number (Optional)" />
                                <x-text-input id="reference_number" name="reference_number" type="text" class="mt-1 block w-full" :value="old('reference_number')" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="remarks" value="Remarks (Optional)" />
                            <textarea id="remarks" name="remarks" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3">{{ old('remarks') }}</textarea>
                        </div>

                        <div class="mt-6 bg-gray-50 p-4 rounded-md border border-gray-200">
                            <h3 class="text-sm font-semibold text-gray-700 mb-2">Pending Amortization Target(s)</h3>
                            <ul class="text-xs text-gray-600 space-y-1">
                                @forelse($pendingSchedules as $schedule)
                                    <li>
                                        Period {{ $schedule->period_number }} (Due: {{ \Carbon\Carbon::parse($schedule->due_date)->format('M d, Y') }}) 
                                        - Principal: ₱{{ number_format($schedule->principal_due, 2) }}, 
                                        Interest: ₱{{ number_format($schedule->interest_due, 2) }}
                                    </li>
                                @empty
                                    <li class="text-green-600 font-semibold">No pending schedules found. This payment will apply as advance principal.</li>
                                @endforelse
                            </ul>
                        </div>

                        <div class="flex items-center gap-4 mt-6">
                            <x-primary-button type="submit" class="bg-green-600 hover:bg-green-700">
                                {{ __('Process Payment & Allocate') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            @endif

        </div>
    </div>
 </x-app-layout>
