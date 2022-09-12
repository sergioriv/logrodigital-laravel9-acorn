<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pdf</title>
    <style>
        * {
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            font-size: 7px;
            color: #373435;
        }

        @page {
            margin: 1em;
        }

        .w-70p {
            width: 70px;
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
            margin-top: 5px;
        }

        .mt-2 {
            margin-top: 10px;
        }

        .form-control {
            border: .3px solid #c4c4c4;
            border-radius: 2px;
            padding: 2.3px 4px;
            height: 9px;
        }

        .label {
            margin-bottom: 1.5px;
        }

        .badge {
            width: 70px;
            height: 70px;
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
        }
    </style>
</head>

<body>


    <table class="table w-100" border="0">
        <tr>
            <td class="h-70p w-70p">
                @if ($school->badge)
                    <img class="badge" src="{{ $school->badge }}" alt="badge">
                @endif
            </td>
            <td class="t-center p-se-1">
                <p class="t-title bold">
                    {{ $school->name ?? null }}
                </p>
                <p>
                    HOJA DE MATRÍCULA - FECHA: {{ $date }}
                </p>
                <p>
                    Creado mediante acuerdo No. 033 de Octubre de 1996 resolución de Reconocimiento No.
                    4657 de Noviembre de 2003, resolución No. 006319 de Noviembre 17 de 2006, resolución No. 0011778 de
                    Diciembre de 2007 resolución No. 005850 del 10 de Julio de 2009.
                </p>
            </td>
            <td class="h-70p w-70p"></td>
        </tr>
    </table>

    <section class="p-1">
        <table class="table w-100 mt-2" border="0">
            <tr>
                <td class="p-1 p-tb-0">
                    <div class="form-control">
                        <b>Fecha de Matrícula:</b>
                        {{ $student->enrolled_date }}
                    </div>
                </td>
                <td class="p-1 p-tb-0">
                    <div class="form-control">
                        <b>Grado:</b>
                        {{ $student->studyYear->name }}
                    </div>
                </td>
                <td class="p-1 p-tb-0">
                    <div class="form-control">
                        <b>Sede:</b>
                        {{ $student->headquarters->name }}
                    </div>
                </td>
            </tr>
        </table>
    </section>

    <section class="card mt-1">
        <div class="card-header">Datos del Estudiante</div>
        <div class="card-content">
            <table class="table w-100">
                <tr>
                    <td colspan="2" class="p-1">
                        <div class="label">Primer nombre</div>
                        <div class="form-control">{{ $student->first_name }}</div>
                    </td>
                    <td colspan="2" class="p-1">
                        <div class="label">Segundo nombre</div>
                        <div class="form-control">{{ $student->second_name }}</div>
                    </td>
                    <td colspan="2" class="p-1">
                        <div class="label">Primer apellido</div>
                        <div class="form-control">{{ $student->first_last_name }}</div>
                    </td>
                    <td colspan="2" class="p-1">
                        <div class="label">Segundo apellido</div>
                        <div class="form-control">{{ $student->second_last_name }}</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="p-1">
                        <div class="label">Correo electrónico institucional</div>
                        <div class="form-control">{{ $student->institutional_email }}</div>
                    </td>
                    <td colspan="1" class="p-1">
                        <div class="label">Teléfono</div>
                        <div class="form-control">{{ $student->telephone }}</div>
                    </td>
                    <td colspan="2" class="p-1">
                        <div class="label">Tipo de Documento</div>
                        <div class="form-control">{{ $student->document_type_code }}</div>
                    </td>
                    <td colspan="2" class="p-1">
                        <div class="label">Número de Documento</div>
                        <div class="form-control">{{ $student->document }}</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="p-1">
                        <div class="label">Ciudad de expedición</div>
                        <div class="form-control">
                            {{ $student->expeditionCity->department->name . ' | ' . $student->expeditionCity->name }}
                        </div>
                    </td>
                    <td colspan="2" class="p-1">
                        <div class="label">País de origen</div>
                        <div class="form-control">{{ $student->country->name }}</div>
                    </td>
                    <td colspan="2" class="p-1">
                        <div class="label">Ciudad de nacimiento</div>
                        <div class="form-control">{{ $student->birthCity->name ?? '--' }}</div>
                    </td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        </div>
    </section>

</body>

</html>
