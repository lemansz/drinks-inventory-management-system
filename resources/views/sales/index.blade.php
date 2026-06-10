<x-layout>
    @if (session('success'))
        <x-alert :message="session('success')" :show="true" />
    @endif
   
    <div class="max-w-6xl mx-auto py-6 px-4 md:py-8">
        
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-8">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Sales Records</h1>
                <p class="text-sm md:text-base text-gray-600 mt-1 md:mt-2">View and manage all recorded sales</p>
            </div>
            <div>
                <a href="{{ route('sales.create') }}" class="inline-block text-center w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-6 rounded-lg transition shadow-sm">
                    + Record Sale
                </a>
            </div>
        </div>



        @if ($sales->isEmpty())
            <div class="bg-white rounded-lg shadow p-8 md:p-12 text-center">
                <p class="text-gray-500 text-base md:text-lg mb-4">No sales recorded yet</p>
                <a href="{{ route('sales.create') }}" class="inline-block w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition">
                    Record First Sale
                </a>
            </div>
        @else
            <div class="bg-white rounded-lg shadow overflow-hidden -mx-4 sm:mx-0">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[700px]">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-4 md:px-6 py-3 text-left text-sm font-semibold text-gray-900">Sale ID</th>
                                <th class="px-4 md:px-6 py-3 text-left text-sm font-semibold text-gray-900">Date</th>
                                <th class="px-4 md:px-6 py-3 text-left text-sm font-semibold text-gray-900">Products</th>
                                <th class="px-4 md:px-6 py-3 text-left text-sm font-semibold text-gray-900">Amount</th>
                                <th class="px-4 md:px-6 py-3 text-left text-sm font-semibold text-gray-900">Profit</th>
                                <th class="px-4 md:px-6 py-3 text-center text-sm font-semibold text-gray-900">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($sales as $sale)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-4 md:px-6 py-4 text-sm font-semibold text-gray-900">#{{ $sale->id }}</td>
                                    <td class="px-4 md:px-6 py-4 text-sm text-gray-600 whitespace-nowrap">
                                        {{ $sale->created_at->format('M d, Y - g:i A') }}
                                    </td>
                                    <td class="px-4 md:px-6 py-4 text-sm text-gray-600 whitespace-nowrap">
                                        {{ $sale->products->count() }} {{ Str::plural('product', $sale->products->count()) }}
                                    </td>
                                    <td class="px-4 md:px-6 py-4 text-sm font-semibold text-left text-gray-900 whitespace-nowrap">
                                        {{ $currency }}{{ number_format($sale->total_amount, 2) }}
                                    </td>
                                    <td class="px-4 md:px-6 py-4 text-sm font-semibold text-left text-green-600 whitespace-nowrap">
                                        +{{ $currency }}{{ number_format($sale->total_profit, 2) }}
                                    </td>
                                    <!-- Flex actions row remains stable using whitespace-nowrap safety boundaries -->
                                    <td class="px-4 md:px-6 py-4 text-sm text-center flex items-center justify-center gap-3 whitespace-nowrap">
                                        <!-- View Link -->
                                        <a href="{{ route('sales.show', $sale) }}" 
                                        class="inline-flex items-center text-blue-600 hover:text-blue-800 font-semibold transition-colors duration-200">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            View
                                        </a>

                                        <!-- Print Button -->
                                        <a href="{{ route('sales.receipt', $sale->id) }}" 
                                        target="_blank" 
                                        class="inline-flex items-center px-3 py-1.5 bg-gray-800 hover:bg-gray-700 text-white rounded-md text-xs font-medium transition-all shadow-sm">
                                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                            Print
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination Container -->
            <div class="mt-6">
                {{ $sales->links() }}
            </div>
        @endif
    </div>

    <!-- Notification Window Container -->
    <!-- Changes: Swapped out absolute margins to fluid bottom layout layers (left-4 right-4 sm:left-auto sm:right-10) -->
    @if(session('print_receipt_id'))
        <div id="printNotification" class="fixed bottom-4 left-4 right-4 sm:left-auto sm:right-10 sm:bottom-10 sm:w-80 bg-blue-600 text-white p-5 rounded-xl shadow-2xl z-50 flex flex-col items-center animate-bounce border-2 border-white">
            <p class="font-bold mb-2 text-center">✅ Sale Recorded!</p>
            
            <a href="{{ route('sales.receipt', session('print_receipt_id')) }}" 
               target="_blank" 
               onclick="document.getElementById('printNotification').remove()"
               class="bg-white text-blue-600 text-center w-full px-4 py-2 rounded-lg font-bold text-sm shadow hover:bg-gray-100 transition mb-3">
               🖨️ Click to View Receipt
            </a>

            <button onclick="document.getElementById('printNotification').remove()" 
                    class="text-xs opacity-70 hover:opacity-100 underline focus:outline-none">
                Dismiss
            </button>
        </div>

        <script>
            window.onload = function() {
                const receiptUrl = "{{ route('sales.receipt', session('print_receipt_id')) }}";
                const printWindow = window.open(receiptUrl, '_blank');

                if (printWindow) {
                    const notify = document.getElementById('printNotification');
                    if (notify) notify.remove();
                } else {
                    const notify = document.getElementById('printNotification');
                    if (notify) notify.classList.remove('animate-bounce');
                }
            };
        </script>
    @endif
</x-layout>
