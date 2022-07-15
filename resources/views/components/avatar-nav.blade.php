@props(['avatar'])

@if ($avatar != NULL)
    <img class="profile" alt="profile" src="{{ env('APP_URL') .'/'. $avatar }}" />
@else
    <img class="profile" alt="profile" src="/img/other/profile-11.webp" />
@endif
