<x-layout>
    <div class="p-4">
        <div class="container mx-auto px-4 max-w-6xl">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-xl font-semibold text-gray-800">Restock History</h1>
                <a href="{{ route('restock.index') }}" class="text-sm text-gray-500 hover:text-gray-700 transition-colors">
                   ← Back to Restock
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Crates/Pack</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Units Added</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Total Cost</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Date</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($logs as $log)
                            <tr class="hover:bg-gray-50 cursor-pointer transition-colors text-left"
                            onclick="window.location.href='{{ route('restock-history.show', $log->id) }}'">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $log->product->name }}</td>
                            <td class="px-12 py-4 whitespace-nowrap text-sm font-medium text-gray-700 text-">{{ $log->crates }}</td>
                            <td class="px-12 py-4 whitespace-nowrap text-sm font-medium text-gray-700 text-">{{ $log->total_units }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-700 text-">{{ $currency }}{{ number_format($log->total_cost, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-700 text-">{{ $log->restocked_at->format('M d, Y - g:i A') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">No restock history yet.</td>
                            </tr>
                            
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</x-layout>