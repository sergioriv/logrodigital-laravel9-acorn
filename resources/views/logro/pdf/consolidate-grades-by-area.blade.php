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
        .py-2 {
            padding-top: 5px;
            padding-bottom: 5px;
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
            border-radius: 4px;
            padding: 2px 4px;
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

        .rotated_vertical {
            -webkit-transform: rotate(270deg);
            -moz-transform: rotate(270deg);
            -ms-transform: rotate(270deg);
            -o-transform: rotate(270deg);
            transform: rotate(270deg);
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
                    <strong>CONSOLIDADO DE CALIFICACIONES
                        <br />
                        {{ $area['name'] }}</strong>
                    <br />
                    GRUPO {{ $group->name }}
                </p>
            </td>
            <td class="h-60p w-70p text-end align-bottom">{{ now()->format('d / m / Y') }}</td>
        </tr>
    </table>

    <section class="mt-2 p-1 card">
        <table class="table w-100" border="0">
            <!-- Asignaturas -->
            <tr>
                <th colspan="2">&nbsp;</th>
                @foreach ($area['subjects'] as $subject)
                    <th colspan="{{ 1 + count($periods) }}">
                        <div class="table-title">{{ $subject['resource_name'] }}</div>
                    </th>
                @endforeach
            </tr>

            <!-- Periodos -->
            <tr>
                <th class="h-50p py-2 text-center" style="vertical-align: bottom">#</th>
                <th class="h-50p py-2 text-start" style="vertical-align: bottom">Estudiantes</th>
                @foreach ($area['subjects'] as $subject)
                @foreach ($periods as $period)
                    @php
                        $periodName = 'P' . $period['ordering'] . ' - ' . $period['workload'];
                    @endphp
                    @if ($period['end'] > today())
                        @php
                            $periodName .= '<br>(estimado)';
                        @endphp
                    @endif
                    <th class="text-center w-30p" align="center">
                        <div class="rotated_vertical">{!! $periodName !!}</div>
                    </th>
                    @if ($period['end'] < today())
                        <th class="text-center w-30p" align="center">
                            <div class="rotated_vertical">AC</div>
                        </th>
                    @endif
                @endforeach
                @endforeach
            </tr>

            @foreach ($students as $key => $student)
                <tr class="separator-top">
                    <td class="py-2" align="center">{{ ++$key }}</td>
                    <td>{{ $student->getCompleteNames() }}</td>
                    @foreach ($area['subjects'] as $subject)
                        @php
                            $acumulado = 0;
                            $currentPorcentage = 0;
                            $estimado = 0;
                        @endphp
                        @foreach ($periods as $period)
                            @php
                                $gradeByStudentByPeriod = collect($subject['gradesByStudent'])
                                    ->filter(function ($grade) use ($student, $period) {
                                        return $student->id === $grade['student_id'] && $grade['period_id'] === $period['id'];
                                    })
                                    ->first();
                                $gradeFinal = $gradeByStudentByPeriod['final'] ?? null;
                                $gradeFinalHtml = $gradeFinal <= $ST->low_performance && !is_null($gradeFinal)
                                    ? "<text class='low_performance'>{$gradeFinal}</text>"
                                    : $gradeFinal;
                            @endphp

                            <td class="text-center" style="width: 20px">
                                {!! $gradeFinalHtml ?: ($GradeController::numberFormat($ST, $estimado) ? '<text class="grade_estimado">'. $GradeController::numberFormat($ST, $estimado) .'</text' : '-') !!}
                            </td>
                            @if ($period['end'] < today())
                                @php
                                    $acumulado += $gradeFinal * $period['workload_porcentage'];
                                    $currentPorcentage += $period['workload_porcentage'];

                                    if ($gradeFinal) {
                                        $x = 3.0 - $acumulado;
                                        $estimado += $x / (1 - $period['workload_porcentage']);
                                    }
                                @endphp
                                <td class="text-center" style="width: 20px">
                                    {{ $GradeController::numberFormat($ST, $acumulado) ?? '-' }}</td>
                            @endif
                        @endforeach
                    @endforeach
                </tr>
            @endforeach
            <tr></tr>
        </table>
    </section>
</body>

</html>
