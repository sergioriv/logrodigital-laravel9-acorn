@props(['avatar'])

@if ($avatar != NULL)
    <img class="profile" alt="profile" src="{{ $avatar }}" />
@else
    <img class="profile" alt="profile" src="/img/profile/profile-9.webp" />
@endif
