<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Consolidado</title>
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

        .f-size-8 {
            font-size: 8px;
        }

        .f-size-9 {
            font-size: 9px;
        }

        .w-30p {
            width: 30px;
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

        .pb-1 {
            padding-bottom: 2.5px;
        }

        .px-1 {
            padding-left: 2.5px !important;
            padding-right: 2.5px !important;
        }

        .px-2 {
            padding-left: 5px !important;
            padding-right: 5px !important;
        }

        .px-3 {
            padding-left: 7.5px !important;
            padding-right: 7.5px !important;
        }

        .py-2 {
            padding-top: 5px !important;
            padding-bottom: 5px !important;
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

        .text-start {
            text-align: left;
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

        .low_performance {
            background-color: rgba(182, 40, 54, 0.15);
            color: #cf2637;
            font-size: inherit;
        }

        .grade_estimado {
            color: rgb(119, 119, 119);
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

    <!-- header -->
    <table class="table w-100" border="0">
        <tr>
            <td class="h-60p w-70p text-center align-sub">
                @if ($SCHOOL->badge)
                    <img class="badge" src="{{ config('app.url') . '/' . $SCHOOL->badge }}" alt="badge">
                @endif
            </td>
            <td class="t-center p-se-1">
                <p class="t-title bold">
                    {{ $SCHOOL->name ?? null }}
                </p>
                <p>
                    <strong>CONSOLIDADO DE CALIFICACIONES</strong>
                </p>
            </td>
            <td class="h-60p w-70p text-end align-bottom">{{ now()->format('d / m / Y') }}</td>
        </tr>
    </table>

    @php
        $subjectLosses = [];
    @endphp

    <section>
        <div class="text-center bold f-size-8">
            GRUPO {{ $group->name }}
        </div>
        <div class="text-center bold f-size-8">
            {{ $area['name'] }}
        </div>
        <div class="text-center bold">
            {{ $period->name }} - {{ $period->workload }}%
        </div>
    </section>

    <section class="mt-2 p-1 card">
        <table class="table w-100" border="0">
            <!-- Asignaturas -->
            <tr>
                <th class="text-center" style="vertical-align: bottom; width:15px;">#</th>
                <th class="text-start" style="vertical-align: bottom">Estudiantes</th>
                @foreach ($area['subjects'] as $subject)
                    @php
                        $subjectLosses[$subject['id']] = 0;
                    @endphp
                    <th class="pb-1">
                        <div class="table-title px-3">{{ $subject['resource_name'] }}</div>
                    </th>
                @endforeach
                <th class="text-center pb-1" style="width: 40px;" align="center">
                    <div class="table-title px-3">TOTAL ÁREA</div>
                </th>
            </tr>

            @php
                $studentGradeArea = [];
            @endphp
            @foreach ($students as $key => $student)
                @php
                    $studentAreaTotal = 0;
                    $studentGradeArea[$student->id]['areaTotal'] = 0;
                @endphp
                <tr class="separator-top">
                    <td class="py-2" align="center">{{ ++$key }}</td>
                    <td>{{ $student->getCompleteNames() }}</td>
                    @foreach ($area['subjects'] as $subject)
                        @php
                            $gradeByStudentByPeriod = collect($subject['gradesByStudent'])
                                ->filter(function ($grade) use ($student, $period) {
                                    return $student->id === $grade['student_id'] && $grade['period_id'] === $period->id;
                                })
                                ->first();
                            $gradeFinal = $gradeByStudentByPeriod['final'] ?? null;
                            $studentAreaTotal += $gradeByStudentByPeriod['final_workload'] ?? null;

                            if ($gradeFinal <= $ST->low_performance && !is_null($gradeFinal)) {
                                $gradeFinalHtml = "<text class='table-title low_performance'>{$gradeFinal}</text>";
                                $subjectLosses[$subject['id']] += 1;
                            } else {
                                $gradeFinalHtml = $gradeFinal ?? '-';
                            }
                        @endphp

                        <td class="text-center" style="width: 20px">
                            {!! $gradeFinalHtml !!}
                        </td>
                    @endforeach
                    @php
                        $studentGradeArea[$student->id]['areaTotal'] = $GradeController::numberFormat($ST, $studentAreaTotal);

                        if ($studentAreaTotal <= $ST->low_performance && !is_null($studentGradeArea[$student->id]['areaTotal'])) {
                            $areaLosses += 1;
                        }
                        if ($studentGradeArea[$student->id]['areaTotal'] <= $ST->low_performance && !is_null($studentGradeArea[$student->id]['areaTotal'])) {
                            $studentGradeAreaHtml = "<text class='table-title low_performance'>{$studentGradeArea[$student->id]['areaTotal']}</text>";
                        } else {
                            $studentGradeAreaHtml = $studentGradeArea[$student->id]['areaTotal'] ?? '-';
                        }
                    @endphp
                    <td align="center" class="bold">
                        {!! $studentGradeAreaHtml !!}
                    </td>
                </tr>
            @endforeach
            <tr class="separator-top">
                <td colspan="{{ 3 + count($area['subjects']) }}">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2" style="text-transform: uppercase; text-align: center;">Cant. Estudiantes que
                    perdieron</td>
                @foreach ($area['subjects'] as $subject)
                    <th colspan="1">
                        <div class="table-title {{ $subjectLosses[$subject['id']] > 0 ? 'low_performance' : null }}">
                            {{ $subjectLosses[$subject['id']] }}</div>
                    </th>
                @endforeach
                <th>
                    <div class="table-title {{ $areaLosses > 0 ? 'low_performance' : null }}">{{ $areaLosses }}
                    </div>
                </th>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
        </table>
    </section>

    <!-- Mejores estudiantes -->
    <section class="mt-2 p-1 card">
        <table class="table w-100" border="0">
            <tr>
                <th colspan="2">
                    <div class="table-title">ESTUDIANTES CON MEJOR PROMEDIO</div>
                </th>
            </tr>
            @php
                $topStudents = array_keys($studentGradeArea, max($studentGradeArea));
            @endphp
            @foreach ($topStudents as $topStudent)
            @unless (is_null($studentGradeArea[$topStudent]['areaTotal']))
                <tr>
                    <td>
                        {{ $students->filter(function ($student) use ($topStudent) {
                                return $student->id === $topStudent;
                            })->first()->getCompleteNames() }}
                    </td>
                    <td>
                        {{ $studentGradeArea[$topStudent]['areaTotal'] }}
                    </td>
                </tr>
            @endunless
            @endforeach
        </table>
    </section>
</body>

</html>
