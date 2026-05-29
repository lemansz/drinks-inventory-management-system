<div class="flex flex-col min-h-screen">

    <div class="flex m-2 mb-0">
        <a href="{{ route('restock-history.index') }}" class="ml-auto" title="Restock History">
            <img src="{{ asset('images/history-icon.svg') }}" alt="">
        </a>
    </div>

    <div class="flex flex-1 flex-col items-center justify-center">
        <!-- Happy Face with Thumbs Up SVG -->
        <img src="{{ asset('images/healthy-stock-icon.svg') }}" alt="Good inventory level" class="w-32 h-32 mb-6">
        <div class="text-center text-3xl font-bold text-green-600 mb-2">
            Perfect! ✓
        </div>
        <p class="text-center text-2xl font-semibold text-gray-700">
            No product needs restocking
        </p>
        <p class="text-gray-500 mt-3">All your inventory levels are healthy</p>
    </div>
    
</div>
