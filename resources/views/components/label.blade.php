@props(['value'])

<label {{ $attributes->merge(['class' => 'form-label logro-label']) }}>
    {{ $value ?? $slot }}
</label>
