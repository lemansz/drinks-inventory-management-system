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
                                    {{ $sale->created_at->format('M d, Y - g:i A') }}
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
                                <td class=" py-4 text-sm text-center flex items-center justify-center gap-3">
                                    <!-- View Link -->
                                    <a href="{{ route('sales.show', $sale) }}" 
                                    class="inline-flex items-center text-blue-600 hover:text-blue-800 font-semibold transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        View
                                    </a>

                                    <!-- Print Button (Styled as a small badge/button) -->
                                    <a href="{{ route('sales.receipt', $sale->id) }}" 
                                    target="_blank" 
                                    class="inline-flex items-center px-3 py-1 bg-gray-800 hover:bg-gray-700 text-white rounded-md text-xs font-medium transition-all shadow-sm">
                                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                        Print Receipt
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
@if(session('print_receipt_id'))
    <div id="printNotification" class="fixed bottom-10 right-10 bg-blue-600 text-white p-6 rounded-xl shadow-lg z-50 flex flex-col items-center animate-bounce border-2 border-white">
        <p class="font-bold mb-2 text-center">✅ Sale Recorded!</p>
        
    
        <a href="{{ route('sales.receipt', session('print_receipt_id')) }}" 
           target="_blank" 
           onclick="document.getElementById('printNotification').remove()"
           class="bg-white text-blue-600 px-4 py-2 rounded-lg font-bold text-sm shadow hover:bg-gray-100 transition mb-3">
           🖨️ Click to View Receipt
        </a>

        <button onclick="document.getElementById('printNotification').remove()" 
                class="text-xs opacity-70 hover:opacity-100 underline">
            Dismiss
        </button>
    </div>

    <script>
        window.onload = function() {
            const receiptUrl = "{{ route('sales.receipt', session('print_receipt_id')) }}";
            const printWindow = window.open(receiptUrl, '_blank');

    
            if (printWindow) {
                const notify = document.getElementById('printNotification');
                notify.remove();
            } else {
                document.getElementById('printNotification').classList.remove('animate-bounce');
            }
        };
    </script>
@endif
</x-layout>
