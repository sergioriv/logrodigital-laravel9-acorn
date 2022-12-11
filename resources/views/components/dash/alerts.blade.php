@props(['alerts'])

<h2 class="small-title">{{ __('Alerts') }}</h2>
@if (!$alerts->count())
    <div class="card mb-2">
        <div class="card-body p-3 pe-4 ps-4">{{ __('No pending alerts') }}</div>
    </div>
@endif
@foreach ($alerts as $alert)
    <div class="card border border-1 @if ($alert->priority === 1) border-orange @else border-light @endif mb-2">
        <div class="card-body p-3 pe-4 ps-4">
            <div>
                {{ __($alert->title, ['CREATE_BY' => $alert->createdRol->getFullName() ?? null, 'STUDENT_NAME' => $alert->student->getFullName() ?? null]) }}
            </div>
            <div class="mt-2 pt-2 border-top">
                {{ $alert->message }}
                <a type="button" href="{{ route('alert.checked', $alert) }}" class="ms-2 btn-sm btn-outline-info">{{ __('Mark as read') }}</a>
            </div>
        </div>
    </div>
@endforeach
