<x-layout>
    <div class="ml-48 p-8 bg-gray-50 min-h-screen">
        <!-- Dashboard Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900">Dashboard</h1>
            <p class="text-gray-600 mt-2">Welcome back! Here's your sales overview.</p>
        </div>

        <!-- Sales Made Today Section -->
        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 mb-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-semibold text-gray-900">Sales Made Today</h2>
                <span class="text-sm font-medium text-gray-500">{{ now()->format('M d, Y') }}</span>
            </div>

            <!-- Sales Table -->
            @if($todaysSales->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200 bg-gray-50">
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Sale ID</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Items</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Total Amount</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Profit</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($todaysSales as $sale)
                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">#{{ $sale->id }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">
                                        {{ $sale->products->count() }} item(s)
                                    </td>
                                    <td class="px-4 py-3 text-sm font-semibold text-emerald-600">
                                        PHP {{ number_format($sale->total_amount, 2) }}
                                    </td>
                                    <td class="px-4 py-3 text-sm font-semibold text-blue-600">
                                        PHP {{ number_format($sale->total_profit, 2) }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-500">
                                        {{ $sale->created_at->format('g:i A') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Summary Stats -->
                <div class="grid grid-cols-3 gap-4 mt-8 pt-6 border-t border-gray-200">
                    <div class="bg-emerald-50 rounded-lg p-4 border border-emerald-200">
                        <p class="text-gray-600 text-sm font-medium">Total Sales</p>
                        <p class="text-3xl font-bold text-emerald-600 mt-2">
                            PHP {{ number_format($todaysSales->sum('total_amount'), 2) }}
                        </p>
                    </div>
                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                        <p class="text-gray-600 text-sm font-medium">Total Profit</p>
                        <p class="text-3xl font-bold text-blue-600 mt-2">
                            PHP {{ number_format($todaysSales->sum('total_profit'), 2) }}
                        </p>
                    </div>
                    <div class="bg-indigo-50 rounded-lg p-4 border border-indigo-200">
                        <p class="text-gray-600 text-sm font-medium">Transactions</p>
                        <p class="text-3xl font-bold text-indigo-600 mt-2">
                            {{ $todaysSales->count() }}
                        </p>
                    </div>
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-lg font-medium text-gray-900">No sales today</h3>
                    <p class="mt-1 text-gray-500">Get started by making your first sale of the day.</p>
                </div>
            @endif
        </div>
    </div>
</x-layout>