<x-layout>

    @if (session('success'))
        <div class="absolute right-0 top-0">
            <x-alert :message="session('success')" :show='true'/>
        </div>
    @endif

    @if ($stockCount == 0)
       {{-- For empty invetory --}}
        <x-empty-inventory />

    @elseif($countLowStock == 0)
        {{-- For healthy levels of inventory --}}
       <x-healthy-inventory />

    @else
        <div x-data="searchRestock()" class="p-4">
            <p class="mb-4 text-red-500 font-bold font-stretch-50%">{{ $countLowStock }} {{ Str::plural('product', $countLowStock) }} {{ $countLowStock == 1 ? 'is':'are' }} currently low or out of stock!</p>
            
            <input 
                type="text" 
                x-model="query" 
                @input="search()"
                placeholder="Search products by name or supplier..." 
                class="p-2 border border-gray-300 rounded w-full mb-4"
            >

            <div class="container mx-auto px-4">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier Phone</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <template x-if="filteredProducts.length > 0">
                                <template x-for="product in filteredProducts" :key="product.id">
                                    <tr class= "hover:bg-green-50 cursor-pointer bg-green-100 transition-colors" @click="window.location.href=`/products/${product.id}`">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" x-text="product.name"></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700" x-text="product.category.name"></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700" x-text="product.stock"></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700" x-text="product.supplier"></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700" x-text="product.supplier_phone_no"></td>
                                    </tr>
                                </template>
                            </template>
                            <template x-if="filteredProducts.length === 0 && query !== ''">
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">No products found matching your search.</td>
                                </tr>
                            </template>
                            <div x-show="query === ''">
                                @forelse($getLowStock as $product)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $product->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $product->category->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $product->stock }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $product->supplier }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $product->supplier_phone_no }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-500 font-semibold cursor-pointer" onclick="window.location.href='{{ route('restock.create', $product->id) }}'">Restock</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">No products need restocking.</td>
                                    </tr>
                                @endforelse
                            </div>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4" x-show="query === ''">
                    {{ $getLowStock->links() }}
                </div>
            </div>
        </div>
    @endif

    <script>
        function searchRestock() {
          return {
                query: '',
                filteredProducts: [],
                searchTimeout: null,
                
                search() {
                    // Clear previous timeout
                    clearTimeout(this.searchTimeout);

                    if (this.query.trim() === '') {
                        this.filteredProducts = '';
                        return;
                    }

                    // Set new timeout with 300ms debounce
                    this.searchTimeout = setTimeout(() => {
                        fetch(`/restock/search?q=${encodeURIComponent(this.query)}`)
                            .then(response => response.json())
                            .then(data => {
                                this.filteredProducts = data;
                            })
                            .catch(error => console.error('Search error:', error));
                    }, 300);
                }
            }
        }
    </script>

</x-layout>
