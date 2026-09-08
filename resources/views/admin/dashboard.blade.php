<x-app-layout>
    <x-slot name="header">
        <div class="px-6 py-4 flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard') }} 
            </h2>

            <div>
                <a href="{{ route('admin.loan-payments.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Loan Payment Test
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- STATS / DASHBOARD INFO ROW -->
            <!-- Stacks vertically on mobile, switches to 3 columns on medium+ screens -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <!-- Stat Card 1 -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500">Total Active Members</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900">{{ $totalActive }}</div>
                </div>

                <!-- Stat Card 2 (Placeholder for Chart or Metric) -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500">Total No. of Loans</div>
                    <div class="mt-2 text-3xl font-bold text-red-600">0</div>
                </div>
                

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500">Shared Capital Pool</div>
                    <div class="mt-2 text-3xl font-bold text-emerald-600">₱{{$totalSharedCapital}}</div>
                </div>

            </div>

            <!-- TABLE CONTAINER -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-3 py-3 text-center text-xs font-medium text-gray-600 uppercase">Member Name</th>
                            <th class="px-3 py-3 text-center text-xs font-medium text-gray-600 uppercase">Savings Accounts</th>
                            <th class="px-3 py-3 text-center text-xs font-medium text-gray-600 uppercase">No. of Loan/s</th>
                            <th class="px-3 py-3 text-center text-xs font-medium text-gray-600 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($members as $member)
                            <tr>
                                <td class="px-3 py-4 whitespace-nowrap text-center">
                                    <a href="{{ route('admin.savings.show', $member) }}" 
                                       wire:navigate 
                                       class="text-blue-600 hover:text-blue-900 font-semibold hover:underline">
                                        {{ $member->full_name ?? "Unavailable" }}
                                    </a>
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-600 text-center">
                                    {{ $member->savings_accounts_count ?? $member->savingsAccounts->count() }}
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-600 text-center">
                                    {{ $member->loans_count ?? $member->loans?->count() ?? 0 }}
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap text-sm text-center">
                                    <a href="{{ route('admin.savings.show', $member) }}" 
                                       wire:navigate 
                                       class="inline-flex items-center px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-medium rounded transition">
                                        View Ledger &rarr;
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>