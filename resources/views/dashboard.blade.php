<x-layout>
    <div class="p-8 bg-gray-50 min-h-screen">
        <!-- Dashboard Header -->
        <div class="mb-4">
            <p class="text-gray-600 m-0 p-0 text-2xl">Hi, {{ $user->first_name }}!</p>
        </div>
   
        <div class="mb-2 flex">
            <a href="{{ route('sales.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition ml-auto">
                + Record Sale
            </a>
        </div>

        <!-- Sales Made Today Section -->
        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 mb-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-semibold text-gray-900">Today</h2>
                <span class="text-sm font-medium text-gray-500">
                    {{ now('Africa/Lagos')->format('M d, Y') }}
                    {{-- <img class="mt-0.5 cursor-pointer" src="{{ asset('images/history-icon.svg') }}" alt="" title="History"> --}}
                </span>
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
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($todaysSales as $sale)
                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">#{{ $sale->id }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">
                                        {{ $sale->products->count() }} item(s)
                                    </td>
                                    <td class="px-4 py-3 text-sm font-semibold text-blue-600">
                                        {{ $currency }}{{ number_format($sale->total_amount, 2) }}
                                    </td>
                                    <td class="px-4 py-3 text-sm font-semibold text-emerald-600">
                                        +{{ $currency }}{{ number_format($sale->total_profit, 2) }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-500">
                                        {{ $sale->created_at->format('g:i A') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <a href="{{ route('sales.show', $sale) }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4" x-show="query === ''">
                    {{ $todaysSales->links() }}
            </div>
                </div>

                <!-- Summary Stats -->
                <div class="grid grid-cols-3 gap-4 mt-8 pt-6 border-t border-gray-200">
                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                        <p class="text-gray-600 text-sm font-medium">Total Sales</p>
                        <p class="text-3xl font-bold text-blue-600 mt-2">
                            {{ $currency }}{{ number_format($todaysSales->sum('total_amount'), 2) }}
                        </p>
                    </div>
                    <div class="bg-emerald-50 rounded-lg p-4 border border-emerald-200">
                        <p class="text-gray-600 text-sm font-medium">Total Profit</p>
                        <p class="text-3xl font-bold text-emerald-600 mt-2">
                            {{ $currency }}{{ number_format($todaysSales->sum('total_profit'), 2) }}
                        </p>
                    </div>
                    <div class="bg-indigo-50 rounded-lg p-4 border border-indigo-200">
                        <p class="text-gray-600 text-sm font-medium">Transactions</p>
                        <p class="text-3xl font-bold text-indigo-600 mt-2">
                            {{ $todaysSales->total() }}
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

         <!-- Inside your Dashboard Blade file -->
        <div class="report-section bg-white p-2 border border-gray-300 rounded mb-4">
            <h3>Download Sales Report</h3>
            
            <form action="{{ route('reports.download') }}" method="GET" style="display: flex; gap: 10px;">
                <div>
                    <label for="date">Select Date:</label>
                    <input type="date" name="date" id="date" value="{{ today()->toDateString() }}" class="form-control">
                </div>
                
                <div style="align-self: flex-end;">
                    <button type="submit" class="btn btn-primary">
                        Generate PDF
                    </button>
                </div>
            </form>
        </div>

    <!-- Dynamic charts -->
    <div class="flex gap-2">
        <a href="{{ request()->fullUrlWithQuery(['period' => 'week']) }}" 
            class="px-6 py-3 rounded-lg font-medium transition-colors {{ request('period', 'week') === 'week'
            ? 'bg-blue-600 text-white shadow-md' 
            : 'bg-gray-200 text-gray-800 hover:bg-gray-300' }}">
            This Week
        </a>
        
        <a href="{{ request()->fullUrlWithQuery(['period' => 'month']) }}" 
            class="px-6 py-3 rounded-lg font-medium transition-colors {{ request('period') === 'month' 
            ? 'bg-blue-600 text-white shadow-md' 
            : 'bg-gray-200 text-gray-800 hover:bg-gray-300' }}">
            This Month
        </a>
    </div>

        <div class="mt-4">
            {!! $chart->container() !!}
            {{ $chart->script() }}
        </div>

    </div>
</x-layout>