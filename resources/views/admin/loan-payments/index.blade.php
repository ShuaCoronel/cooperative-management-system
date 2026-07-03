<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Loan Payment Ledger') }}
            </h2>
            <a href="{{ route('admin.loan-payment.create') }}" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600
                         border border-transparent rounded-md font-semibold text-xs
                         text-white uppercase tracking-widest hover:bg-blue-600
                         focus:bg-blue-700 active:bg-blue-900 focus:outline-none
                         focus:ring-indigo-500 focus:ring-offset-2
                         transition ease-in-out duration-150">

                         Record New Payment

            </a>
        </div>
    </x-slot>

    <div class='py-12'>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400
                 text-green-700 px-4 py-3 rounded relative" role="alert">
                 <span class="block sm:inline">{{ session('success') }}</span>

                </div>
                
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    <table class=" min-w-full divide-y divide-gray-200">
                        <thead class=" bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-light text-gray-500 uppercase tracking-wider">DATE</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-light text-gray-500 uppercase tracking-wider">Loan ID / MEMBER</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-light text-gray-500 uppercase tracking-wider">Amount Paid</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-light text-gray-500 uppercase tracking-wider">Principal Breakdown</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-light text-gray-500 uppercase tracking-wider">Interest Breakdown</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-light text-gray-500 uppercase tracking-wider">Method $ Ref</th>

                                
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($payments as $payment)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($payment->payment_date)->format('M d,y') }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span class="font-semibold">{{ $payment->loan_id }}</span>
                                    <div class="text-xs text-gray-500">{{ $payment->loan->member->full_name ?? 'Unknown Member'}}</div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600">
                                    Php{{ number_format($payment->amount_paid, 2) }}    
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    Php{{ number_format($payment->principal_paid, 2) }}

                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    Php{{ number_format($payment->interest_paid, 2) }}

                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ucfirst($payment->payment_method) }}
                                    @if ($payment->reference_number)
                                    <div class="text-xs text-gray-400 font-mono mt-1">
                                        Ref: {{ $payment->reference_number }} 
                                    </div>
                                    @endif

                                </td>   
                            </tr>
                                
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-20 whitespace-nowrap text-sm text-gray-500 text-center">
                                        No loan payments have been recorded yet
                                    </td>
                                </tr>
                                
                            @endforelse
                        </tbody>
                    </table>
                    @if(method_exists($payments, 'links'))
                        <div class="mt-6">
                            {{ $payments->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>