<x-layout>
    <div class="text-4xl ml-2">
        {{ $product->name }}
    </div>

    <!-- Product Photo -->
    <div class="ml-2 mt-4">
        <div class="w-28 h-28 border-2 border-gray-300 rounded-lg overflow-hidden bg-gray-50 shadow-md flex items-center justify-center">
            @if($product->photo)
                <img src="{{ asset('storage/' . $product->photo) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
            @else
                <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            @endif
        </div>
    </div>

<div class="flex flex-col mt-1 p-2 items-end">
    <div class="flex flex-wrap justify-center">
        <div class="w-1/3 border rounded border-gray-300 p-2  m-6 text-2xl"> 
           <img src="{{ asset('images/cost-icon.svg') }}" alt="">
           <span class="font-semibold">Cost:</span> {{ $currency }}{{ $product->cost_per_unit }}
        </div>

        <div class="w-1/3 m-6 border rounded border-gray-300 p-2 text-2xl"> 
            <img src="{{ asset('images/price-icon.svg') }}" alt="">
            <span class="font-semibold">Price:</span> {{ $currency }}{{ $product->selling_price }}
        </div>

        <div class="w-1/3 m-6 border rounded border-gray-300 p-2 text-2xl"> 
            <img src="{{ asset('images/profit-icon.svg') }}" alt="">
            <span class="font-semibold">Profit:</span> {{ $currency }}{{ $product->profit }}
        </div>

        <div class="w-1/3 m-6 border rounded border-gray-300 p-2 text-2xl">
            <img src="{{ asset('images/crates-available-icon.svg') }}" alt="">
            <span class="font-semibold">Crates available:</span> {{ floor($product->stock / $product->pieces_per_crate) }}
        </div>

        <div class="w-1/3 m-6 border rounded border-gray-300 p-2 text-2xl">
            <img src="{{ asset('images/pieces-in-crate-icon.svg') }}" alt="">
            <span class="font-semibold">Pieces in a crate:</span> {{ $product->pieces_per_crate }}
        </div>

        <div class="w-1/3 m-6 border rounded border-gray-300 p-2 text-2xl">
            <img src="{{ asset('images/stock-icon.svg') }}" alt="stock icon">
            <span class="font-semibold">Stock:</span> {{ $product->stock }}
        </div>

        <div class="w-1/3 m-6 border rounded border-gray-300 p-2 text-2xl">
            <img src="{{ asset('images/supplier-icon.svg') }}" alt="">
            <span class="font-semibold">Supplier:</span> {{ $product->supplier . ' | ' . $product->supplier_phone_no}}
        </div>

        <div class="w-1/3 m-6 border rounded border-gray-300 p-2 text-2xl">
            <img src="{{ asset('images/category-icon.svg') }}" alt="">
            <span class="font-semibold">Category:</span> {{ Str::lower($product->category->name) }}
        </div>
    </div>

    <div class="flex mt-3 text-center gap-2 text-sm p-4 border border-gray-300 w-fit rounded mr-auto ml-auto">

        <x-nav-links url="{{ route('products.destroy', $product->id) }}" method="DELETE" confirm="Are you sure you want to delete {{ $product->name }}?" icon="delete-icon.svg">
          Delete
        </x-nav-links>

        <x-nav-links url="{{ route('products.edit', $product->id) }}" icon="edit-icon.svg">
          Edit
        </x-nav-links>

        <x-nav-links url="{{ route('restock.create', $product->id) }}" icon="restock-icon.svg">
          Restock
        </x-nav-links>
    </div>
</div>

</x-layout>