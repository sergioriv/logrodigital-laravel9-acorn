@props(['disabled' => false, 'hasError' => false])

<select {!! $attributes->merge() !!}>
{{ $slot }}
</select>

@error($hasError)
<div class="invalid-feedback d-block logro-label">{{ $message }}</div>
@enderror
