@props(['avatar'])

@if ($avatar != NULL)
<div class="sw-13 position-relative mb-3">
    <img src="{{ $avatar }}" class="rounded-xl sh-13 sw-13" alt="thumb" />
</div>
@else
<div class="sw-13 sh-13 mb-3 d-inline-block bg-separator d-flex justify-content-center align-items-center rounded-xl">
    <i {{ $attributes->merge(['class' => 'icon icon-24']) }}></i>
</div>
@endif
