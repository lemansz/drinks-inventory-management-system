<x-layout>
    <!-- Keep max-w-4xl while managing padding fluidly (py-6 px-4 md:py-8) -->
    <div class="max-w-4xl mx-auto py-6 px-4 md:py-8">
        
        <!-- Header Viewport Adjustment -->
        <!-- Changes: Flexes to a stacked column on mobile, changes text-alignment hierarchy, and handles spacing dynamically -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-8">
            <div class="order-2 sm:order-1">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Sale #{{ $sale->id }}</h1>
                <p class="text-sm md:text-base text-gray-600 mt-1 md:mt-2">{{ $sale->created_at->format('F d, Y - g:i A') }}</p>
            </div>
            <div class="order-1 sm:order-2">
                <a href="{{ route('sales.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-semibold text-sm md:text-base">
                    ← Back to Sales
                </a>
            </div>
        </div>

        <!-- Summary Stats Matrix -->
        <!-- Changes: Stacks on mobile, moves to 2 columns on tablets (sm), and steps up to 3 columns on desktops (lg) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-5 md:p-6 border border-gray-100">
                <p class="text-gray-500 text-xs md:text-sm font-medium mb-1">Profit</p>
                <p class="text-2xl md:text-3xl font-bold text-green-600 break-all">
                    {{ $currency }}{{ number_format($sale->total_profit, 2) }}
                </p>
            </div>
            <div class="bg-white rounded-lg shadow p-5 md:p-6 border border-gray-100">
                <p class="text-gray-500 text-xs md:text-sm font-medium mb-1">Products</p>
                <p class="text-2xl md:text-3xl font-bold text-blue-600">
                    {{ $sale->products->count() }}
                </p>
            </div>
            <div class="bg-white rounded-lg shadow p-5 md:p-6 border border-gray-100 sm:col-span-2 lg:col-span-1">
                <p class="text-gray-500 text-xs md:text-sm font-medium mb-1">Total Quantity</p>
                <p class="text-2xl md:text-3xl font-bold text-purple-600">
                    {{ $sale->products->sum('pivot.quantity_sold') }}
                </p>
            </div>
        </div>

        <!-- Products Data Table Wrapper -->
        <!-- Changes: Added scrolling overflow containment, stripped localized static cell offsets, and standardized cell spacing -->
        <div class="bg-white rounded-lg shadow overflow-hidden mb-8 -mx-4 sm:mx-0 border border-gray-100">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[650px]">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 md:px-6 py-3 text-left text-sm font-semibold text-gray-900">Product Name</th>
                            <th class="px-4 md:px-6 py-3 text-right text-sm font-semibold text-gray-900">Quantity</th>
                            <th class="px-4 md:px-6 py-3 text-right text-sm font-semibold text-gray-900">Price/Unit</th>
                            <th class="px-4 md:px-6 py-3 text-right text-sm font-semibold text-gray-900">Subtotal / Profit</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($sale->products as $product)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 md:px-6 py-4">
                                    <div class="max-w-[200px] sm:max-w-none">
                                        <p class="font-semibold text-gray-900 truncate sm:whitespace-normal">{{ $product->name }}</p>
                                        <p class="text-xs md:text-sm text-gray-500">{{ $product->category->name }}</p>
                                    </div>
                                </td>
                                <td class="px-4 md:px-6 py-4 text-right font-semibold text-gray-900 whitespace-nowrap">
                                    {{ number_format($product->pivot->quantity_sold, 2) }}
                                </td>
                                <td class="px-4 md:px-6 py-4 text-right text-gray-600 whitespace-nowrap">
                                    {{ $currency }}{{ number_format($product->pivot->price_per_unit, 2) }}
                                </td>
                                <td class="px-4 md:px-6 py-4 text-right text-gray-900 whitespace-nowrap">
                                    <div class="font-semibold">
                                        {{ $currency }}{{ number_format($product->pivot->subtotal, 2) }}
                                    </div>
                                    <div class="text-green-600 text-xs mt-0.5">
                                        +{{ $currency }}{{ number_format($product->profit * $product->pivot->quantity_sold, 2) }}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Notes Section Layout -->
        @if ($sale->notes)
            <div class="bg-white rounded-lg shadow p-5 md:p-6 mb-8 border border-gray-100">
                <h2 class="text-base md:text-lg font-semibold text-gray-900 mb-3">Notes</h2>
                <p class="text-sm md:text-base text-gray-700 whitespace-pre-wrap leading-relaxed">{{ $sale->notes }}</p>
            </div>
        @endif

        <!-- Grand Total Summary Banner -->
        <!-- Changes: Uses flex layout adjustments to stack text alignment elegantly on extra narrow displays -->
        <div class="bg-white rounded-lg shadow p-5 md:p-6 border border-gray-100">
            <div class="flex flex-row justify-between items-center">
                <span class="text-base md:text-lg font-bold text-gray-900">Grand Total:</span>
                <span class="text-xl md:text-2xl font-bold text-gray-900 whitespace-nowrap">
                    {{ $currency }}{{ number_format($sale->total_amount, 2) }}
                </span>
            </div>
        </div>
    </div>
</x-layout>
