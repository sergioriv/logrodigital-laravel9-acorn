@php
    $badge = \App\Http\Controllers\SchoolController::badge()
@endphp
<a href="/" class="logo-lobby">
    @if ($badge)
        <img class="sw-13 sh-13 object-fit-fill"
            src="{{ config('app.url') .'/'. $badge }}"
            alt="logo">
    @else
        <div class="logo-default"></div>
    @endif
</a>
