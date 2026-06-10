<x-layout>
    
    @if (session('success'))
        <x-alert :message="session('success')" :show="true" />
    @endif

    <div class="p-4 md:p-8 bg-gray-50 min-h-screen">
        
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <p class="text-gray-600 m-0 p-0 text-xl md:text-2xl">Hi, {{ $user->first_name }}!</p>
            </div>
            <div>
                <a href="{{ route('sales.create') }}" class="inline-block text-center w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-6 rounded-lg transition shadow-sm">
                    + Record Sale
                </a>
            </div>
        </div>

        <!-- Sales Made Today Section -->
        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-4 md:p-6 mb-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl md:text-2xl font-semibold text-gray-900">Today</h2>
                <span class="text-xs md:text-sm font-medium text-gray-500 bg-gray-100 px-2.5 py-1 rounded-full">
                    {{ now('Africa/Lagos')->format('M d, Y') }}
                </span>
            </div>

            <!-- Sales Table -->
            @if($todaysSales->count() > 0)
                <!-- The overflow wrapper safely handles large tables on narrow screen widths -->
                <div class="overflow-x-auto -mx-4 px-4 md:mx-0 md:px-0">
                    <table class="w-full min-w-[600px] md:min-w-full">
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
                                    <td class="px-4 py-3 text-sm">
                                        <a href="{{ route('sales.show', $sale) }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $todaysSales->links() }}
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-8 pt-6 border-t border-gray-200">
                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                        <p class="text-gray-600 text-sm font-medium">Total Sales</p>
                        <p class="text-2xl md:text-3xl font-bold text-blue-600 mt-2 break-all">
                            {{ $currency }}{{ number_format($todaysSales->sum('total_amount'), 2) }}
                        </p>
                    </div>
                    <div class="bg-emerald-50 rounded-lg p-4 border border-emerald-200">
                        <p class="text-gray-600 text-sm font-medium">Total Profit</p>
                        <p class="text-2xl md:text-3xl font-bold text-emerald-600 mt-2 break-all">
                            {{ $currency }}{{ number_format($todaysSales->sum('total_profit'), 2) }}
                        </p>
                    </div>
                    <div class="bg-indigo-50 rounded-lg p-4 border border-indigo-200 sm:col-span-2 lg:col-span-1">
                        <p class="text-gray-600 text-sm font-medium">Transactions</p>
                        <p class="text-2xl md:text-3xl font-bold text-indigo-600 mt-2">
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

        <div class="report-section bg-white p-4 md:p-6 border border-gray-200 rounded-lg shadow-md mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Download Sales Report</h3>
            
            <form action="{{ route('reports.download') }}" method="GET" class="flex flex-col sm:flex-row items-end gap-4 w-full">
                <div class="w-full sm:flex-1">
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Select Date:</label>
                    <input type="date" name="date" id="date" value="{{ today()->toDateString() }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-3 py-2 border bg-gray-50">
                </div>
                
                <div class="w-full sm:w-auto">
                    <button type="submit" class="w-full sm:w-auto bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 px-5 rounded-md transition shadow-sm">
                        Generate PDF
                    </button>
                </div>
            </form>
        </div>

        <!-- Dynamic Chart Controls & Visualizer -->
        <div class="flex flex-col gap-4">
            <div class="flex gap-2">
                <a href="{{ request()->fullUrlWithQuery(['period' => 'week']) }}" 
                    class="flex-1 sm:flex-initial text-center px-6 py-3 rounded-lg font-medium transition-colors text-sm md:text-base {{ request('period', 'week') === 'week'
                    ? 'bg-blue-600 text-white shadow-md' 
                    : 'bg-gray-200 text-gray-800 hover:bg-gray-300' }}">
                    This Week
                </a>
                
                <a href="{{ request()->fullUrlWithQuery(['period' => 'month']) }}" 
                    class="flex-1 sm:flex-initial text-center px-6 py-3 rounded-lg font-medium transition-colors text-sm md:text-base {{ request('period') === 'month' 
                    ? 'bg-blue-600 text-white shadow-md' 
                    : 'bg-gray-200 text-gray-800 hover:bg-gray-300' }}">
                    This Month
                </a>
            </div>

            <div class="mt-2 bg-white p-4 rounded-lg shadow-md border border-gray-200 overflow-x-hidden w-full">
                {!! $chart->container() !!}
                {{ $chart->script() }}
            </div>
        </div>

    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            window.dispatchEvent(new Event('resize'));
        }, 150);
    });
</script>

</x-layout>
