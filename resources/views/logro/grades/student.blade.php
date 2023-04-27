<form action="{{ route('group.student.save-qualification', [$groupId, $studentId]) }}" id="studentGradesForm" method="POST">
    @csrf
    @method('PATCH')

    <div class="modal-body">
        {{-- @json($areas) --}}
        <table class="table">
            <thead>
                <tr>
                    <th>Asignatura</th>
                    @foreach ($periods as $period)
                        <th class="col-2 text-center">P {{ $period->ordering }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($areas as $area)
                    @foreach ($area->subjects as $index => $subject)
                        <tr>
                            <td>{{ $subject->resourceSubject->public_name }}</td>

                            @foreach ($periods as $period)
                                @php
                                    $grade = $grades->filter(function ($f) use ($subject, $period) {
                                            return
                                                $f->teacher_subject_group_id === $subject?->teacherSubject?->id
                                                && $f->period_id === $period->id;
                                        })
                                        ->first();
                                @endphp
                                <td>
                                    <input
                                        class="form-control"
                                        type="number"
                                        min="{{ $studyTime->minimum_grade }}"
                                        max="{{ $studyTime->maximum_grade }}"
                                        step="{{ $studyTime->step }}"
                                        @if ($grade) name="period[{{ $period->id }}][grades][{{ $grade->id }}]"
                                        @elseif ($subject->teacherSubject) name="period[{{ $period->id }}][grades_teachers][{{ $subject->teacherSubject->id }}]"
                                        @else name="period[{{ $period->id }}][grades_subjects][{{ $subject->id }}]" @endif
                                        value="{{ $grade->final ?? null }}" />
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>

    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">{{ __('Close') }}</button>
        <button type="submit" class="btn btn-outline-primary">{{ __('Save grades') }}</button>
    </div>
</form>
