<div x-data="{ show: {{ $show ? 'true' : 'false' }} }"
    x-init="setTimeout(() => show = false, 4000)"
    x-show="show"
    x-transition:enter="transition ease-out duration-500"
    x-transition:enter-start="opacity-0 -translate-y-4"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-400"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 -translate-y-4"
    class="fixed top-6 left-1/2 -translate-x-1/2 w-[calc(100%-2rem)] max-w-md z-50 pointer-events-none transform">
    
    <!-- The Pretty Restyled Card Block -->
    <div class="p-4 bg-emerald-100 border-2 border-emerald-300 rounded-2xl shadow-xl flex items-center gap-3.5 pointer-events-auto backdrop-blur-sm">
        <!-- Solid icon badge wrapper -->
        <div class="w-6 h-6 bg-emerald-600 rounded-full flex items-center justify-center flex-shrink-0 shadow-sm">
            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        
        <!-- Text Ink -->
        <p class="text-sm md:text-base font-bold tracking-wide text-emerald-900 pr-2">
            {{ $message }}
        </p>
    </div>
</div>

