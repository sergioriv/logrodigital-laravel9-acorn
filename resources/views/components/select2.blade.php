@props([
    'name',
    'modelfiled' => null,
    'array' => [],
    'hasError' => false
])

<select name="{{ $name }}" logro="select2" {!! $attributes->merge() !!}>
    <option label="&nbsp;"></option>
    @foreach ($array as $arr)
        <option value="{{ $arr->id }}"
            @if ($modelfiled !== null)
                @selected(old($name, $modelfiled) == $arr->id)
            @else
                @selected(old($name) == $arr->id)
            @endif>
            {{ $arr->name }}
        </option>
    @endforeach
</select>

@error($hasError)
<div class="invalid-feedback d-block logro-label">{{ $message }}</div>
@enderror




