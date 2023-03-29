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
            font-size: 7px;
            color: #373435;
        }

        @page {
            margin: 3em;
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
    </style>
</head>

<body>


    <table class="table w-100" border="0">
        <tr>
            <td class="h-70p w-70p text-center align-sub">
                @if ($SCHOOL->badge)
                    <img class="badge" src="{{ $SCHOOL->badge }}" alt="badge">
                @endif
            </td>
            <td class="t-center p-se-1">
                <p class="t-title bold">
                    {{ $SCHOOL->name ?? null }}
                </p>
                <p>
                    REMISIÓN ORIENTACIÓN ESCOLAR - {{ $date }}
                </p>
                <p>
                    Creado mediante acuerdo No. 033 de Octubre de 1996 Resolución de Reconocimiento No.
                    4657 de Noviembre de 2003, Resolución No. 006319 de Noviembre 17 de 2006, Resolución No. 0011778 de
                    Diciembre de 2007 Resolución No. 005850 del 10 de Julio de 2009.
                    @if (!is_null($SCHOOL->dane))
                    <br />
                    DANE {{ $SCHOOL->dane }}
                    @endif
                </p>
            </td>
            <td class="h-70p w-70p align-sub text-center"></td>
        </tr>
    </table>

    <section class="p-1 text-center">
        <div class="bold">
            ENTIDAD A LA QUE SE REMITE: {{ $tracking->entity_remit }}
        </div>
    </section>

    @if ( ! is_null($tracking->header_remit) )
        <section class="p-1 mt-1">
            {{ $tracking?->headerRemit->content }}
        </section>
    @endif

    <section class="card mt-1">
        <div class="card-header">Datos del Estudiante</div>
        <div class="card-content">
            <table class="table w-100">
                <tr>
                    <td colspan="4" class="p-1">
                        <div class="label">Sede</div>
                        <div class="form-control">{{ $tracking->student->headquarters->name ?? '' }}</div>
                    </td>
                    <td colspan="4" class="p-1">
                        <div class="label">Jornada</div>
                        <div class="form-control">{{ $tracking->student->studyTime->name ?? '' }}</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="p-1">
                        <div class="label">Primer nombre</div>
                        <div class="form-control">{{ $tracking->student->first_name }}</div>
                    </td>
                    <td colspan="2" class="p-1">
                        <div class="label">Segundo nombre</div>
                        <div class="form-control">{{ $tracking->student->second_name }}</div>
                    </td>
                    <td colspan="2" class="p-1">
                        <div class="label">Primer apellido</div>
                        <div class="form-control">{{ $tracking->student->first_last_name }}</div>
                    </td>
                    <td colspan="2" class="p-1">
                        <div class="label">Segundo apellido</div>
                        <div class="form-control">{{ $tracking->student->second_last_name }}</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="p-1">
                        <div class="label">Correo electrónico institucional</div>
                        <div class="form-control">{{ $tracking->student->institutional_email }}</div>
                    </td>
                    <td colspan="1" class="p-1">
                        <div class="label">Teléfono</div>
                        <div class="form-control">{{ $tracking->student->telephone }}</div>
                    </td>
                    <td colspan="2" class="p-1">
                        <div class="label">Tipo de Documento</div>
                        <div class="form-control">{{ $tracking->student->document_type_code }}</div>
                    </td>
                    <td colspan="2" class="p-1">
                        <div class="label">Número de Documento</div>
                        <div class="form-control">{{ $tracking->student->document }}</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="p-1">
                        <div class="label">Ciudad de expedición</div>
                        <div class="form-control">
                            @if ($tracking->student->expedition_city_id != null)
                                {{ $tracking->student->expeditionCity->department->name . ' | ' . $tracking->student->expeditionCity->name }}
                            @else
                                --
                            @endif
                        </div>
                    </td>
                    <td colspan="2" class="p-1">
                        <div class="label">País de origen</div>
                        <div class="form-control">{{ $tracking->student->country->name ?? '' }}</div>
                    </td>
                    <td colspan="2" class="p-1">
                        <div class="label">Ciudad de nacimiento</div>
                        <div class="form-control">
                            @if ($tracking->student->country_id == $nationalCountry->id)
                                @if ($tracking->student->birth_city_id != null)
                                    {{ $tracking->student->birthCity->department->name . ' | ' . $tracking->student->birthCity->name }}
                                @else
                                    --
                                @endif
                            @else
                                --
                            @endif
                        </div>
                    </td>
                    <td class="p-1">
                        <div class="label">Fecha nacimiento</div>
                        <div class="form-control">{{ $tracking->student->birthdate }}</div>
                    </td>
                    <td class="p-1">
                        <div class="label">Edad</div>
                        <div class="form-control">{{ $tracking->student->age() }} años</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="p-1">
                        <div class="label">Ciudad de residencia</div>
                        <div class="form-control">
                            @if ($tracking->student->residence_city_id != null)
                                {{ $tracking->student->residenceCity->department->name . ' | ' . $tracking->student->residenceCity->name }}
                            @else
                                --
                            @endif
                        </div>
                    </td>
                    <td class="p-1">
                        <div class="label">Zona</div>
                        <div class="form-control">{{ __($tracking->student->zone ?? '--') }}</div>
                    </td>
                    <td colspan="4" class="p-1">
                        <div class="label">Dirección</div>
                        <div class="form-control">{{ $tracking->student->address ?? '--' }}</div>
                    </td>
                    <td>
                        <div class="label">Estrato social</div>
                        <div class="form-control">{{ $tracking->student->social_stratum ?? '--' }}</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="5" class="p-1">
                        <div class="label">Barrio</div>
                        <div class="form-control">{{ $tracking->student->neighborhood ?? '--' }}</div>
                    </td>
                    <td class="p-1">
                        <div class="label">No. de hermanos</div>
                        <div class="form-control">{{ $tracking->student->number_siblings ?? '--' }}</div>
                    </td>
                    <td class="p-1">
                        <div class="label">Género</div>
                        <div class="form-control">{{ $tracking->student?->gender->name ?? '' }}</div>
                    </td>
                    <td class="p-1">
                        <div class="label">RH</div>
                        <div class="form-control">{{ $tracking->student?->rh->name ?? '' }}</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="4" class="p-1">
                        <div class="label">Administradora de salud</div>
                        <div class="form-control">{{ $tracking->student?->healthManager->name ?? '--' }}</div>
                    </td>
                    <td class="p-1">
                        <div class="label">Sisben</div>
                        <div class="form-control">{{ $tracking->student->sisben->name ?? '--' }}</div>
                    </td>
                    <td colspan="3" class="p-1">
                        <div class="label">Discapacidad</div>
                        <div class="form-control">{{ __($tracking->student?->disability->name ?? '--') }}</div>
                    </td>
                </tr>
            </table>
        </div>
    </section>

    <section class="card mt-1">
        <div class="card-header">Datos del acudiente</div>
        @if ( is_null($tracking->student->person_charge) )
        <div class="card-content">
            <div class="p-1">Sin registro de acudiente</div>
        </div>
        @else
        <div class="card-content">
            <table class="table w-100">
                <tr>
                    <td colspan="4" class="p-1">
                        <div class="label">Nombre completo</div>
                        <div class="form-control">{{ $tracking->student->myTutorIs->name ?? '--' }}</div>
                    </td>
                    <td colspan="3" class="p-1">
                        <div class="label">Correo electrónico</div>
                        <div class="form-control">{{ $tracking->student->myTutorIs->email ?? '--' }}</div>
                    </td>
                    <td class="p-1">
                        <div class="label">Teléfono</div>
                        <div class="form-control">{{ $tracking->student->myTutorIs->telephone ?? '--' }}</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="p-1">
                        <div class="label">Ciudad de residencia</div>
                        <div class="form-control">
                            @if ($tracking->student->myTutorIs != null && $tracking->student->myTutorIs->residence_city_id != null)
                                {{ $tracking->student->myTutorIs->residenceCity->department->name . ' | ' . $tracking->student->myTutorIs->residenceCity->name }}
                            @else
                                --
                            @endif
                        </div>
                    </td>
                    <td colspan="4" class="p-1">
                        <div class="label">Dirección</div>
                        <div class="form-control">{{ $tracking->student->myTutorIs->address ?? '--' }}</div>
                    </td>
                    <td class="p-1">
                        <div class="label">Celular</div>
                        <div class="form-control">{{ $tracking->student->myTutorIs->cellphone ?? '--' }}</div>
                    </td>
                </tr>
            </table>
        </div>
        @endif
    </section>

    @if ( ! is_null($tracking->risk_or_vulnerabilities) )
        <section class="card mt-1">
            <div class="card-header">Riegos o vulnerabilidades del estudiante</div>
            <div class="card-content">
                <div class="p-1">{{ nl2br($tracking->risk_or_vulnerabilities) }}</div>
            </div>
        </section>
    @endif

    <section class="card mt-1">
        <div class="card-header">{{ __('Reason for remit') }}</div>
        <div class="card-content">
            <div class="p-1">
                {{ nl2br($tracking->reason_entity) }}
            </div>
        </div>
    </section>

    @if ( ! is_null($tracking->orientation_intervention) )
    <section class="card mt-1">
        <div class="card-header">{{ __('Orientation Intervention') }}</div>
        <div class="card-content">
            <div class="p-1">
                {{ nl2br($tracking->orientation_intervention) }}
            </div>
        </div>
    </section>
    @endif

    <section class="card mt-4">
        <table class="table w-100">
            <tr>
                <td class="t-center w-50">
                    <div class="signature"></div>
                    <div class="signature_name">
                        {{ $SCHOOL->rector_name }}
                    </div>
                    <div>RECTOR</div>
                </td>
                <td class="t-center w-50">
                    <div class="signature"></div>
                    <div class="signature_name">
                        {{ $tracking->creator->getFullName() }}
                    </div>
                    <div>DOCENTE ORIENTADORA</div>
                </td>
            </tr>
            <tr>
                <td class="t-center w-50"></td>
                <td class="t-center w-50">
                    <div>Psicóloga R P - 114277</div>
                    <div>{{ $tracking->creator->email }}</div>
                </td>
            </tr>
        </table>
    </section>


</body>

</html>
