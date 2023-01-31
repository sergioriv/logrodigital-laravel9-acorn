<div class="modal-header">
    <h5 class="modal-title">{{ __('Edit attendance') . ' - ' . $attendance->date }}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<form action="{{ route('attendance.update', $attendance) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="modal-body">

        <table class="table table-striped mb-0">
            <tbody>
                @foreach ($students as $student)
                    <tr>
                        <td>
                            <label class="form-check custom-icon mb-0 unchecked-opacity-25">
                                <input type="checkbox" class="form-check-input"
                                    name="studentsAttendance[{{ $student->code }}]" value="1" editAttendanceStudent
                                    data-code="Edit{{ $student->code }}" @checked($student->oneAttendanceStudent?->attend === 'Y')>
                                <span class="form-check-label">
                                    <span class="content">
                                        <span class="heading mb-1 d-block lh-1-25">
                                            {{ $student->getCompleteNames() }}
                                            <x-tag-student :student="$student" />
                                        </span>
                                    </span>
                                </span>
                            </label>
                        </td>
                        <td>
                            <!-- Dropdown Button Start -->
                            <div id="dropdownEdit{{ $student->code }}"
                                class="@if ($student->oneAttendanceStudent?->attend === 'Y') d-none @endif">
                                <button type="button" class="btn btn-sm btn-outline-primary btn-icon btn-icon-only"
                                    data-bs-offset="0,3" data-bs-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false" data-bs-auto-close="inside">
                                    <i class="icon bi-three-dots-vertical"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <div class="dropdown-item">
                                        <label class="form-label">
                                            <input type="radio" name="studentsAttendance[{{ $student->code }}][type]"
                                                value="late-arrival" @checked($student->oneAttendanceStudent?->attend === 'L') />
                                            {{ __('Late arrival') }}
                                        </label>
                                    </div>
                                    <div class="dropdown-item">
                                        <label class="form-label">
                                            <input type="radio" name="studentsAttendance[{{ $student->code }}][type]"
                                                value="justified" @checked($student->oneAttendanceStudent?->attend === 'J') />
                                            {{ __('Justified') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <!-- Dropdown Button End -->
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
    <div class="modal-footer">
        <x-button class="btn-outline-danger" data-bs-dismiss="modal">{{ __('Close') }}</x-button>
        <x-button type="submit" class="btn-primary">{{ __('Update') }}</x-button>
    </div>
</form>
<script>
    jQuery("form").on("submit", function (event) {
        $("button[type='submit']", this).prop("disabled", true);
    });

    jQuery("[editAttendanceStudent].form-check-input").click(function () {

    var studentCode = $(this).data('code');

    $("#dropdown" + studentCode + " input").prop('checked', false);

    if ($(this).prop('checked')) {
        $('#dropdown' + studentCode).addClass('d-none');
    } else {
        $('#dropdown' + studentCode).removeClass('d-none');
    }
    });
</script>
