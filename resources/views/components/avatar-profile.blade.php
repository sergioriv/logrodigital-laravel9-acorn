@props(['avatar'])

@if ($avatar !== NULL)
    @php
        $avatar = config('app.url') .'/'. $avatar;
    @endphp
@else
    @php
        $avatar = config('app.url') .'/img/profile/profile-11.webp';
    @endphp
@endif

<div {{ $attributes->merge(['class' => 'position-relative d-inline-block']) }} id="imageProfile">
    <img src="{{ $avatar }}" alt="alternate text" class="rounded-xl border border-separator-light border-4 sw-13 sh-13" />
</div>
