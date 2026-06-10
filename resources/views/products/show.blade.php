<x-layout>
    <div class="p-4 md:p-8 bg-gray-50 min-h-screen">
        <!-- Breadcrumb & Top Navigation Actions -->
        <div class="max-w-5xl mx-auto flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div class="flex items-center gap-4">
                <!-- Upgraded Product Photo Sizing -->
                <div class="w-16 h-16 md:w-20 md:h-20 border border-gray-200 rounded-xl overflow-hidden bg-white shadow-sm flex items-center justify-center flex-shrink-0">
                    @if($product->photo)
                        <img src="{{ asset('storage/' . $product->photo) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    @else
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    @endif
                </div>
                <div>
                    <span class="inline-block text-xs font-semibold uppercase tracking-wider text-emerald-800 bg-emerald-50 px-2.5 py-1 rounded-full mb-1">
                        {{ $product->category->name }}
                    </span>
                    <h1 class="text-2xl md:text-4xl font-bold text-gray-900 tracking-tight">{{ $product->name }}</h1>
                </div>
            </div>

            <!-- Action Buttons: Evenly distributed on mobile, grouped neatly on desktop -->
            <div class="grid grid-cols-3 sm:flex items-center gap-2 bg-white p-1.5 rounded-xl shadow-sm border border-gray-200">
                <x-nav-links url="{{ route('products.edit', $product->id) }}" icon="edit-icon.svg" class="flex flex-col sm:flex-row items-center justify-center gap-1.5 py-2 px-4 text-xs md:text-sm font-medium text-gray-700 hover:bg-gray-50 rounded-lg transition">
                    Edit
                </x-nav-links>
                
                <x-nav-links url="{{ route('restock.create', $product->id) }}" icon="restock-icon.svg" class="flex flex-col sm:flex-row items-center justify-center gap-1.5 py-2 px-4 text-xs md:text-sm font-medium text-blue-600 hover:bg-blue-50 rounded-lg transition">
                    Restock
                </x-nav-links>

                <x-nav-links url="{{ route('products.destroy', $product->id) }}" method="DELETE" confirm="Are you sure you want to delete {{ $product->name }}?" icon="delete-icon.svg" class="flex flex-col sm:flex-row items-center justify-center gap-1.5 py-2 px-4 text-xs md:text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition">
                    Delete
                </x-nav-links>
            </div>
        </div>

        <!-- Main Metrics Grid Content -->
        <div class="max-w-5xl mx-auto">
            <!-- Grid automatically transitions from 1 column (mobile) to 2 (tablet) to 3 (desktop) safely without breaking margins -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                
                <!-- Financial Metrics Block -->
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200 flex items-center gap-4">
                    <div class="w-12 h-12 bg-gray-50 rounded-xl flex items-center justify-center p-2.5 flex-shrink-0">
                        <img src="{{ asset('images/cost-icon.svg') }}" alt="" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Cost per Unit</p>
                        <p class="text-xl md:text-2xl font-bold text-gray-900 mt-0.5">{{ $currency }}{{ number_format($product->cost_per_unit, 2) }}</p>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200 flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center p-2.5 flex-shrink-0">
                        <img src="{{ asset('images/price-icon.svg') }}" alt="" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Selling Price</p>
                        <p class="text-xl md:text-2xl font-bold text-blue-600 mt-0.5">{{ $currency }}{{ number_format($product->selling_price, 2) }}</p>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200 flex items-center gap-4 sm:col-span-2 lg:col-span-1">
                    <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center p-2.5 flex-shrink-0">
                        <img src="{{ asset('images/profit-icon.svg') }}" alt="" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Unit Profit</p>
                        <p class="text-xl md:text-2xl font-bold text-emerald-600 mt-0.5">+{{ $currency }}{{ number_format($product->profit, 2) }}</p>
                    </div>
                </div>

                <!-- Stock Tracking Metrics Block -->
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200 flex items-center gap-4">
                    <div class="w-12 h-12 bg-gray-50 rounded-xl flex items-center justify-center p-2.5 flex-shrink-0">
                        <img src="{{ asset('images/crates-available-icon.svg') }}" alt="" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Crates Available</p>
                        <p class="text-xl md:text-2xl font-bold text-gray-900 mt-0.5">{{ floor($product->stock / $product->pieces_per_crate) }}</p>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200 flex items-center gap-4">
                    <div class="w-12 h-12 bg-gray-50 rounded-xl flex items-center justify-center p-2.5 flex-shrink-0">
                        <img src="{{ asset('images/pieces-in-crate-icon.svg') }}" alt="" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Pieces in Crate</p>
                        <p class="text-xl md:text-2xl font-bold text-gray-900 mt-0.5">{{ $product->pieces_per_crate }}</p>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200 flex items-center gap-4">
                    <div class="w-12 h-12 bg-amber-200 rounded-xl flex items-center justify-center p-2.5 flex-shrink-0">
                        <img src="{{ asset('images/stock-icon.svg') }}" alt="" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Stock (Individual Items)</p>
                        <p class="text-xl md:text-2xl font-bold text-amber-600 mt-0.5">{{ $product->stock }}</p>
                    </div>
                </div>

                <!-- Logistics / Supplier Block -->
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200 flex items-center gap-4 sm:col-span-2 lg:col-span-3">
                    <div class="w-12 h-12 bg-gray-50 rounded-xl flex items-center justify-center p-2.5 flex-shrink-0">
                        <img src="{{ asset('images/supplier-icon.svg') }}" alt="" class="w-full h-full object-contain">
                    </div>
                    <div class="w-full">
                        <p class="text-sm font-medium text-gray-500">Supplier Details</p>
                        <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-4 mt-0.5">
                            <span class="text-base md:text-lg font-semibold text-gray-900">{{ $product->supplier }}</span>
                            <span class="hidden sm:inline text-gray-300">|</span>
                            <a href="tel:{{ $product->supplier_phone_no }}" class="text-sm md:text-base text-blue-600 hover:underline font-medium flex items-center gap-1">
                                📞 {{ $product->supplier_phone_no }}
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-layout>
