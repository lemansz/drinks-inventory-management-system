<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>mcheck</title>
    @livewireStyles
</head>
<body>
@vite(['resources/css/app.css', 'resources/js/app.js'])
<div class="flex gap-4">
    <!-- Sidebar Navigation -->
    <nav class="bg-emerald-900 w-48 min-h-screen flex flex-col fixed left-0 top-0 p-2">
        <div class="mb-6 ml-0">
            <h1 class="text-white text-2xl font-bold pl-2">mcheck</h1>
        </div>
        
        <div class="flex-1 space-y-2">
            <div class="hover:bg-emerald-800/100 rounded">
                <x-nav-links url="/dashboard" class="text-white px-2 py-3 rounded flex items-center" icon="dashboard-icon.svg">
                    <span class="text-sm tracking-wider">Reports</span>
                </x-nav-links>
            </div>

            <div class="hover:bg-emerald-800/100 rounded">
                <x-nav-links url="/sales" class="text-white px-2 py-3 rounded flex items-center" icon="ledger-icon.svg">
                    <span class="text-sm tracking-wider">Sales</span>
                </x-nav-links>
            </div>

            <div class="hover:bg-emerald-800/100 rounded">
                <x-nav-links url="/restock" class="text-white px-2 py-3 rounded flex items-center" icon="{{ $StockService->hasLowStock() ? 'low-stock-icon.svg':'stock-icon.svg'}}">
                    <span class="text-sm tracking-wider">Restock</span>
                </x-nav-links>
            </div>

            <div class="hover:bg-emerald-800/100 rounded">
                <x-nav-links url="/products/create" class="text-white px-2 py-3 rounded flex items-center" icon="{{ $StockService->countStock() == 0 ? 'no-product-alert.svg':'product-add-icon.svg' }}">
                    <span class="text-sm tracking-wider">Add product</span>
                </x-nav-links>
            </div>

            <div class="hover:bg-emerald-800/100 rounded">
                <x-nav-links url="/inventory" class="text-white px-2 py-3 rounded flex items-center" icon="warehouse-icon.svg">
                    <span class="text-sm tracking-wider">Inventory</span>
                </x-nav-links>
            </div>

            <div class="hover:bg-emerald-800/100 rounded">
                <x-nav-links url="/settings" class="text-white px-2 py-3 rounded flex items-center" icon="setting-line-icon.svg">
                    <span class="text-sm tracking-wider">Settings</span>
                </x-nav-links>
            </div>
        </div>

        <div class="hover:bg-emerald-800/100 rounded mt-auto">
            <x-nav-links url="/logout" class="text-white py-3 rounded flex items-center px-2" icon="log-out-icon.svg">
                <span class="text-sm tracking-wider">Log Out</span>
            </x-nav-links>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="ml-56 w-full">
        {{ $slot }}
        @livewireScripts
    </main>
</div>
</body>
</html> 