<x-layout>

    @if (session('warning'))
        <div class="absolute right-0 top-0">
            <x-alert :message="session('warning')" :show='true'/>
        </div>
    @endif

    @if($stockCount == 0)
        <x-empty-inventory />
    @else
    <div class="max-w-6xl mx-auto py-8 px-4">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Record Sale</h1>
            <p class="text-gray-600 mt-2">Search and add products to create a new sale</p>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <h3 class="text-red-800 font-semibold mb-2">Error</h3>
                <ul class="text-red-700 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <x-forms.form action="{{ route('sales.store') }}" method="POST" formClass="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- === IDEMPOTENT TOKEN ===  -->
            <input type="hidden" name="_idempotency_token" value="{{ Str::uuid() }}">

            <!-- Left: Product Search -->
            <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Add Products</h2>
                        
                        <!-- Search Bar -->
                        <div class="mb-6">
                            <label for="productSearch" class="block text-sm font-medium text-gray-700 mb-2">
                                Search Products
                            </label>
                            <div class="relative">
                                <input 
                                    type="text" 
                                    id="productSearch" 
                                    placeholder="Search by product name..." 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    autocomplete="off"
                                >
                                <div id="searchResults" class="absolute top-full left-0 right-0 mt-1 bg-white border border-gray-300 rounded-lg shadow-lg z-10 hidden max-h-64 overflow-y-auto">
                                    <!-- Results will be populated here -->
                                </div>
                            </div>
                        </div>

                        <!-- Cart Items -->
                        <div class="space-y-4">
                            <div id="cartItems" class="space-y-4">
                                <!-- Cart items will be added here dynamically -->
                            </div>
                            
                            <div id="emptyCart" class="text-center py-8 text-gray-500">
                                <p>No products added yet. Search and add products above.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow p-6 sticky top-20">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h2>
                        
                        <!-- Summary Table -->
                        <div id="summaryTable" class="mb-6">
                            <div class="text-center text-gray-500 py-4">
                                <p>Add products to see summary</p>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="mb-6">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                Notes (Optional)
                            </label>
                            <textarea 
                                id="notes" 
                                name="notes" 
                                rows="3" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Add any notes about this sale..."
                            ></textarea>
                        </div>

                        <!-- Totals -->
                        <div class="border-t pt-4 mb-6">
                            <div class="flex justify-between mb-3">
                                <span class="text-gray-600">Subtotal:</span>
                                <span id="subtotal" class="font-semibold">0.00</span>
                            </div>
                            <div class="flex justify-between mb-3 text-xl">
                                <span class="font-bold text-gray-900">Total:</span>
                                <span id="total" class="font-bold text-blue-600">0.00</span>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button 
                            type="submit" 
                            id="submitBtn" 
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition disabled:bg-gray-400 disabled:cursor-not-allowed flex align-middle justify-center gap-1"
                            disabled
                        >
                        <span id="saleText" class="">Record Sale</span>

                        <svg id="spinner" class="hidden w-4 h-4 animate-spin mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Hidden input to store cart data -->
            <input type="hidden" id="cartData" name="items" value="[]">
        </x-forms.form>
    </div>

    @endif

    <script>
        let cart = [];
        const searchInput = document.getElementById('productSearch');
        const searchResults = document.getElementById('searchResults');
        const cartItemsDiv = document.getElementById('cartItems');
        const emptyCartDiv = document.getElementById('emptyCart');
        const cartDataInput = document.getElementById('cartData');
        const summaryTable = document.getElementById('summaryTable');
        const subtotalSpan = document.getElementById('subtotal');
        const totalSpan = document.getElementById('total');
        const submitBtn = document.getElementById('submitBtn');
        const saleForm = document.querySelector('form');
        const saleText =  document.getElementById('saleText');
        const spinner = document.getElementById('spinner');

        // Search products
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();

            if (query.length < 1) {
                searchResults.classList.add('hidden');
                return;
            }

            searchTimeout = setTimeout(() => {
                fetch(`{{ route('sales.search-products') }}?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (!Array.isArray(data) || data.length === 0) {
                            searchResults.innerHTML = '<div class="p-4 text-gray-500">No products found</div>';
                        } else {
                            searchResults.innerHTML = data.map(product => `
                                <div class="p-3 hover:bg-blue-50 cursor-pointer border-b last:border-b-0 transition" 
                                     onclick="addToCart(${product.id}, '${product.name.replace(/'/g, "\\'")}', ${product.selling_price}, ${product.stock}, ${product.pieces_per_crate})">
                                    <div class="font-medium text-gray-900">${product.name}</div>
                                    <div class="text-sm text-gray-500">${product.supplier} | Stock: ${product.stock} | {!! $currency !!}${parseFloat(product.selling_price).toFixed(2)}</div>
                                </div>
                            `).join('');
                        }
                        searchResults.classList.remove('hidden');
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        searchResults.innerHTML = '<div class="p-4 text-red-500">Error loading products</div>';
                    });
            }, 300);
        });

        // Close search results when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('#productSearch') && !e.target.closest('#searchResults')) {
                searchResults.classList.add('hidden');
            }
        });

        // Add product to cart
        function addToCart(productId, productName, price, stock, pieces_per_crate) {
            if (cart.some(item => item.product_id === productId)) {
                alert('Product already in cart');
                return;
            }

            cart.push({
                product_id: productId,
                product_name: productName,
                price_per_unit: price,
                quantity_sold: 1,
                stock: stock,
                crate: pieces_per_crate
            });

            searchInput.value = '';
            searchResults.classList.add('hidden');
            renderCart();
        }

        // Remove product from cart
        function removeFromCart(productId) {
            cart = cart.filter(item => item.product_id !== productId);
            cartDataInput.value = JSON.stringify(cart);
            renderCart();
        }

        // Update quantity
        function updateQuantity(productId, quantity) {
            const item = cart.find(i => i.product_id === productId);
            
            if (!item) return;

            // If input is empty, just return without removing
            if (quantity === '' || quantity === null) {
                return;
            }

            const numQty = parseFloat(quantity);

            if (isNaN(numQty) || numQty <= 0) {
                removeFromCart(productId);
                return;
            }

            if (numQty > item.stock) {
                alert(`Cannot exceed stock of ${item.stock}`);
                document.getElementById(`qty-${productId}`).value = item.quantity_sold;
                return;
            }

            item.quantity_sold = numQty;
            cartDataInput.value = JSON.stringify(cart);
            renderCart();
        }

        // Update quantity in real-time (as user types)
        function updateQuantityLive(productId, quantity) {
            const item = cart.find(i => i.product_id === productId);
            
            if (!item) return;

            // If input is empty, just update display
            if (quantity === '' || quantity === null) {
                updateSummaryDisplay();
                return;
            }

            const numQty = parseFloat(quantity);

            if (isNaN(numQty) || numQty <= 0) {
                updateSummaryDisplay();
                return;
            }

            if (numQty > item.stock) {
                return;
            }

            item.quantity_sold = numQty;
            cartDataInput.value = JSON.stringify(cart);
            updateSummaryDisplay();
        }

        // Increment quantity
        function incrementQuantity(productId) {
            const item = cart.find(i => i.product_id === productId);
            if (!item) return;

            let newQuantity = item.quantity_sold + 1;

            if (newQuantity > item.stock) {
                alert(`Cannot exceed stock of ${item.stock}`);
                return;
            }

            item.quantity_sold = newQuantity;
            document.getElementById(`qty-${productId}`).value = newQuantity;
            cartDataInput.value = JSON.stringify(cart);
            updateSummaryDisplay();
        }

        // Decrement quantity
        function decrementQuantity(productId) {
            const item = cart.find(i => i.product_id === productId);
            if (!item) return;

            let newQuantity = item.quantity_sold - 1;

            if (newQuantity <= 0) {
                removeFromCart(productId);
                return;
            }

            item.quantity_sold = newQuantity;
            document.getElementById(`qty-${productId}`).value = newQuantity;
            cartDataInput.value = JSON.stringify(cart);
            updateSummaryDisplay();
        }

        // Update summary display without re-rendering cart items
        function updateSummaryDisplay() {
            // Update cart items totals
            const cartItems = document.querySelectorAll('[id^="qty-"]');
            cartItems.forEach(input => {
                const productId = input.id.replace('qty-', '');
                const item = cart.find(i => i.product_id == productId);
                if (item) {
                    const totalElement = input.closest('.flex').querySelector('.ml-auto');
                    if (totalElement) {
                        const total = (item.quantity_sold * item.price_per_unit).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                        totalElement.textContent = @json($currency) + total;
                    }
                }
            });

            // Update summary totals
            let subtotal = 0;
            cart.forEach(item => {
                subtotal += item.quantity_sold * item.price_per_unit;
            });
            /* 
                How do we bring the currency that is set out
            */
            const total = subtotal;
            subtotalSpan.textContent = '{!! $currency !!}' + parseFloat(subtotal).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            totalSpan.textContent = '{!! $currency !!}' + parseFloat(total).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});

            // Update summary table
            summaryTable.innerHTML = `
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Items:</span>
                        <span class="font-semibold">${cart.length}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Qty:</span>
                        <span class="font-semibold">${cart.reduce((sum, item) => sum + item.quantity_sold, 0).toFixed(2)}</span>
                    </div>
                </div>
            `;
        }

        // Add crate quantity
        function addCrate(productId, crateValue) {
            const item = cart.find(i => i.product_id === productId);
            if (!item) return;

            const qtyInput = document.getElementById(`qty-${productId}`);
            const inputValue = qtyInput.value.trim();
            
            // If input is empty, set to crate value; otherwise add crate value
            let newQuantity = inputValue === '' ? crateValue : item.quantity_sold + crateValue;

            if (newQuantity > item.stock) {
                alert(`Cannot exceed stock of ${item.stock}`);
                return;
            }

            item.quantity_sold = newQuantity;
            qtyInput.value = newQuantity;
            cartDataInput.value = JSON.stringify(cart);
            updateSummaryDisplay();
        }


        // Render cart
        function renderCart() {
            cartDataInput.value = JSON.stringify(cart);
            
            if (cart.length === 0) {
                cartItemsDiv.innerHTML = '';
                emptyCartDiv.style.display = 'block';
                summaryTable.innerHTML = '<div class="text-center text-gray-500 py-4"><p>Add products to see summary</p></div>';
                submitBtn.disabled = true;
                subtotalSpan.textContent = '{!! $currency !!}0.00';
                totalSpan.textContent = '{!! $currency !!}0.00';
                return;
            }

            emptyCartDiv.style.display = 'none';
            submitBtn.disabled = false;

            // Render cart items
            cartItemsDiv.innerHTML = cart.map(item => `
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h3 class="font-semibold text-gray-900">${item.product_name}</h3>
                            <p class="text-sm text-gray-500">{!! $currency !!}${parseFloat(item.price_per_unit).toFixed(2)} per unit</p>
                        </div>
                        <button 
                            type="button" 
                            onclick="removeFromCart(${item.product_id})"
                            class="text-red-500 hover:text-red-700 font-semibold"
                        >
                            Remove
                        </button>
                    </div>
                    <div class="flex items-center gap-2">
                        <label class="text-sm text-gray-700">Qty:</label>
                        <button type="button" class="px-2 py-1 bg-gray-200 hover:bg-gray-300 rounded" onclick="decrementQuantity(${item.product_id})">−</button>
                        <input 
                            type="text" 
                            id="qty-${item.product_id}"
                            value="${item.quantity_sold}" 
                            step="0.01"
                            min="0.01"
                            max="${item.stock}"
                            oninput="updateQuantityLive(${item.product_id}, this.value)"
                            onchange="updateQuantity(${item.product_id}, this.value)"
                            class="w-20 px-2 py-1 border border-gray-300 rounded text-center focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                        <button type="button" class="px-2 py-1 bg-gray-200 hover:bg-gray-300 rounded" onclick="incrementQuantity(${item.product_id})">+</button>
                        <span class="text-sm text-gray-600">/ ${item.stock}</span>
                        <button type="button" class="border text-sm p-0.5 rounded border-gray-300" onclick="addCrate(${item.product_id}, ${item.crate})">
                            Crate
                            <div class="text-xs">
                               x${item.crate}
                            </div>
                        </button>
                        <span class="ml-auto font-semibold">{!! $currency !!}${(item.quantity_sold * item.price_per_unit).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>
                    </div>
                </div>
            `).join('');

            // Update summary
            let subtotal = 0;
            cart.forEach(item => {
                subtotal += item.quantity_sold * item.price_per_unit;
            });

            const total = subtotal;
            subtotalSpan.textContent = '{!! $currency !!}' + parseFloat(subtotal).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            totalSpan.textContent = '{!! $currency !!}' + parseFloat(total).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    
            // Render summary table
            summaryTable.innerHTML = `
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Items:</span>
                        <span class="font-semibold">${cart.length}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Qty:</span>
                        <span class="font-semibold">${cart.reduce((sum, item) => sum + item.quantity_sold, 0).toFixed(2)}</span>
                    </div>
                </div>
            `;
        }

        // Form submission
        saleForm.addEventListener('submit', function(e) {
            if (cart.length === 0) {
                e.preventDefault();
                alert('Please add at least one product to the sale');
                return;
            }
            submitBtn.disabled = true;
            spinner.classList.remove('hidden');
            saleText.textContent = 'Recording sale... ';
            
            // Ensure cart data is synced to hidden input before submission
            cartDataInput.value = JSON.stringify(cart);
        });

        // Initialize
        renderCart();
    </script>
</x-layout>
