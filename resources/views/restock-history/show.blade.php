<x-layout>
    <div class="p-4">
        <div class="text-xl font-semibold justify-between mb-6">
            <h1 class="text-xl font-semibold text-gray-800">Restock Details</h1>
            <a href="{{ route('restock-history.index') }}" class="text-sm text-gray-500 hover:text-gray-700 transition-colors">
               ← Back to History
            </a>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 divide-y divide-gray-100">
            <div class="px-6 py-4 flex justify-between items-center">
                <span class="text-sm text-gray-500">Product</span>
                <span class="text-sm font-medium text-gray-900">{{ $log->product->name }}</span>
            </div>
            <div class="px-6 py-4 flex justify-between items-center">
                <span class="text-sm text-gray-500">Crates Restocked</span>
                <span class="text-sm font-medium text-gray-900">{{ $log->crates }}</span>
            </div>
            <div class="px-6 py-4 flex justify-between items-center">
                <span class="text-sm text-gray-500">Units per Crate</span>
                <span class="text-sm font-medium text-gray-900">{{ $log->units_per_crate }}</span>
            </div>
            <div class="px-6 py-4 flex justify-between items-center">
                <span class="text-sm text-gray-500">Total Units Added</span>
                <span class="text-sm font-medium text-gray-900">{{ $log->total_units }}</span>
            </div>
            <div class="px-6 py-4 flex justify-between items-center">
                <span class="text-sm text-gray-500">Unit Cost (at time)</span>
                <span class="text-sm font-medium text-gray-900">{{ $currency }}{{ number_format($log->total_cost, 2) }}</span>
            </div>
            <div class="px-6 py-4 flex justify-between items-center">
                <span class="text-sm text-gray-500">Supplier (at time)</span>
                <span class="text-sm font-medium text-gray-900">{{ $log->product->supplier }} | {{ $log->product->supplier_phone_no }}</span>
            </div>
            <div class="px-6 py-4 flex justify-between items-center">
                <span class="text-sm text-gray-500">Date & Time</span>
                <span class="text-sm font-medium text-gray-900">{{ $log->restocked_at->format('M d, Y - g:i A') }}</span>
            </div>
        </div>
    </div>
</x-layout>