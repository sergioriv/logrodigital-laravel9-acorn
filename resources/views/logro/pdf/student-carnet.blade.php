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

        .card {
            border: .3px solid #c4c4c4;
            border-radius: 4px;
        }

        .carnet {
            width: 85mm;
            height: 55mm;
            padding: 1mm;
        }

        .t-center {
            text-align: center;
        }

        .left-content {
            width: 42mm;
        }

        .right-content {
            width: 42mm;
        }

        .photo-student {
            position: relative;
            width: 41mm;
            height: 54mm;
        }

        .photo-student img {
            max-width: 41mm;
            height: 54mm;
            border-radius: 3px 0 0 4px;
        }

        .table {
            border-collapse: collapse;
        }

        .w-100 {
            width: 100%;
        }

        .badge {
            position: relative;
            height: 15mm;
        }

        .badge img {
            max-width: 41mm;
            height: 15mm;
        }

        .bold {
            font-weight: 700;
        }

        .mt-2 {
            margin-top: 1.5mm;
        }

        .text-uppercase {
            text-transform: uppercase;
        }


        .f-size-5 {
            font-size: 5px;
        }

        .f-size-7 {
            font-size: 7px;
        }

        .f-size-9 {
            font-size: 9px;
        }

        .f-size-11 {
            font-size: 11px;
        }
        .p-8 {
            padding: 8mm;
        }
        .line {
            border-top: 0.3px solid #373435;
        }
        .table {
            border-collapse: collapse;
            border: 0px;
        }
        .signature {
            height: 12mm;
        }
        .signature img {
            max-width: 45mm;
            height: 11mm;
        }
    </style>
</head>

<body>

    <table class="table" border="0">
        <tr>
            <td>
                <div class="card carnet">
                    <div class="t-center">
                        <table class="table w-100">
                            <tr>
                                <td class="left-content">
                                    <div class="photo-student">
                                        @if (!is_null($student->user->avatar))
                                            <img src="{{ imgBase64($student->user->avatar) }}">
                                        @endif
                                    </div>
                                </td>
                                <td class="right-content t-center">
                                    <div class="badge w-100">
                                        @if ($SCHOOL->badge)
                                            <img src="{{ imgBase64($SCHOOL->badge) }}">
                                        @endif
                                    </div>
                                    <div class="bold text-uppercase f-size-5">{{ $SCHOOL->name }}</div>
                                    <div class="bold mt-2 f-size-9 text-uppercase">
                                        {{ $student->getNames() }}
                                    </div>
                                    <div class="text-uppercase">
                                        {{ $student->getLastNames() }}
                                    </div>
                                    <div class="text-uppercase">
                                        {{ $student->document_type_code . ' - ' . $student->document }}
                                    </div>
                                    <div class="text-uppercase mt-2 f-size-11 bold">
                                        {{ $student->group->name }}
                                    </div>
                                    <div class="text-uppercase bold f-size-5">
                                        {{ $student->headquarters->name }}
                                        |
                                        {{ $student->studyTime->name }}
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </td>
            <td>
                <div class="card carnet">
                    <div class="t-center p-8">

                        <div class="text-uppercase bold f-size-11">ESTUDIANTE</div>

                        <div>&nbsp;</div>
                        <div>&nbsp;</div>

                        <div class="f-size-5">
                            Este documento es personal e intransferible.
                            Este documento lo identifica como estudiante de la Institución <b class="text-uppercase f-size-5">{{ $SCHOOL->name }}</b>.
                            Su titular se obliga a darle el debido uso y guardar comportamiento ético y cívico referidos a la comunidad educativa y a la sociedad en general.</div>

                        <div class="mt-2 f-size-5 bold">Válido durante el año lectivo {{ now()->format('Y') }}</div>

                        <div class="text-center">
                            <div class="signature">
                                @if ($SCHOOL->signature_rector !== null)
                                    <img src="{{ imgBase64($SCHOOL->signature_rector) }}">
                                @endif
                            </div>
                        </div>

                        <div class="line"></div>

                        <div class="mt-2 f-size-5">
                            Tel. {{ $SCHOOL->contact_telephone }}
                            <br />
                            {{ $SCHOOL->address }}
                        </div>

                    </div>
                </div>
            </td>
        </tr>
    </table>





    </section>


</body>

</html>
