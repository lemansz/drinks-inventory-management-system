<x-layout>
    @if (session('warning'))
        <div class="absolute right-0 top-0">
            <x-alert :message="session('warning')" :show='true'/>
        </div>
    @endif
    <!-- Container expands beautifully on larger viewports while remaining centered -->
    <div class="max-w-7xl mx-auto mt-4 md:mt-8 px-4 sm:px-6 lg:px-8">
        
        <!-- Two-column grid layout for desktop view, single column for mobile -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- LEFT COLUMN: Product Overview & Detail Profile (Spans 2 columns on large screens) -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Product Header & Photo Card combined for cleaner layout continuity -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-100">
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6 shadow-sm">
                        <h1 class="font-bold text-2xl lg:text-3xl tracking-tight">{{ $product->name }}</h1>
                    </div>

                    <!-- Product Photo Section -->
                    <div class="p-6 flex flex-col sm:flex-row items-center gap-6 justify-center sm:justify-start">
                        <div class="w-32 h-32 border-2 border-gray-200 rounded-lg overflow-hidden bg-gray-50 flex items-center justify-center shrink-0 shadow-inner">
                            @if($product->photo)
                                <img src="{{ asset('storage/' . $product->photo) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            @else
                                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            @endif
                        </div>
                        
                        <!-- Mini Meta Description next to image on larger viewports -->
                        <div class="text-center sm:text-left">
                            <p class="text-xs font-bold text-gray-400 tracking-wider uppercase">Active Inventory Item</p>
                            <p class="text-sm text-gray-500 mt-1">Verify existing inventory metrics below before executing system adjustments.</p>
                        </div>
                    </div>
                </div>
                
                <!-- Info Badges Container: Dynamic stack on mobile, 2-column grid on tablets/desktop -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Stock Info -->
                    <div class="bg-red-50 border-l-4 border-red-500 p-5 rounded-r-lg shadow-sm flex flex-col justify-center">
                        <p class="font-bold text-lg text-red-700 flex items-center gap-2">
                            <span>⚠</span> Low Stock Alert
                        </p>
                        <p class="text-red-600 mt-1 text-base">
                            Current Stock: <span class="font-bold text-xl">{{ $product->stock }}</span> pieces left
                        </p>
                    </div>
                    
                    <!-- Supplier Info -->
                    <div class="bg-gray-50 border-b-4 border-gray-300 p-5 rounded-t-lg rounded-br-lg shadow-sm">
                        <p class="text-gray-500 text-xs font-bold tracking-wider mb-1">SUPPLIER CONTACT</p>
                        <p class="text-gray-800 font-semibold text-base truncate">{{ $product->supplier }}</p>
                        <p class="text-gray-600 mt-1 text-sm flex items-center gap-1">
                            <span>📞</span> {{ $product->supplier_phone_no }}
                        </p>
                    </div>
                </div>

            </div>

            <!-- RIGHT COLUMN: Restock Action Form Container (Spans 1 column) -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-lg p-6 lg:p-8 border-t-4 border-blue-500 h-full flex flex-col justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800 mb-2">Restock Product</h2>
                        <p class="text-xs text-gray-500 mb-6">Input newly acquired quantities to replenish warehouse totals.</p>
                        
                        <x-forms.form action="{{ route('restock.store', $product->id) }}">
                            <input type="hidden" name="_idempotency_token" value="{{ Str::uuid() }}">
                            <div class="mb-6">
                                <x-forms.input type="number" name="crates" label="Crates Available" inputmode="numeric" placeholder="How many crates?" class="w-full"/>
                            </div>
                            
                            <button type="submit" id="submitBtn" class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 disabled:from-gray-400 disabled:to-gray-400 disabled:cursor-not-allowed text-white font-bold py-3.5 px-4 rounded-lg transition duration-200 shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                                <span id="checkIcon">✓</span>
                                <span id="restockText">Restock Now</span>
                                <svg id="spinner" class="hidden w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </button>
                        </x-forms.form>
                    </div>
                </div>
            </div>

        </div>
    </div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const submitBtn = document.getElementById('submitBtn');
    const spinner = document.getElementById('spinner');
    const checkIcon = document.getElementById('checkIcon');
    const restockText = document.getElementById('restockText');

    if (submitBtn) {
      submitBtn.addEventListener('click', function(e) {
        e.preventDefault();

        const actualForm = submitBtn.closest('form');
        
        if (actualForm) {
          submitBtn.disabled = true;
          if (checkIcon) checkIcon.classList.add('hidden');
          spinner.classList.remove('hidden');
          restockText.textContent = 'Restocking...';
          actualForm.submit();
        }
      });
    }
  });
</script>

</x-layout>
