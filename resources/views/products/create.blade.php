<x-layout>

    @if (session('warning'))
        <div class="absolute right-0 top-0">
            <x-alert :message="session('warning')" :show='true'/>
        </div>
    @endif

    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl mx-auto">
            <!-- Header Section -->
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-12 h-12 bg-emerald-900 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </div>
                    <h1 class="text-4xl font-bold text-gray-900">Add Product</h1>
                </div>
                <p class="text-gray-600 ml-15">Add a new product to your inventory</p>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-emerald-900 to-emerald-800 px-6 py-4">
                    <p class="text-white font-semibold">Product Details</p>
                </div>

                <div class="p-8">
                    <x-forms.form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data" formClass="space-y-6" id="productForm">

                        <input type="hidden" name="_idempotency_token" value="{{ Str::uuid() }}">

                        <!-- Product Information Section -->
                        <div class="border-b border-gray-200 pb-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Product Information</h2>
                            <div class="space-y-4">
                                <x-forms.input type="text" name="name" label="Product Name" labelClass="sm" placeholder="Pepsi 50cl"/>
                                
                                <div>
                                    <label class="text-sm font-semibold text-gray-900" for="category">Category</label>
                                    <select name="category_id" id="category" class="w-full mt-2 rounded border border-gray-400 p-2 focus:outline-none focus:ring-2 focus:ring-emerald-900">
                                        <option value="">Select a category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Pricing Section -->
                        <div class="border-b border-gray-200 pb-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Pricing</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <x-forms.input type="text" name="cost_per_unit" label="Cost Per Unit" inputmode="numeric" placeholder="Cost per bottle/sachet"/>
                                <x-forms.input type="text" name="selling_price" label="Selling Price" inputmode="numeric" placeholder="Selling price"/>
                            </div>
                        </div>

                        <!-- Inventory Section -->
                        <div class="border-b border-gray-200 pb-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Inventory</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <x-forms.input type="number" name="crates_available" label="Crates Available" inputmode="numeric" placeholder="How many crates?"/>
                                <x-forms.input type="number" name="pieces_per_crate" label="Pieces Per Crate" inputmode="numeric" placeholder="Pieces in crate"/>
                            </div>
                        </div>

                        <!-- Supplier Information Section -->
                        <div class="border-b border-gray-200 pb-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Supplier Information</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <x-forms.input type="text" name="supplier" label="Supplier Name" placeholder="Name"/>
                                <x-forms.input type="tel" name="supplier_phone_no" label="Phone Number" placeholder="Phone number"/>
                            </div>
                        </div>

                        <!-- Product Image Section -->
                        <div class="pb-6">
                            <label class="block text-sm font-semibold text-gray-900 mb-3">Product Image</label>
                            <div class="flex items-center justify-center w-full">
                                <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        <p class="text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                        <p class="text-xs text-gray-400">PNG, JPG, GIF up to 10MB</p>
                                    </div>
                                    <input type="file" name="photo" class="hidden"/>
                                </label>
                            </div>
                        </div>

                      
                <div class="flex gap-2 md:gap-4 pt-6">
                        <button type="submit" id="add-product-btn" class="flex-1 bg-emerald-900 hover:bg-emerald-800 disabled:bg-gray-400 disabled:cursor-not-allowed text-white font-semibold py-2.5 px-3 md:py-3 md:px-6 text-xs md:text-base rounded-lg transition duration-200 flex items-center justify-center gap-1.5 md:gap-2">
                            <!-- Icons scale down on mobile (w-4 h-4 md:w-5 md:h-5) -->
                            <svg id="plus-icon" class="w-4 h-4 md:w-5 md:h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            
                            <span id="add-product-txt" class="truncate">Add Product</span>
                            
                            <svg id="spinner" class="hidden w-4 h-4 md:w-5 md:h-5 animate-spin flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
                        
                        <!-- Cancel Button -->
                        <a href="{{ route('products.index') }}" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2.5 px-3 md:py-3 md:px-6 text-xs md:text-base rounded-lg transition duration-200 flex items-center justify-center gap-1.5 md:gap-2">
                            <svg class="w-4 h-4 md:w-5 md:h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            <span class="truncate">Cancel</span>
                        </a>

                    </div>

                    </x-forms.form>
                </div>
            </div>
        </div>
    </div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const addProductBtn = document.getElementById('add-product-btn');
    const spinner = document.getElementById('spinner');
    const plusIcon = document.getElementById('plus-icon');
    const addProductTxt = document.getElementById('add-product-txt');

    if (addProductBtn) {
      addProductBtn.addEventListener('click', function(e) {
        
        const actualForm = addProductBtn.closest('form');
        
        if (actualForm) {
          
          addProductBtn.disabled = true;
          plusIcon.classList.add('hidden');
          spinner.classList.remove('hidden');
          addProductTxt.textContent = 'Adding...';

          actualForm.submit();
        }
      });
    }
  });
</script>
</x-layout>