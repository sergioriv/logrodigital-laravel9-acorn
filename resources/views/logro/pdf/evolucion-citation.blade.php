<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $tracking->student->getFullName() }}</title>
    <style>
        * {
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            font-size: 10px;
            color: #373435;
        }

        @page {
            margin: 5em;
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
            margin-top: 160px;
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
        .p-2 {
            padding: 5px;
        }
        .p-3 {
            padding: 7.5px !important;
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

        .fz-8 {
            font-size: 8;
        }

        .fz-9 {
            font-size: 9;
        }
    </style>
</head>

<body>


    <table class="table header" border="0">
        @if ($SCHOOL->badge)
            <tr>
                <td class="text-center align-sub">
                    <div class="h-70p">
                        <img class="badge" src="{{ config('app.url') . '/' . $SCHOOL->badge }}" alt="badge">
                    </div>
                </td>
            </tr>
        @endif
        <tr>
            <td class="t-center p-se-1">
                <p class="bold">
                    {{ $SCHOOL->name ?? null }}
                </p>
                <p class="fz-8">
                    {!! \App\Models\HeadersAndFooters::first()->headerDocsHtml() !!}
                    @if (!is_null($SCHOOL->dane))
                        <br />
                        DANE {{ $SCHOOL->dane }}
                    @endif
                </p>
            </td>
        </tr>
    </table>

    <section class="content pt-6">
        <div class="text-center bold">
            EVOLUCIÓN ASESORIA {{ $tracking->dateFull() }}
        </div>

        <div class="mt-10">
            <table class="table w-100">
                <tr>
                    <td  class="p-1">
                        <div class="label">Asistencia</div>
                        <div class="form-control p-3">{{ __($tracking->attendance ?? '--') }}</div>
                    </td>
                    <td  class="p-1">
                        <div class="label">Tipo de asesoría</div>
                        <div class="form-control p-3">{{ __($tracking->type_advice ?? '--') }}</div>
                    </td>
                </tr>
            </table>
            <table class="table w-100 mt-2">
                <tr>
                    <td colspan="2" class="p-1">
                        <div class="label">Evolución</div>
                        <div class="form-control p-3">{{ $tracking->evolution }}</div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="mt-6">
            <table class="table w-100">
                <tr>
                    <td class="t-center w-50">
                        <div class="signature"></div>
                        <div class="signature_name bold">
                            {{ $tracking->creator?->getFullName() }}
                        </div>
                        <div class="fz-8">DOCENTE ORIENTADORA</div>
                        <div class="fz-8">Psicóloga R P - 114277</div>
                        <div class="fz-8">{{ $tracking->creator?->institutional_email }}</div>
                    </td>
                </tr>
            </table>
        </div>
    </section>

    <section class="footer fz-8">
        Carrera 22 No. 4 - 44 Sur Barrio El Sociego - Madrid - Cundinamarca
        <br />
        Tel. 6018282027
        <br />
        www.ietecnologico-madrid-cundinamarca.edu.co - admintecnologico@ietecnologico-madrid.edu.co
    </section>


</body>

</html>
