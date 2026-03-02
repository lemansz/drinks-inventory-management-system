<div class="flex flex-col items-center justify-center min-h-screen">
    <img src="{{ asset('images/empty-box-icon.svg') }}" alt="Empty inventory" class="w-32 h-32 mb-6">
    
    <div class="text-center text-2xl font-bold text-gray-700">
        You have no product in the inventory
    </div>
    <p class="text-gray-500 mt-2 mb-6">Get started by adding your first product</p>
    <a href="{{ route('products.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-8 rounded-lg transition duration-200">
        Add First Product
    </a>
</div>