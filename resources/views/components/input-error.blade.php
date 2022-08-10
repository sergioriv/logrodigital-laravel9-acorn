@props(['disabled' => false, 'hasError' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'form-control']) !!}>

@error($hasError)
<div class="invalid-feedback d-block logro-label">{{ $message }}</div>
@enderror
