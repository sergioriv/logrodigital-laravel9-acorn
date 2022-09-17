@props(['avatar','inclusive' => null])

@if ($avatar !== NULL)
    @php
        $avatar = config('app.url') .'/'. $avatar;
    @endphp
@else
    @php
        $avatar = config('app.url') .'/img/other/profile-11.webp';
    @endphp
@endif

@if (1 === $inclusive)
    @php
        $inclusive = 'border-separator-yellow';
    @endphp
@else
    @php
        $inclusive = 'border-separator-light';
    @endphp
@endif

<div {{ $attributes->merge(['class' => 'position-relative d-inline-block']) }} id="imageProfile">
    <img src="{{ $avatar }}" alt="alternate text" class="rounded-xl border {{ $inclusive }} border-4 sw-13 sh-13" />
</div>
