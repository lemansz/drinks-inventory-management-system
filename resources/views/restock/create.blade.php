<x-layout>
    <div class="max-w-md mx-auto mt-8">
        <!-- Product Header -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-t-lg p-6 shadow-lg">
            <p class="font-bold text-3xl">{{ $product->name }}</p>
        </div>

        <!-- Product Photo -->
        <div class="bg-white px-6 py-4 flex justify-center">
            <div class="w-32 h-32 border-2 border-gray-300 rounded-lg overflow-hidden bg-gray-50 flex items-center justify-center">
                @if($product->photo)
                    <img src="{{ asset('storage/' . $product->photo) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                @else
                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                @endif
            </div>
        </div>
        
        <!-- Stock Info -->
        <div class="bg-red-50 border-l-4 border-red-500 p-6">
            <p class="font-bold text-lg text-red-700">⚠ Low Stock Alert</p>
            <p class="text-red-600 mt-2 text-base">Current Stock: <span class="font-semibold">{{ $product->stock }} pieces</span> left</p>
        </div>
        
        <!-- Supplier Info -->
        <div class="bg-gray-50 border-b-4 border-gray-200 p-6 rounded-b-lg mb-6 shadow-md">
            <p class="text-gray-600 text-sm font-semibold mb-2">SUPPLIER CONTACT</p>
            <p class="text-gray-800 font-semibold text-lg">{{ $product->supplier }}</p>
            <p class="text-gray-600 mt-1">📞 {{ $product->supplier_phone_no }}</p>
        </div>

        <!-- Restock Form -->
        <div class="bg-white rounded-lg shadow-lg p-8 border-t-4 border-blue-500">
            <h2 class="text-xl font-bold text-gray-800 mb-6">Restock Product</h2>
            
            <x-forms.form action="{{ route('restock.store', $product->id) }}">
                <div class="mb-6">
                    <x-forms.input type="number" name="crates" label="Crates Available" inputmode="numeric" placeholder="How many crates?" class="w-full"/>
                </div>
                <button type="submit" id="submitBtn" class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 disabled:from-gray-400 disabled:to-gray-400 disabled:cursor-not-allowed text-white font-bold py-3 px-4 rounded-lg transition duration-200 shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                    <span id="restockText">✓ Restock Now</span>
                    <svg id="spinner" class="hidden w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </x-forms.form>
        </div>
    </div>

    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const spinner = document.getElementById('spinner');
        const restockText = document.getElementById('restockText');
        const submitBtn = document.getElementById('submitBtn');

        if (form) {
          form.addEventListener('submit', function(e) {
                submitBtn.disabled = true;
                spinner.classList.remove('hidden');
                restockText.textContent = 'Restocking...';
            });
        }
      });
    </script>
</x-layout>
