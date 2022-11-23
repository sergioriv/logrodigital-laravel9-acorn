@props(['subject' => null])

@if ($subject)
    <div class="col small-gutter-col">
        <div class="card h-100 hover-border-primary border-0">
            <a href="{{ route('teacher.my.subjects.show', $subject) }}">
                <div class="card-body text-center d-flex flex-column">
                    <h5 class="text-primary font-weight-bold">{{ $subject->group->name }}</h5>
                    <small class="text-muted">{{ $subject->group->headquarters->name }}</small>
                    <small class="text-muted">{{ $subject->group->studyTime->name }}</small>
                    <small class="text-muted">{{ $subject->group->studyYear->name }}</small>
                    <small class="text-muted">
                        @if (null !== $subject->group->teacher_id)
                            <i class="icon icon-15 bi-award text-muted"></i>
                            <span>
                                {{ $subject->group->teacher->getFullName() }}
                            </span>
                        @else
                            <span>&nbsp;</span>
                        @endif
                    </small>
                    {{ $slot }}
                </div>
            </a>
        </div>
    </div>
@endif
