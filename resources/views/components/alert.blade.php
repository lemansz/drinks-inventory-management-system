<div x-data="{ show: {{ $show }} }"
    x-init="setTimeout(() => { show = false }, 3000)"
    x-show="show"
    x-transition.duration.500ms
    class="bg-green-500 text-2xl/relaxed text-white p-2 rounded-md w-fit mt-1 ml-2">
    {{ $message }}
</div>
