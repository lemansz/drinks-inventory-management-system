<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>mcheck</title>
    @livewireStyles
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 antialiased">
@vite(['resources/css/app.css', 'resources/js/app.js'])

<!-- Alpine.js State Container for Mobile Menu -->
<div x-data="{ mobileMenuOpen: false }" class="min-h-screen flex flex-col md:flex-row">
    
    <!-- Mobile Top Navigation Bar (Visible ONLY on mobile/tablet) -->
    <header class="bg-emerald-900 text-white p-4 flex justify-between items-center md:hidden sticky top-0 z-50 shadow-md">
        <h1 class="text-xl font-bold">mcheck</h1>
        <button 
            @click="mobileMenuOpen = !mobileMenuOpen" 
            class="p-2 rounded focus:outline-none focus:bg-emerald-800"
            aria-label="Toggle Menu"
        >
            <!-- Hamburger Icon -->
            <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
            <!-- Close Icon -->
            <svg x-show="mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </header>

    <!-- Sidebar Navigation (Responsive & Collapsible) -->
    <nav 
    x-cloak
    :class="mobileMenuOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
    class="-translate-x-full bg-emerald-900 w-64 md:w-48 fixed md:sticky top-0 left-0 h-screen flex flex-col p-4 z-40 transition-transform duration-300 ease-in-out shadow-xl md:shadow-none mt-[56px] md:mt-0"
    >

        <!-- Desktop Logo Heading (Hidden on mobile) -->
        <div class="hidden md:block mb-6">
            <h1 class="text-white text-2xl font-bold pl-2">mcheck</h1>
        </div>
        
        <!-- Navigation Links Container (Added padding-top for extra mobile breathing room) -->
        <div class="flex-1 space-y-1 overflow-y-auto pt-4 md:pt-0">
            <div class="hover:bg-emerald-800 rounded">
                <x-nav-links url="/dashboard" class="text-white px-2 py-3 rounded flex items-center" icon="dashboard-icon.svg">
                    <span class="text-sm tracking-wider">Reports</span>
                </x-nav-links>
            </div>

            <div class="hover:bg-emerald-800 rounded">
                <x-nav-links url="/sales" class="text-white px-2 py-3 rounded flex items-center" icon="ledger-icon.svg">
                    <span class="text-sm tracking-wider">Sales</span>
                </x-nav-links>
            </div>

            <div class="hover:bg-emerald-800 rounded">
                <x-nav-links url="/restocks" class="text-white px-2 py-3 rounded flex items-center" icon="{{ $StockService->hasLowStock() ? 'low-stock-icon.svg':'stock-icon.svg'}}">
                    <span class="text-sm tracking-wider">Restock</span>
                </x-nav-links>
            </div>

            <div class="hover:bg-emerald-800 rounded">
                <x-nav-links url="/products/create" class="text-white px-2 py-3 rounded flex items-center" icon="{{ $StockService->countStock() == 0 ? 'no-product-alert.svg':'product-add-icon.svg' }}">
                    <span class="text-sm tracking-wider">Add product</span>
                </x-nav-links>
            </div>

            <div class="hover:bg-emerald-800 rounded">
                <x-nav-links url="/products" class="text-white px-2 py-3 rounded flex items-center" icon="warehouse-icon.svg">
                    <span class="text-sm tracking-wider">Inventory</span>
                </x-nav-links>
            </div>

            <div class="hover:bg-emerald-800 rounded">
                <x-nav-links url="/settings" class="text-white px-2 py-3 rounded flex items-center" icon="setting-line-icon.svg">
                    <span class="text-sm tracking-wider">Settings</span>
                </x-nav-links>
            </div>
        </div>

        <!-- Sticky Bottom Log Out -->
       <form method="POST" action="{{ route('logout') }}" class="hover:bg-emerald-800 rounded mt-auto pt-4 pb-16 md:pb-0">
            @csrf
            <button type="submit" class="w-full text-left text-white py-3 rounded flex items-center px-2">
                <!-- Keep your custom icon asset here -->
                <img src="{{ asset('images/log-out-icon.svg') }}" class="w-5 h-5 mr-2" alt="Logout">
                <span class="text-sm tracking-wider">Log Out</span>
            </button>
        </form>
    </nav>

    <!-- Mobile Overlay Backing (Closes menu when clicking outside sidebar) -->
    <div 
        x-show="mobileMenuOpen" 
        @click="mobileMenuOpen = false" 
        class="fixed inset-0 bg-black/40 z-30 md:hidden"
        style="display: none;"
    ></div>

    <!-- Main Content Area -->
    <main class="flex-1 w-full p-4 md:p-6 min-h-[calc(100vh-64px)] md:min-h-screen overflow-x-hidden">
        {{ $slot }}
        @livewireScripts
    </main>
</div>
</body>
</html>
