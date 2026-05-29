<div>
    <label class="text-{{ $labelClass ?? '2xl' }}" for="{{ $name }}">{{ $label }}</label>
    <input type="{{ $type }}" id="{{ $name }}" value="{{ old($name, $attributes->get('value')) }}" {{ $attributes->merge(['class' => 'rounded border border-gray-400 p-2']) }}>
    @error($name)
        <div class="text-red-500">{{ $message }}</div>
    @enderror
</div>