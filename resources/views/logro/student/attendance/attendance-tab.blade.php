<!-- Attendance Table Start -->
<div class="">
    <table class="data-table responsive nowrap stripe dataTable no-footer dtr-inline" logro="dataTableBoxed"
        data-order='[]'>
        <thead>
            <tr>
                <th class="text-muted text-small text-uppercase p-0 pb-2">{{ __('date') }}</th>
                <th class="text-muted text-small text-uppercase p-0 pb-2">{{ __('subject') }}</th>
                <th class="text-muted text-small text-uppercase p-0 pb-2">{{ __('Teacher') }}</th>
                <th class="text-muted text-small text-uppercase p-0 pb-2">{{ __('type') }}</th>
                <th class="sw-5 empty">&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($absences as $absence)
                <tr>
                    <td>{{ $absence->dateLabel() }}</td>
                    <td>{{ $absence->teacherSubjectGroup?->subject->resourceSubject->name }}</td>
                    <td>{{ $absence->teacherSubjectGroup?->teacher?->getFullName() }}</td>
                    <td class="align-baseline">
                        <span>{{ $absence->student->attend->getLabelText() }}</span>
                        <aside class="badge bg-muted">x{{ $absence->hours }}</aside>
                    </td>
                    <td class="text-end">
                        @if (auth()->user()->hasRole('COORDINATOR')
                            || auth()->user()->hasRole('SUPPORT')
                            || (auth()->user()->hasRole('TEACHER') && $absence->teacherSubjectGroup->teacher_id === auth()->id())
                        )
                        <!-- Dropdown Button Start -->
                        <div class="">
                            <button type="button" class="btn btn-sm btn-outline-primary btn-icon btn-icon-only"
                                data-bs-offset="0,3" data-bs-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false" data-submenu>
                                <i data-acorn-icon="more-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                @if (is_null($absence->student->file_support))
                                <button class="dropdown-item btn-icon btn-icon-start" onclick="attendanceFile('{{ $absence->id }}','{{ $absence->student->student_id }}')">
                                    <i data-acorn-icon="upload"></i>
                                    <span>Cargar documento soporte</span>
                                </button>
                                <button class="dropdown-item btn-icon btn-icon-start" type="button" onclick="absenceChangeType('{{$absence->student->id}}','{{$absence->student->attend->value}}')">
                                    <i data-acorn-icon="edit-square"></i>
                                    <span>Cambiar de tipo</span>
                                </button>
                                @else
                                <a class="dropdown-item"
                                    href="{{ config('app.url') .'/'. $absence->student->file_support }}"
                                    target="_blank">Ver archivo soporte</a>
                                @endif
                            </div>
                        </div>
                        <!-- Dropdown Button End -->
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<!-- Attendance Table End -->
