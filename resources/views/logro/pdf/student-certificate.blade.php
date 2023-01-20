<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $student->getFullName() }}</title>
    <style>
        * {
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            font-size: 10px;
            color: #373435;
        }

        @page {
            margin: 5em;
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

        .p-1 {
            padding: 2.5px;
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
            margin-top: 2em;
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
        .mt-5 {
            margin-top: 25px;
        }
        .mt-6 {
            margin-top: 30px;
        }

        .align-sub {
            vertical-align: sub;
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
            max-height: 70px;
        }

        .table {
            border-collapse: collapse;
        }

        .card {
            /* border: 2px solid #c4c4c4; */
            /* border-radius: 3px; */
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
            text-align: justify;
        }
        .f-size-8 {
            font-size: 8;
        }
    </style>
</head>

<body>


    <table class="table w-100" border="0">
        <tr>
            <td class="h-70p w-70p text-center align-sub">
                @if ($SCHOOL->badge)
                    <img class="badge" src="{{ config('app.url') . '/' . $SCHOOL->badge }}" alt="badge">
                @endif
            </td>
            <td class="t-center p-se-1">
                <p class="bold">
                    {{ $SCHOOL->name ?? null }}
                </p>
                <p class="f-size-8">
                    Creado mediante acuerdo No. 033 de Octubre de 1996 Resolución de Reconocimiento No.
                    4657 de Noviembre de 2003, Resolución No. 006319 de Noviembre 17 de 2006, Resolución No. 0011778 de
                    Diciembre de 2007 Resolución No. 005850 del 10 de Julio de 2009.
                </p>
            </td>
            <td class="h-70p w-70p align-sub text-center">&nbsp;</td>
        </tr>
    </table>

    <section class="mt-6 text-center bold">
        EL SUSCRITO RECTOR/A DE LA INSTITUCIÓN
        <br />
        {{ $SCHOOL->name ?? null }}
        <br />
        <div class="mt-4">CERTIFICA QUE:</div>
    </section>

    <section class="mt-4 text-justify">
        El (La) Estudiante {{ $student->getCompleteNames() }},
        identificado(a) con {{ $student->document_type_code ?? '_______' }} No. {{ $student->document ?? '_______________________' }}
        se encuentra matriculado(a) en esta Institución,
        cursando {{ __($student->studyYear->resource->name) }}
        durante el presente año lectivo {{ $date->format('Y') }},
        en la sede {{ $student->headquarters->name }} jornada {{ $student->studyTime->name }} grupo {{ $student->group->name }}.
    </section>
    <section class="mt-2">
        Se expide la presente certificación a los {{ $date->format('d') }} días del mes {{ $date->format('m') }} de {{ $date->format('Y') }}, para sus trámites pertinentes.
    </section>
    <section class="mt-2 bold">
        Este certificado únicamente es valido con sello de rectoría en original.
    </section>

    <section class="card mt-6">
        <table class="table w-100">
            <tr>
                <td class="t-center w-50">
                    <div class="signature">
                        @if ($SCHOOL->signature_rector !== null)
                            <img src="{{ asset($SCHOOL->signature_rector) }}">
                        @endif
                    </div>
                    <div class="signature_name bold">
                        {{ $SCHOOL->rector_name ?? null }}
                    </div>
                    <div class="bold">RECTOR</div>
                </td>
            </tr>
        </table>
    </section>

</body>

</html>
