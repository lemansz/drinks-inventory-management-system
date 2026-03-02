<x-layout>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl mx-auto">
            <!-- Header Section -->
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-12 h-12 bg-emerald-900 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                    <h1 class="text-4xl font-bold text-gray-900">Edit Product</h1>
                </div>
                <p class="text-gray-600 ml-15">Update the product details below</p>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">

                <div class="bg-gradient-to-r from-emerald-900 to-emerald-800 px-6 py-4">
                    <p class="text-white font-semibold">{{ $product->name }}</p>
                </div>

                <div class="p-8">
                    <!-- Product Photo Display -->
                    <div class="mb-8">
                        <label class="block text-sm font-semibold text-gray-900 mb-3">Product Photo</label>
                        <div class="w-32 h-32 border-2 border-gray-300 rounded-lg overflow-hidden bg-gray-50 shadow-md flex items-center justify-center">
                            @if($product->photo)
                                <img src="{{ asset('storage/' . $product->photo) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            @else
                                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            @endif
                        </div>
                    </div>

                    <x-forms.form method="POST" action="{{ route('products.update', $product->id) }}" formClass="space-y-6">
                        <!-- Product Information Section -->
                        <div class="border-b border-gray-200 pb-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Product Information</h2>
                            <div class="space-y-4">
                                <x-forms.input type="text" name="name" label="Product Name" value="{{ $product->name }}" labelClass="sm"/>
                            </div>
                        </div>

                        <!-- Pricing Section -->
                        <div class="border-b border-gray-200 pb-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Pricing</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <x-forms.input type="text" name="cost_per_unit" label="Cost Per Unit" value="{{ $product->cost_per_unit }}"/>
                                <x-forms.input type="text" name="selling_price" label="Selling Price" value="{{ $product->selling_price }}"/>
                            </div>
                        </div>

                        <!-- Supplier Information Section -->
                        <div class="border-b border-gray-200 pb-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Supplier Information</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <x-forms.input type="text" name="supplier" label="Supplier Name" value="{{ $product->supplier }}"/>
                                <x-forms.input type="text" name="supplier_phone_no" label="Phone Number" value="{{ $product->supplier_phone_no }}"/>
                            </div>
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-gray-900" for="category">Category</label>
                            <select name="category_id" id="category" class="w-full mt-2 rounded border border-gray-400 p-2 focus:outline-none focus:ring-2 focus:ring-emerald-900">
                                <option value="{{ $product->category_id }}">{{ $product->category->name }}</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
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

                        <!-- Action Buttons -->
                        <div class="flex gap-4 pt-6">
                            <button type="submit" class="flex-1 bg-emerald-900 hover:bg-emerald-800 text-white font-semibold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Save Changes
                            </button>
                            <a href="{{ route('products.show', $product->id) }}" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Cancel
                            </a>
                        </div>
                    </x-forms.form>
                </div>
            </div>
        </div>
    </div>
</x-layout>