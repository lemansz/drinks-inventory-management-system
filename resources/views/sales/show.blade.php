<x-layout>
    <div class="max-w-4xl mx-auto py-8 px-4">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Sale #{{ $sale->id }}</h1>
                <p class="text-gray-600 mt-2">{{ $sale->created_at->format('F d, Y - g:i A') }}</p>
            </div>
            <a href="{{ route('sales.index') }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                ← Back to Sales
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Sale Summary Cards -->
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm mb-1">Profit</p>
                <p class="text-3xl font-bold text-green-600">{{ $currency }}{{ number_format($sale->total_profit, 2) }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm mb-1">Products</p>
                <p class="text-3xl font-bold text-blue-600">{{ $sale->products->count() }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm mb-1">Total Quantity</p>
                <p class="text-3xl font-bold text-purple-600">{{ $sale->products->sum('pivot.quantity_sold') }}</p>
            </div>
        </div>

        <!-- Products Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden mb-8">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Product Name</th>
                        <th class="px-6 py-3 text-right text-sm font-semibold text-gray-900">Quantity</th>
                        <th class="px-6 py-3 text-right text-sm font-semibold text-gray-900">Price/Unit</th>
                        <th class="px-14 py-3 text-right text-sm font-semibold text-gray-900">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($sale->products as $product)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $product->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $product->category->name }}</p>
                                </div>
                            </td>
                            <td class="px-10 py-4 text-right">
                                <span class="font-semibold text-gray-900">{{ number_format($product->pivot->quantity_sold, 2) }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="text-gray-900">{{ $currency }}{{ number_format($product->pivot->price_per_unit, 2) }}</span>
                            </td>
                            <td class="px-8 py-4 text-right">
                                <span class="font-semibold text-gray-900">{{ $currency }}{{ number_format($product->pivot->subtotal, 2) }}<span class="text-green-600 text-xs">/{{ $currency }}{{ number_format($product->profit * $product->pivot->quantity_sold, 2) }}</span></span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Notes Section -->
        @if ($sale->notes)
            <div class="bg-white rounded-lg shadow p-6 mb-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Notes</h2>
                <p class="text-gray-700 whitespace-pre-wrap">{{ $sale->notes }}</p>
            </div>
        @endif

        <!-- Total Summary -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center border-t pt-4">
                <span class="text-lg font-bold text-gray-900">Grand Total:</span>
                <span class="text-2xl font-bold text-gray-900">{{ $currency }}{{ number_format($sale->total_amount, 2) }}</span>
                
            </div>
        </div>
    </div>
</x-layout>
