@props(['content'])

<div class="card mb-5">

    <div class="card-body">
        @if (count($content))
        <div class="accordion accordion-flush" id="accordionFlushAlerts-{{ $attributes?->get('name') }}">
            @foreach ($content as $alertStudent)
                <div class="accordion-item">
                    <div class="accordion-header" id="flush-heading-{{ $attributes?->get('name') .'-'.$loop->index }}">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#flush-collapse-{{ $attributes?->get('name') .'-'.$loop->index }}" aria-expanded="false"
                            aria-controls="flush-collapse-{{ $attributes?->get('name') .'-'.$loop->index }}">
                            <span class="font-weight-bold me-1">{{ '(' . $alertStudent->count() . ')' }}</span>
                            {{ $alertStudent[0]->student->getCompleteNames() }}
                            <span class="ms-2 mb-0 badge bg-muted text-uppercase">{{ $alertStudent[0]->student->group?->name }}</span>
                            <a href="{{ route('students.show', $alertStudent[0]->student->id) }}"><i
                                class="icon bi-box-arrow-in-up-right text-primary ms-2"></i></a>
                        </button>
                    </div>
                    <div id="flush-collapse-{{ $attributes?->get('name') .'-'.$loop->index }}" class="accordion-collapse collapse"
                        aria-labelledby="flush-heading-{{ $attributes?->get('name') .'-'.$loop->index }}"
                        data-bs-parent="#accordionFlushAlerts-{{ $attributes?->get('name') }}">
                        <div class="accordion-body px-5">
                            <div class="row g-2">
                                @foreach ($alertStudent as $alert)
                                    <div class="col-12 d-flex align-items-end content-container">
                                        <div
                                            class="bg-separator-light d-inline-block rounded-md py-3 px-3 pe-7 position-relative text-alternate">
                                            @if ($alert->priority)
                                                <span class="text" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title=""
                                                    data-bs-original-title="{{ __('priority') }}"><i
                                                        data-acorn-icon="warning-circle"
                                                        class="text-warning"></i></span>
                                            @endif
                                            <span class="text">{{ $alert->message }}</span>
                                            @if ( ! is_null($alert->sub_message) )
                                            <span class="text fst-italic">({{ $alert->sub_message }})</span>
                                            @endif

                                            <span
                                                class="position-absolute text-extra-small text-alternate opacity-75 b-2 e-2">
                                                {{ $alert?->created_user?->getFullName() }}
                                                | {{ $alert->created_at }}
                                                @hasrole('ORIENTATION')
                                                @if ($alert->user_approval_id)
                                                | {{ $alert->user_approval->getFullName() }}
                                                {!! $alert->approvalCheck() !!}
                                                @endif
                                                @endhasrole
                                            </span>

                                            <span class="text">

                                                @if ($attributes?->get('name') === 'remits')
                                                    <a type="button" href="{{ route('alert.approval', $alert) }}"
                                                        class="ms-2 text-info">{{ __('mark as read') }}</a>
                                                @else
                                                    <a type="button" href="{{ route('alert.checked', $alert) }}"
                                                        class="ms-2 text-info">{{ __('mark as read') }}</a>
                                                @endif
                                            </span>

                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @else
        <div class="text text-light">{{ __('No pending alerts') }}</div>
        @endif
    </div>

</div>
