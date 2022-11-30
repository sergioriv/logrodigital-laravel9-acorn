@props(['student'])

@if (1 === $student->inclusive)
    <span class="badge bg-outline-warning">{{ __('inclusive') }}</span>
@endif
@if ('new' === $student->status)
    <span class="badge bg-outline-primary">{{ __($student->status) }}</span>
@elseif ('repeat' === $student->status)
    <span class="badge bg-outline-danger">{{ __($student->status) }}</span>
@endif
