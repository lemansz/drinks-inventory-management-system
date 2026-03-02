<x-layout>
    <div class="max-w-6xl mx-auto py-8 px-4">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Sales Records</h1>
                <p class="text-gray-600 mt-2">View and manage all recorded sales</p>
            </div>
            <a href="{{ route('sales.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition">
                + Record Sale
            </a>
        </div>

        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-green-700">{{ session('success') }}</p>
            </div>
        @endif

        @if ($sales->isEmpty())
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <p class="text-gray-500 text-lg mb-4">No sales recorded yet</p>
                <a href="{{ route('sales.create') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition">
                    Record First Sale
                </a>
            </div>
        @else
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Sale ID</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Date</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Products</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Amount</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Profit</th>
                            <th class="px-6 py-3 text-center text-sm font-semibold text-gray-900">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($sales as $sale)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm font-semibold text-gray-900">#{{ $sale->id }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $sale->created_at->format('M d, Y - H:i') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $sale->products->count() }} {{ Str::plural('product', $sale->products->count()) }}
                                </td>
                                <td class="px-6 py-4 text-sm font-semibold text-left text-gray-900">
                                    {{ $currency }}{{ number_format($sale->total_amount, 2) }}
                                </td>
                                <td class="px-6 py-4 text-sm font-semibold text-left text-green-600">
                                    +{{ $currency }}{{ number_format($sale->total_profit, 2) }}
                                </td>
                                <td class="px-6 py-4 text-sm text-center">
                                    <a href="{{ route('sales.show', $sale) }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $sales->links() }}
            </div>
        @endif
    </div>
</x-layout>
