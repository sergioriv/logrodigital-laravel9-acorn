@props(['link' => null])
<button
    @if ($link) onclick="location.href='{{ $link }}'" @endif
    {{ $attributes->merge(['class' => 'dropdown-item']) }}>
    {{ $slot }}
</button>
