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
            font-size: 7px;
            color: #373435;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
        }

        @page {
            margin: 3em;
        }

        .header,
        .footer {
            width: 100%;
            text-align: center;
            position: fixed;
        }

        .header {
            top: 0px;
        }

        .footer {
            bottom: 0px;
        }

        .content {
            position: relative;
            margin-top: 100px;
            margin-bottom: 80px;
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

        .w-33 {
            width: 33.3% !important;
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

        .t-end {
            text-align: right;
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
        .pt-4 {
            padding-top: 20px;
        }
        .pt-6 {
            padding-top: 30px;
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

        .mt-7 {
            margin-top: 35px;
        }

        .mt-8 {
            margin-top: 40px;
        }

        .mt-9 {
            margin-top: 45px;
        }

        .mt-10 {
            margin-top: 50px;
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
            text-align: justify;
        }



        .f-size-6 {
            font-size: 6px;
        }
        .f-size-7 {
            font-size: 7px;
        }
        .f-size-8 {
            font-size: 8px;
        }

        .f-size-9 {
            font-size: 9px;
        }

        .f-size-10 {
            font-size: 10px;
        }

        .align-bottom {
            vertical-align: bottom;
        }

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
        .w-20 {
            width: 20%;
        }
        table.border, .border th, .border td {
            border: 0.3px solid #c4c4c4;
        }

    </style>
</head>

<body>


    <table class="table" border="0">
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
                    Creado mediante acuerdo No. 033 de Octubre de 1996 Resolución de Reconocimiento No.
                    4657 de Noviembre de 2003, Resolución No. 006319 de Noviembre 17 de 2006, Resolución No. 0011778 de
                    Diciembre de 2007 Resolución No. 005850 del 10 de Julio de 2009.
                </p>
            </td>
            <td class="h-60p w-70p t-end align-bottom">{{ $date }}</td>
        </tr>
    </table>

    <div>&nbsp;</div>

    <table class="table w-100 mb-1">
        <tr>
            <td class="w-33 pe-1">
                <div class="card p-1">
                    <b>Estudiante:</b>
                    {{ $student->getCompleteNames() }}
                    <br />
                    <b>Documento:</b>
                    {{ $student->document_type_code . ' - ' . $student->document }}
                </div>
            </td>
            <td class="w-33 ps-1 pe-1">
                <div class="card p-1">
                    <b>Sede:</b>
                    {{ $student->groupYear->group->headquarters->name }}
                    <br />
                    <b>Jornada:</b>
                    {{ $student->groupYear->group->studyTime->name }}
                </div>
            </td>
            <td class="w-33 ps-1">
                <div class="card p-1">
                    <b>Grado:</b>
                    {{ $student->groupYear->group->studyYear->name }}
                    <br />
                    <b>Jornada:</b>
                    {{ $student->groupYear->group->name }}
                </div>
            </td>
        </tr>
    </table>

    <section class="mt-2">
        <table class="table w-100 border" border="1">
            <thead>

                <tr>
                    <th rowspan="2" class="p-1 f-size-6">FECHA</th>
                    <th rowspan="2" class="p-1 f-size-6 w-20">DESCRIPCIÓN DE LA SITUACIÓN ACADÉMICA Y/O DE CONVIVENCIA</th>
                    <th rowspan="2" class="p-1 f-size-6 w-20">VERSIÓN LIBRE Y/O DESCARGOS</th>
                    <th rowspan="2" class="p-1 f-size-6 w-20">ACUERDOS, COMPROMISOS Y/O ACCIONES CORRECTIVAS</th>
                    <th colspan="3" class="p-1 f-size-6">FIRMAS</th>
                </tr>
                <tr>
                    <th class="p-1 f-size-6">ESTUDIANTE</th>
                    <th class="p-1 f-size-6">REP. LEGAL</th>
                    <th class="p-1 f-size-6">DOCENTE</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="h-30p">&nbsp;</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="h-30p">&nbsp;</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="h-30p">&nbsp;</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="h-30p">&nbsp;</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="h-30p">&nbsp;</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="h-30p">&nbsp;</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="h-30p">&nbsp;</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="h-30p">&nbsp;</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="h-30p">&nbsp;</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="h-30p">&nbsp;</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="h-30p">&nbsp;</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="h-30p">&nbsp;</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="h-30p">&nbsp;</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </section>

</body>

</html>
