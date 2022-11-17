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
                @if ($alert->created_rol === 'ORIENTATION')
                {{ __($alert->title, ['CREATED_BY' => $alert->orientator->getFullName(), 'STUDENT_NAME' => $alert->student->getFullName()]) }}
                @elseif ($alert->created_rol === 'TEACHER')
                {{ __($alert->title, ['CREATED_BY' => $alert->teacher->getFullName(), 'STUDENT_NAME' => $alert->student->getFullName()]) }}
                @endif
            </div>
            <div class="mt-2 pt-2 border-top">
                {{ $alert->message }}
            </div>
        </div>
    </div>
@endforeach
