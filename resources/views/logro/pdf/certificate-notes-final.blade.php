<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Certificado</title>
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

        .f-size-9 {
            font-size: 9px;
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

        .text-justify {
            text-align: justify
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
            <td rowspan="2" class="h-60p w-70p text-center align-sub">
                @if ($SCHOOL->badge)
                    <img class="badge" src="{{ imgBase64($SCHOOL->badge) }}" alt="badge">
                @endif
            </td>
            <td rowspan="2" class="t-center p-se-1">
                <p class="t-title bold">
                    {{ $SCHOOL->name ?? null }}
                </p>
                <p>{!! \App\Models\HeadersAndFooters::first()->headerDocsHtml() !!}</p>
            </td>
            <td class="w-70p text-end">
                {!! isset($folio) ? 'FOLIO: '. $folio : '' !!}
            </td>
        </tr>
        <tr>
            <td class="text-end align-bottom">{{ $date }}</td>
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
                    <b>Año lectivo:</b>
                    {{ $group->schoolYear->name }}
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

    <section>
        <p class="bold text-center text-uppercase f-size-9 mt-3">EL SUSCRITO RECTOR DE LA {{ $SCHOOL->name ?? null }}</p>
        <p class="text-center f-size-9 mt-3">CERTIFICA:</p>
        <P class="text-justify f-size-9 mt-3">
            Que el (la) estudiante <span class="text-uppercase f-size-9">{{ $student->getCompleteNames() }}</span>, identificado(a) con {{ $student->documentTypeCode->code }} No. {{ $student->document }},
            cursó y {{ $reportYear?->result ? 'aprobó' : 'reprobo' }} en esta Institución Educativa los estudios correspondientes al grado <span class="text-uppercase f-size-9">{{ $group->studyYear->name }}</span> de {{ $group->studyYear->resource->type }}, durante el año lectivo escolar {{ $group->schoolYear->name }}
            @if ($nextStudyYear) y {{ $reportYear->result ? '' : 'no' }} fue promovido al grado <span class="text-uppercase f-size-9">{{ $nextStudyYear->name }}</span> de {{ $nextStudyYear->resource->type }} @endif
            de conformidad con la Ley 115 del 08 de Febrero de 1994, el Decreto 1860 del 03 de Agosto de 1994, la Ley 715 del 01 de diciembre de 2001, el Decreto 1850 del 13 de agosto de 2002, el Decreto 1290 de 2009 y el Plan de Estudios de la Institución; con la siguiente intensidad horaria y
            obteniendo los siguientes resultados:
        </P>
    </section>

    @if ($group->studyYear->useGrades())
    <section class="p-1 card mt-3">
        <table class="table w-100" border="0">
            <thead>
                <tr>
                    <th>
                        <div class="table-title">AREA</div>
                    </th>
                    <th>
                        <div class="table-title">HIS</div>
                    </th>
                    <th>
                        <div class="table-title">NOTA</div>
                    </th>
                    <th>
                        <div class="table-title">DESEMPEÑO</div>
                    </th>
                </tr>
            </thead>
            <tbody>

                @php $lossesArea = 0; @endphp

                @foreach ($areas as $area)
                    @php
                        $areaHIS = 0;
                        $areaNotes = \App\Http\Controllers\GradeController::areaNoteStudent($student->id, $area, $periods, $grades, $studyTime);
                    @endphp

                    @foreach ($area->subjects as $subject)
                    @php
                        $areaHIS += $subject->academicWorkload->hours_week;
                    @endphp
                    @endforeach

                    {{-- Datos de Área --}}
                    <tr class="bold separator-bottom separator-top">
                        <td>{{ $area->name }}</td>
                        <td class="text-center">{{ $areaHIS }}</td>
                        <td class="text-center">{{ $areaNotes['total'] ?? '-' }}</td>
                        <td class="text-center text-capitalize">
                            @php
                            $performanceArea = \App\Http\Controllers\GradeController::performanceString($studyTime, $areaNotes['total']);
                            if ($performanceArea === __('low') && !$area->last) { $lossesArea++; }
                            @endphp
                            {{ $performanceArea }}
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="4" class="h-5p"></td>
                </tr>
            </tbody>
        </table>

        <div class="text-center bold table-title" style="padding: 5px 0;">
            {{ \App\Http\Controllers\GradeController::verifiedPassOrFail($studyTime, $lossesArea) }}
        </div>
    </section>

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
    </section>
    @endif

    <section class="mt-3">
        <p class="f-size-9">
            Este certificado para su validez no necesita autenticación en notaria según decreto N° 1024 de 1982. Expedido en la Institución, a los 19 dias del mes de Enero de 2024.
        </p>
    </section>

    <section style="margin-top: 40px">
        <table class="table w-100" border="0">
            <tr>
                <td style="width: 30%;">&nbsp;</td>
                <td class="h-60p text-center align-sub">
                    @if ($SCHOOL->signature_rector)
                        <img class="badge" src="{{ imgBase64($SCHOOL->signature_rector) }}" alt="badge">
                    @endif
                </td>
                <td style="width: 30%;">&nbsp;</td>
            </tr>
            <tr>
                <td style="width: 30%;">&nbsp;</td>
                <td align="center" style="border-top: 0.6px solid #333">
                    {!! $SCHOOL->rector_name ? $SCHOOL->rector_name .'<br>' : '' !!}
                    RECTOR/A
                </td>
                <td style="width: 30%;">&nbsp;</td>
            </tr>
        </table>
    </section>

</body>

</html>
