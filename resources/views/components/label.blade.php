@props(['value'])

<label {{ $attributes->merge(['class' => 'form-label logro-label']) }}>
    {{ $value ?? $slot }}
    @if ($attributes->get('required'))
    <x-required />
    @endif
</label>
