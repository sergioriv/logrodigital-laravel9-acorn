<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte - {{ __('Period') }} {{ $currentPeriod->ordering }}</title>
    <style>
        * {
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            font-size: 7px;
            color: #373435;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
        }

        @page {
            margin: 3em;
        }

        .f-size-5 {
            font-size: 5px;
        }

        .f-size-6 {
            font-size: 6px;
        }

        .w-70p {
            width: 70px;
        }

        .w-50 {
            width: 50%;
        }

        .w-100 {
            width: 100%;
        }

        .h-5p {
            height: 5px;
        }

        .h-10p {
            height: 10px;
        }

        .h-20p {
            height: 20px;
        }

        .h-30p {
            height: 30px;
        }

        .h-40p {
            height: 40px;
        }

        .h-50p {
            height: 50px;
        }

        .h-60p {
            height: 60px;
        }

        .h-70p {
            height: 70px;
        }

        .t-center {
            text-align: center;
        }

        .t-start {
            text-align: left;
        }

        .t-title {
            font-size: 8px;
            text-transform: uppercase;
        }

        .bold {
            font-weight: bold;
        }

        /* PADDING */
        .pe-1 {
            padding-right: 2.5px;
        }

        .pe-2 {
            padding-right: 5px;
        }

        .ps-1 {
            padding-left: 2.5px;
        }

        .ps-2 {
            padding-left: 5px;
        }

        .p-1 {
            padding: 2.5px;
        }

        .p-2 {
            padding: 5px;
        }

        .p-se-1 {
            padding: 0 10px;
        }

        .p-se-2 {
            padding: 0 20px;
        }

        .p-se-3 {
            padding: 0 30px;
        }

        .p-se-4 {
            padding: 0 40px;
        }

        .p-se-5 {
            padding: 0 50px;
        }

        .p-2p {
            padding: 2px;
        }

        .p-3p {
            padding: 3px;
        }

        .p-4p {
            padding: 4px;
        }

        .p-tb-0 {
            padding-top: 0;
            padding-bottom: 0;
        }

        .p-se-0 {
            padding-left: 0;
            padding-right: 0;
        }

        .mt-1 {
            margin-top: 5px;
        }

        .mt-2 {
            margin-top: 10px;
        }

        .mt-3 {
            margin-top: 15px;
        }

        .mt-4 {
            margin-top: 20px;
        }

        .mb-1 {
            margin-bottom: 5px;
        }

        .mb-2 {
            margin-bottom: 10px;
        }

        .mb-3 {
            margin-bottom: 15px;
        }

        .mb-4 {
            margin-bottom: 20px;
        }

        .align-sub {
            vertical-align: sub;
        }
        .align-bottom {
            vertical-align: bottom;
        }

        .form-control {
            border: .3px solid #c4c4c4;
            border-radius: 2px;
            padding: 2.3px 4px;
            min-height: 9px;
        }

        .label {
            margin-bottom: 1.5px;
        }

        .badge {
            max-width: 70px;
            height: 55px;
        }

        .table {
            border-collapse: collapse;
        }

        .card {
            border: .3px solid #c4c4c4;
            border-radius: 4px;
        }

        .card-header {
            background-color: #c4c4c4;
            color: #fff;
            font-weight: bold;
            padding: 3px 7px;
            border-radius: 2.5px 2.5px 0 0;
        }

        .card-content {
            border: .6px solid #c4c4c4;
            padding: 2.5px;
            border-top: 0px;
            border-radius: 0 0 2.5px 2.5px;
        }

        .signature {
            height: 75px;
        }

        .signature_name {
            text-transform: uppercase;
        }

        .signature img {
            max-width: 220px;
            height: 70px;
        }

        .text-center {
            text-align: center;
        }

        .text-end {
            text-align: right;
        }

        .text-capitalize {
            text-transform: capitalize;
        }
        .text-uppercase {
            text-transform: uppercase;
        }

        .table-title {
            background-color: #d9d9d9;
            padding: 3px;
            border-radius: 4px;
            font-size: 6px;
        }

        .separator-top {
            border-top: .3px solid #c4c4c4;
        }

        .separator-bottom {
            border-bottom: .3px solid #c4c4c4;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>


    <table class="table w-100" border="0">
        <tr>
            <td class="h-60p w-70p text-center align-sub">
                @if ($SCHOOL->badge)
                    <img class="badge" src="{{ asset($SCHOOL->badge) }}" alt="badge">
                @endif
            </td>
            <td class="t-center p-se-1">
                <p class="t-title bold">
                    {{ $SCHOOL->name ?? null }}
                </p>
                <p>{!! \App\Models\HeadersAndFooters::first()->headerDocsHtml() !!}</p>
            </td>
            <td class="h-60p w-70p text-end align-bottom">{{ $date }}</td>
        </tr>
    </table>

    <table class="table w-100 mb-1">
        <tr>
            <td class="w-50 pe-1">
                <div class="card p-1">
                    <b>Estudiante:</b>
                    {{ $student->getCompleteNames() }}
                    <br />
                    <b>Sede:</b>
                    {{ $group->headquarters->name }}
                    <br />
                    <b>Grado:</b>
                    {{ $group->studyYear->name }}
                </div>
            </td>
            <td class="w-50 ps-1">
                <div class="card p-1">
                    <b>Reporte de notas:</b>
                    {{ $titleReportNotes }}
                    <br />
                    <b>Grupo:</b>
                    {{ $group->name }}
                    <br />
                    <b>Director de grupo:</b>
                    {{ $group->teacher ? $group->teacher->getFullName() : null }}
                </div>
            </td>
        </tr>
    </table>

    <section class="p-1 card">
        <table class="table w-100" border="0">
            <thead>
                <tr>
                    <th>
                        <div class="table-title">AREAS Y ASIGNATURAS</div>
                    </th>
                    <th>
                        <div class="table-title">%</div>
                    </th>
                    <th>
                        <div class="table-title">F</div>
                    </th>
                    <th>
                        <div class="table-title">DOCENTE</div>
                    </th>

                    @foreach ($periods as $period)
                        <th>
                            <div class="table-title">P{{ $period->ordering }}</div>
                        </th>
                    @endforeach
                    @if ('FINAL' === $currentPeriod)
                        <th>
                            <div class="table-title">FINAL</div>
                        </th>
                    @endif
                    <th>
                        <div class="table-title">DESEMPEÑO
                            {{ $currentPeriod !== 'FINAL' ? 'P' . $currentPeriod->ordering : 'FINAL' }}</div>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr class="text-center">
                    <td colspan="4">&nbsp;</td>
                    @foreach ($periods as $period)
                        <td class="f-size-5">{{ "{$period->workload}%" }}</td>
                    @endforeach
                    @if ('FINAL' === $currentPeriod)
                        <td class="f-size-5">&nbsp;</td>
                    @endif
                </tr>
                <tr>
                    <td class="h-5p"></td>
                </tr>

                @php $overallAvg = 0 @endphp

                @foreach ($areas as $area)
                    @php
                        $areaNotes = \App\Http\Controllers\GradeController::areaNoteStudent($area, $periods, $grades, $studyTime);
                        $overallAvg += $areaNotes['overallAvg'];

                        $lastAreaGrade = null;
                    @endphp

                    {{-- Datos de Área --}}
                    <tr class="bold separator-bottom separator-top">
                        <td colspan="4">{{ $area->name }}</td>
                        @foreach ($periods as $period)
                            <td class="text-center" style="width: 20px">{{ $areaNotes['area'][$period->ordering] ?? '-' }}
                            </td>
                            @if ($loop->last && $currentPeriod !== 'FINAL')
                                @php $lastAreaGrade = $areaNotes['area'][$period->ordering] @endphp
                            @endif
                        @endforeach
                        @if ('FINAL' === $currentPeriod)
                            @php $lastAreaGrade = $areaNotes['total'] @endphp
                            <td class="text-center">{{ $areaNotes['total'] ?? '-' }}</td>
                        @endif
                        <td class="text-center text-capitalize">
                            @if ($lastAreaGrade)
                                {{ \App\Http\Controllers\GradeController::performanceString($studyTime, $lastAreaGrade) }}
                            @endif
                        </td>
                    </tr>

                    {{-- Asignaturas del Área --}}
                    @foreach ($area->subjects as $subject)
                        @php $lastPeriodGrade = null @endphp
                        <tr>
                            <td class="f-size-6">{{ $subject->resourceSubject->public_name }}</td>
                            <td class="f-size-6 text-center">
                                @if (!is_null($subject->academicWorkload))
                                {{ $subject->academicWorkload->course_load }}
                                @else
                                {{ '0' }}
                                @endif
                                %</td>
                            <td class="f-size-6 text-center">  <!-- FALLAS -->
                                @php
                                    $absencesSubject = $absencesTSG->filter(function ($tsg) use ($subject) {
                                        return $tsg->id == $subject->teacherSubject?->id;
                                    })->first();
                                @endphp
                                {{ $absencesSubject->attendances_count ?? 0 }}
                            </td>
                            <td class="f-size-6">
                                @if (!is_null($subject->teacherSubject))
                                    {{ $subject->teacherSubject?->teacher?->getFullName() }}
                                @endif
                            </td>
                            @foreach ($periods as $period)
                                @php
                                    $gradePeriod =
                                        $grades
                                            ->filter(function ($g) use ($subject, $period) {
                                                if (!is_null($subject->teacherSubject))
                                                    return $g->teacher_subject_group_id == $subject->teacherSubject->id
                                                        && $g->period_id == $period->id;
                                            })
                                            ->first()->final ?? null;
                                @endphp

                                @if ($loop->last && $currentPeriod !== 'FINAL')
                                    @php $lastPeriodGrade = $gradePeriod @endphp
                                @endif

                                <td class="f-size-6 text-center">
                                    {{ \App\Http\Controllers\GradeController::numberFormat($studyTime, $gradePeriod) ?? '-' }}
                                </td>
                            @endforeach

                            @if ('FINAL' === $currentPeriod)
                            @php $lastPeriodGrade = $areaNotes['totalSubject'][$subject->id] @endphp
                                <td class="f-size-6 text-center">{{ $areaNotes['totalSubject'][$subject->id] ?? '-' }}</td>
                            @endif

                            <td class="f-size-6 text-center text-capitalize">
                                {{-- @unless('FINAL' === $currentPeriod) --}}
                                @if (!is_null($gradePeriod))
                                {{ \App\Http\Controllers\GradeController::performanceString($studyTime, $lastPeriodGrade) }}
                                @endif
                                {{-- @endunless --}}
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="5" class="h-5p"></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="text-center bold table-title">
            PROMEDIO GENERAL:
            {{ \App\Http\Controllers\GradeController::numberFormat($studyTime, $overallAvg / count($areas)) }}
        </div>
    </section>

    @if ('FINAL' !== $currentPeriod)
    <section class="mt-1 p-1 card f-size-6">
            <b class="f-size-6">OBSERVACIONES:</b>
            {{ $remark }}
    </section>
    @endif

    <section class="p-1 card mt-1">
        <div class="f-size-6">
            Rangos de desempeño:
            <text class="text-capitalize f-size-6">
            {{ __('low') . " ({$studyTime->lowRange()})" }}
            | {{ __('basic') . " ({$studyTime->basicRange()})" }}
            | {{ __('high') . " ({$studyTime->highRange()})" }}
            | {{ __('superior') . " ({$studyTime->superiorRange()})" }}
            </text>
        </div>
        <div class="f-size-6">Desempeño <b class="f-size-6">Bajo</b> corresponde al área o asignatura perdida.</div>
        <div class="f-size-6"><b class="f-size-6">%</b> carga académica.
        | <b class="f-size-6">F</b> cantidad de fallas periodo.</div>
    </section>

    <section style="margin-top: 40px">
        <table class="table w-100" border="0">
            <tr>
                <td style="width: 10%;"></td>
                <td class="h-60p w-70p text-center align-sub" style="width: 30%;"></td>
                <td style="width: 20%;"></td>
                <td class="h-60p w-70p text-center align-sub" style="width: 30%;">
                    @if ($SCHOOL->signature_rector)
                        <img class="badge" src="{{ asset($SCHOOL->signature_rector) }}" alt="badge">
                    @endif
                </td>
                <td style="width: 10%;"></td>
            </tr>
            <tr>
                <td style="width: 10%;"></td>
                <td align="center" style="border-top: 0.6px solid #333">
                    {!! $group->teacher ? $group->teacher->getFullName() .'<br>' : '' !!}
                    DIRECTOR DE GRUPO
                </td>
                <td style="width: 20%;"></td>
                <td align="center"  style="border-top: 0.6px solid #333">
                    {!! $SCHOOL->rector_name ? $SCHOOL->rector_name .'<br>' : '' !!}
                    RECTOR/A
                </td>
                <td style="width: 10%;"></td>
            </tr>
        </table>
    </section>

    <!-- Descriptors Section Start -->
    @if ('FINAL' !== $currentPeriod)
    @if (!$descriptors->isEmpty())

        <!-- New Page -->
        <div class="page-break"></div>

        <section>
            <p class="text-center text-uppercase">{{ __('Descriptors') }}</p>
            @foreach ($descriptors as $descriptor)
            <div class="card p-1">
                <div class="text-center bold table-title">{{ $descriptor->subject->resourceSubject->name }}</div>
                <ul>
                    @foreach ($descriptor->descriptorsStudent as $descriptorStudent)
                    <li>{{ $descriptorStudent->descriptor->content }} @if($descriptorStudent->descriptor->inclusive) {{ '('. __('inclusive') .')' }} @endif</li>
                    @endforeach
                </ul>
            </div>
            @endforeach
        </section>

    @endif
    @endif
    <!-- Descriptors Section End -->

</body>

</html>
