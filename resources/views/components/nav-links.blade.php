@props([
    'url',
    'active' => false,
    'icon' => null,
    'method' => 'GET',
    'confirm' => false,
])

@if ($method === 'DELETE')
    <form action="{{ $url }}" method="POST" class="inline" @if($confirm) onsubmit="return confirm('{{ $confirm }}')" @endif>
        @csrf
        @method('DELETE')
        <button type="submit" {{ $attributes->class(['font-bold' => $active]) }} style="background: none; border: none; cursor: pointer;">
            @if ($getIcon())
            <div class="flex justify-center px-4">
                {!! $getIcon() !!}
            </div>
            @endif
            <p class="">
                {{ $slot }}
            </p>
        </button>
    </form>
@else
    <a href="{{ $url }}" {{ $attributes->class(['font-bold' => $active])}}>
        @if ($getIcon())
        <div class="flex justify-center px-4">
            {!! $getIcon() !!}
        </div>
        @endif
        <p class="">
            {{ $slot }}
        </p>
    </a>
@endif