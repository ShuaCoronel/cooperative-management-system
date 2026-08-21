<x-app-layout>
    <x-slot name="header">
        <div class="px-6 py-4 flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard') }}
            </h2>


            <div>
                <a href="{{ route('admin.loan-payments.index') }}" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600
                         border border-transparent rounded-md font-semibold text-xs
                         text-white uppercase tracking-widest hover:bg-blue-600
                         focus:bg-blue-700 active:bg-blue-900 focus:outline-none
                         focus:ring-indigo-500 focus:ring-offset-2
                         transition ease-in-out duration-150">

                         Loan Payment Test

                </a>
                {{-- @foreach ($members as $member )
                    
               
                <x-primary-button :href="route('admin.savings.show', $member->savingsAccount)" class="!bg-blue-500" wire:navigate>
                    Savings Transaction
                </x-primary-button>

                 @endforeach --}}

                

        </div>


        </div>
    </x-slot>

    <div class="py-12">
        {{-- <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 text-wrap text-center">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div> --}}

        <table class="min-w-full divide-y divide-gray-200">
    <thead>
        <tr class="bg-gray-50">
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Member Name</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Account Number</th>
            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
        @foreach ($members as $member)
            @php
                $account = $member->savingsAccounts->first();
            @endphp
            <tr>
                <!-- 1. Clickable Member Name -->
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($account)
                        <a href="{{ route('admin.savings.show', $account) }}" 
                           wire:navigate 
                           class="text-blue-600 hover:text-blue-900 font-semibold hover:underline">
                            {{ $member->full_name ?? $member->name }}
                        </a>
                    @else
                        <span class="text-gray-900 font-medium">{{ $member->full_name ?? $member->name }}</span>
                    @endif
                </td>

                <!-- 2. Account Number -->
                <td class="px-6 py-4 whitespace-nowrap font-mono text-sm text-gray-600">
                    {{ $account ? $account->account_number : 'No Account' }}
                </td>

                <!-- 3. Keep buttons for primary explicit actions -->
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                    @if($account)
                        <a href="{{ route('admin.savings.show', $account) }}" 
                           wire:navigate 
                           class="inline-flex items-center px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-medium rounded transition">
                            View Ledger &rarr;
                        </a>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
        
    </div>
</x-app-layout>
