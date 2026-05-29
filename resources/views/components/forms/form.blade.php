<form method="{{ $method ?? 'POST' }}" enctype="{{ $enctype ?? 'application/x-www-form-urlencoded'}}" action="{{ $action ?? '' }}" class="{{ $formClass ?? 'max-w-2xl mx-auto space-y-6' }}">
    @if(strtoupper($method ?? 'POST') === 'POST')
        @csrf
    @endif
    {{ $slot }}
</form>
