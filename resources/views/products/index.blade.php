<x-layout>

    @if ($stockCount == 0)
        <x-empty-inventory />
    @else


    @if (session('success'))
        <div class="absolute right-0 top-0">
            <x-alert :message="session('success')" :show='true'/>
        </div>
    @endif

    <div x-data="searchProducts()" class="p-4">
        <input 
            type="text" 
            x-model="query" 
            @input="search()"
            placeholder="Search products by name or category..." 
            class="p-2 border border-gray-300 rounded w-full mb-4"
        >

        <div class="container mx-auto px-4">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">   
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'cost_per_unit', 'direction' => ($sort === 'cost_per_unit') ? ($direction === 'asc' ? 'desc' : 'asc') : 'asc', 'page' => 1 ]) }}" class="inline-flex items-center gap-1.5">
                                    Cost
                                    @if($sort === 'cost_per_unit' && $sort !== null)
                                        @if($direction === 'asc')
                                            <img src="{{ asset('images/ascending-filter-icon.svg')}}" alt="Ascending" class="w-4 h-4 m-0">
                                        @else 
                                            <img src="{{ asset('images/descending-filter-icon.svg')}}" alt="Descending" class="w-4 h-4 m-0">
                                        @endif
                                    @else
                                        <img src="{{ asset('images/up-down-arrows-icon.svg')}}" alt="Up Down Arrow" class="w-4 h-4 m-0">
                                    @endif
                                </a>
                            </th>

                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">   
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'selling_price', 'direction' => ($sort === 'selling_price') ? ($direction === 'asc' ? 'desc' : 'asc') : 'asc', 'page' => 1 ]) }}" class="inline-flex items-center gap-1.5">
                                    Price
                                    @if($sort === 'selling_price' && $sort !== null)
                                        @if($direction === 'asc')
                                            <img src="{{ asset('images/ascending-filter-icon.svg')}}" alt="Ascending" class="w-4 h-4 m-0">
                                        @else 
                                            <img src="{{ asset('images/descending-filter-icon.svg')}}" alt="Descending" class="w-4 h-4 m-0">
                                        @endif
                                    @else
                                        <img src="{{ asset('images/up-down-arrows-icon.svg')}}" alt="Up Down Arrow" class="w-4 h-4 m-0">
                                    @endif
                                </a>
                            </th>

                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">   
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'profit', 'direction' => ($sort === 'profit') ? ($direction === 'asc' ? 'desc' : 'asc') : 'asc', 'page' => 1 ]) }}" class="inline-flex items-center gap-1.5">
                                    Profit
                                    @if($sort === 'profit' && $sort !== null)
                                        @if($direction === 'asc')
                                            <img src="{{ asset('images/ascending-filter-icon.svg')}}" alt="Ascending" class="w-4 h-4 m-0">
                                        @else 
                                            <img src="{{ asset('images/descending-filter-icon.svg')}}" alt="Ascending" class="w-4 h-4 m-0">
                                        @endif
                                    @else
                                        <img src="{{ asset('images/up-down-arrows-icon.svg')}}" alt="Up Down Arrow" class="w-4 h-4 m-0">
                                    @endif
                                </a>
                            </th>

                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">   
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'stock', 'direction' => ($sort === 'stock') ? ($direction === 'asc' ? 'desc' : 'asc') : 'asc', 'page' => 1 ]) }}" class="inline-flex items-center gap-1.5">
                                    Stock
                                    @if($sort === 'stock' && $sort !== null)
                                        @if($direction === 'asc')
                                            <img src="{{ asset('images/ascending-filter-icon.svg')}}" alt="Ascending" class="w-4 h-4 m-0">
                                        @else 
                                            <img src="{{ asset('images/descending-filter-icon.svg')}}" alt="Ascending" class="w-4 h-4 m-0">
                                        @endif
                                    @else
                                        <img src="{{ asset('images/up-down-arrows-icon.svg')}}" alt="Up Down Arrow" class="w-4 h-4 m-0">
                                    @endif
                                </a>
                            </th>

                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">   
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'crates_remaining', 'direction' => ($sort === 'crates_remaining') ? ($direction === 'asc' ? 'desc' : 'asc') : 'asc', 'page' => 1 ]) }}" class="inline-flex items-center gap-1.5">
                                    Crates Remaining
                                    @if($sort === 'crates_remaining' && $sort !== null)
                                        @if($direction === 'asc')
                                            <img src="{{ asset('images/ascending-filter-icon.svg')}}" alt="Ascending" class="w-4 h-4 m-0">
                                        @else 
                                            <img src="{{ asset('images/descending-filter-icon.svg')}}" alt="Descending" class="w-4 h-4 m-0">
                                        @endif
                                    @else
                                        <img src="{{ asset('images/up-down-arrows-icon.svg')}}" alt="Up Down Arrow" class="w-4 h-4 m-0">
                                    @endif
                                </a>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <template x-if="filteredProducts.length > 0">
                            <template x-for="product in filteredProducts" :key="product.id">
                                <tr class="hover:bg-green-50 cursor-pointer  bg-green-100 transition-colors" @click="window.location.href=`/products/${product.id}`">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" x-text="product.name"></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700" x-text="product.category.name"></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 text-right" x-text="parseFloat(product.cost_per_unit).toFixed(2)"></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 text-right" x-text="parseFloat(product.selling_price).toFixed(2)"></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 text-right" x-text="parseFloat(product.profit).toFixed(2)"></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 text-right">
                                        <div class="flex items-center justify-end gap-1">
                                            <span x-text="product.stock"></span>
                                            <svg x-show="product.stock < {{ $stockThreshold }}" class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 text-right" x-text="Math.floor(product.stock / product.pieces_per_crate)"></td>
                                </tr>
                            </template>
                        </template>
                        <template x-if="filteredProducts.length === 0 && query !== ''">
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">No products found matching your search.</td>
                            </tr>
                        </template>
                        <div x-show="query === ''">
                            @forelse($products as $product)
                                <tr class="hover:bg-gray-50 cursor-pointer transition-colors" onclick="window.location.href='{{ route('products.show', $product->id) }}'">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $product->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $product->category->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 text-right">{{ $currency }}{{ number_format($product->cost_per_unit, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 text-right">{{ $currency }}{{ number_format($product->selling_price, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 text-right">{{ $currency }}{{ number_format($product->profit, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 text-right">
                                        <div class="flex items-center justify-end gap-1">
                                            <span>{{ $product->stock }}</span>
                                            @if($product->stock < $stockThreshold)
                                                <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 text-right">{{ floor($product->stock / $product->pieces_per_crate ?: 1) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">No products found.</td>
                                </tr>
                            @endforelse
                        </div>
                    </tbody>
                </table>
            </div>

            <div class="mt-4" x-show="query === ''">
                {{ $products->links() }}
            </div>
        </div>
    </div>

    @endif

    <script>
        function searchProducts() {
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
                        fetch(`/products/search?q=${encodeURIComponent(this.query)}`)
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