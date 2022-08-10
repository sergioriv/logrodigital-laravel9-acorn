@props(['disabled' => false, 'hasError' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'form-control']) !!}>

@if ($hasError)
    @error( $attributes->get('name'))
        <div class="invalid-feedback d-block logro-label">{{ $message }}</div>
    @enderror
@endif
