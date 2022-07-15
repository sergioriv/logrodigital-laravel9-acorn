@props(['avatar'])

@if ($avatar !== NULL)
    @php
        $avatar = config('app.url') .'/'. $avatar;
    @endphp
@else
    @php
        $avatar = config('app.url') .'/img/other/profile-11.webp';
    @endphp
@endif

<div {{ $attributes->merge(['class' => 'position-relative d-inline-block']) }} id="imageProfile">
    <img src="{{ $avatar }}" alt="alternate text" class="rounded-xl border border-separator-light border-4 sw-13 sh-13" />
    <button class="btn btn-sm btn-icon btn-icon-only btn-separator-light rounded-xl position-absolute e-0 b-0" type="button">
        <i data-acorn-icon="upload"></i>
    </button>
    <input name="avatar" id="avatar" class="file-upload d-none" type="file" accept="image/jpg, image/jpeg, image/png, image/webp" />
</div>
