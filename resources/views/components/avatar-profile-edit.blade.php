@props(['avatar','inclusive' => null])

@if (!is_null($avatar) && file_exists($avatar))
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
    <button class="btn btn-sm btn-icon btn-icon-only btn-separator-light rounded-xl position-absolute e-0 b-0" type="button">
        <i data-acorn-icon="upload"></i>
    </button>
    <input name="avatar" id="avatar" class="file-upload d-none" type="file" accept="image/jpg, image/jpeg, image/png, image/webp" />
</div>
