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
            </tr>
        </thead>
        <tbody>
            @foreach ($attendance as $attendStudent)
                <tr>
                    <td>{{ $attendStudent->dateLabel() }}</td>
                    <td>{{ $attendStudent->teacherSubjectGroup->subject->resourceSubject->name }}</td>
                    <td>{{ $attendStudent->teacherSubjectGroup->teacher->getFullName() }}</td>
                    <td>{{ $attendStudent->student->attend->getLabelText() }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<!-- Attendance Table End -->
