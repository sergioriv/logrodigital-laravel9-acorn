<?php

/*
|--------------------------------------------------------------------------
| Validation Language Lines
|--------------------------------------------------------------------------
|
| The following language lines contain the default error messages used by
| the validator class. Some of these rules have multiple versions such
| as the size rules. Feel free to tweak each of these messages here.
|
*/

return [
    'accepted'             => ':attribute debe ser aceptado.',
    'accepted_if'          => ':attribute debe ser aceptado cuando :other sea :value.',
    'active_url'           => ':attribute no es una URL válida.',
    'after'                => ':attribute debe ser una fecha posterior a :date.',
    'after_or_equal'       => ':attribute debe ser una fecha posterior o igual a :date.',
    'alpha'                => ':attribute sólo debe contener letras.',
    'alpha_dash'           => ':attribute sólo debe contener letras, números, guiones y guiones bajos.',
    'alpha_num'            => ':attribute sólo debe contener letras y números.',
    'array'                => ':attribute debe ser un conjunto.',
    'before'               => ':attribute debe ser una fecha anterior a :date.',
    'before_or_equal'      => ':attribute debe ser una fecha anterior o igual a :date.',
    'between'              => [
        'array'   => ':attribute tiene que tener entre :min - :max elementos.',
        'file'    => ':attribute debe pesar entre :min - :max kilobytes.',
        'numeric' => ':attribute tiene que estar entre :min - :max.',
        'string'  => ':attribute tiene que tener entre :min - :max caracteres.',
    ],
    'boolean'              => ':attribute debe tener un valor verdadero o falso.',
    'confirmed'            => 'La confirmación de :attribute no coincide.',
    'current_password'     => 'La contraseña es incorrecta.',
    'date'                 => ':attribute no es una fecha válida.',
    'date_equals'          => ':attribute debe ser una fecha igual a :date.',
    'date_format'          => ':attribute no corresponde al formato :format.',
    'declined'             => ':attribute debe ser rechazado.',
    'declined_if'          => ':attribute debe ser rechazado cuando :other sea :value.',
    'different'            => ':attribute y :other deben ser diferentes.',
    'digits'               => ':attribute debe tener :digits dígitos.',
    'digits_between'       => ':attribute debe tener entre :min y :max dígitos.',
    'dimensions'           => 'Las dimensiones de la imagen :attribute no son válidas.',
    'distinct'             => ':attribute contiene un valor duplicado.',
    'email'                => ':attribute no es un correo válido.',
    'ends_with'            => ':attribute debe finalizar con uno de los siguientes valores: :values',
    'enum'                 => 'El :attribute seleccionado es inválido.',
    'exists'               => 'El :attribute seleccionado es inválido.',
    'file'                 => ':attribute debe ser un archivo.',
    'filled'               => ':attribute es obligatorio.',
    'gt'                   => [
        'array'   => ':attribute debe tener más de :value elementos.',
        'file'    => ':attribute debe tener más de :value kilobytes.',
        'numeric' => ':attribute debe ser mayor que :value.',
        'string'  => ':attribute debe tener más de :value caracteres.',
    ],
    'gte'                  => [
        'array'   => ':attribute debe tener como mínimo :value elementos.',
        'file'    => ':attribute debe tener como mínimo :value kilobytes.',
        'numeric' => ':attribute debe ser como mínimo :value.',
        'string'  => ':attribute debe tener como mínimo :value caracteres.',
    ],
    'image'                => ':attribute debe ser una imagen.',
    'in'                   => ':attribute es inválido.',
    'in_array'             => ':attribute no existe en :other.',
    'integer'              => ':attribute debe ser un número entero.',
    'ip'                   => ':attribute debe ser una dirección IP válida.',
    'ipv4'                 => ':attribute debe ser una dirección IPv4 válida.',
    'ipv6'                 => ':attribute debe ser una dirección IPv6 válida.',
    'json'                 => ':attribute debe ser una cadena JSON válida.',
    'lt'                   => [
        'array'   => ':attribute debe tener menos de :value elementos.',
        'file'    => ':attribute debe tener menos de :value kilobytes.',
        'numeric' => ':attribute debe ser menor que :value.',
        'string'  => ':attribute debe tener menos de :value caracteres.',
    ],
    'lte'                  => [
        'array'   => ':attribute debe tener como máximo :value elementos.',
        'file'    => ':attribute debe tener como máximo :value kilobytes.',
        'numeric' => ':attribute debe ser como máximo :value.',
        'string'  => ':attribute debe tener como máximo :value caracteres.',
    ],
    'mac_address'          => ':attribute debe ser una dirección MAC válida.',
    'max'                  => [
        'array'   => ':attribute no debe tener más de :max elementos.',
        'file'    => 'Su imagen de :attribute no debe ser mayor que :max kilobytes.',
        'numeric' => ':attribute no debe ser mayor que :max.',
        'string'  => ':attribute no debe ser mayor que :max caracteres.',
    ],
    'mimes'                => ':attribute debe ser un archivo con formato: :values.',
    'mimetypes'            => ':attribute debe ser un archivo con formato: :values.',
    'min'                  => [
        'array'   => ':attribute debe tener al menos :min elementos.',
        'file'    => 'El tamaño de :attribute debe ser de al menos :min kilobytes.',
        'numeric' => 'El tamaño de :attribute debe ser de al menos :min.',
        'string'  => ':attribute debe contener al menos :min caracteres.',
    ],
    'multiple_of'          => ':attribute debe ser múltiplo de :value',
    'not_in'               => ':attribute es inválido.',
    'not_regex'            => 'El formato d:attribute no es válido.',
    'numeric'              => ':attribute debe ser numérico.',
    'password'             => 'La contraseña es incorrecta.',
    'present'              => ':attribute debe estar presente.',
    'prohibited'           => ':attribute está prohibido.',
    'prohibited_if'        => ':attribute está prohibido cuando :other es :value.',
    'prohibited_unless'    => ':attribute está prohibido a menos que :other sea :values.',
    'prohibits'            => ':attribute prohibe que :other esté presente.',
    'regex'                => 'El formato de :attribute es inválido.',
    'required'             => ':attribute es obligatorio.',
    'required_array_keys'  => ':attribute debe contener entradas para: :values.',
    'required_if'          => ':attribute es obligatorio cuando :other es :value.',
    'required_unless'      => ':attribute es obligatorio a menos que :other esté en :values.',
    'required_with'        => ':attribute es obligatorio cuando :values está presente.',
    'required_with_all'    => ':attribute es obligatorio cuando :values están presentes.',
    'required_without'     => ':attribute es obligatorio cuando :values no está presente.',
    'required_without_all' => ':attribute es obligatorio cuando ninguno de :values está presente.',
    'same'                 => ':attribute y :other deben coincidir.',
    'size'                 => [
        'array'   => ':attribute debe contener :size elementos.',
        'file'    => 'El tamaño de :attribute debe ser :size kilobytes.',
        'numeric' => 'El tamaño de :attribute debe ser :size.',
        'string'  => ':attribute debe contener :size caracteres.',
    ],
    'starts_with'          => ':attribute debe comenzar con uno de los siguientes valores: :values',
    'string'               => ':attribute debe ser una cadena de caracteres.',
    'timezone'             => ':Attribute debe ser una zona horaria válida.',
    'unique'               => ':attribute ya ha sido registrado.',
    'uploaded'             => 'Subir :attribute ha fallado.',
    'url'                  => ':Attribute debe ser una URL válida.',
    'uuid'                 => ':attribute debe ser un UUID válido.',
    'custom'               => [
        'email'    => [
            'unique' => 'El :attribute ya ha sido registrado.',
        ],
        'password' => [
            'min' => 'La :attribute debe contener más de :min caracteres',
        ],
    ],


    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'email' => 'correo electrónico',
        'password' => 'contraseña',

        'institutional_email' => 'correo electrónico institucional',
        'contact_email' => 'email de contacto',
        'contact_telephone' => 'teléfono de contacto',
        'handbook_coexistence' => 'manual de convivencia',
        'file_upload' => 'el archivo',

        'firstName' => 'primer nombre',
        'secondName' => 'segundo nombre',
        'firstLastName' => 'primer apellido',
        'secondLastName' => 'segundo apellido',
        'telephone' => 'teléfono',
        'document_type' => 'tipo documento',
        'document' => 'documento',
        'expedition_city' => 'ciudad de expedición',
        'number_siblings' => 'número de hermanos',
        'birth_city' => 'ciudad de nacimiento',
        'country' => 'país de origen',
        'birthdate' => 'fecha de nacimiento',
        'gender' => 'genero',
        'residence_city' => 'ciudad de residencia',
        'address' => 'dirección',
        'social_stratum' => 'estrato social',
        'dwelling_type' => 'tipo de vivienda',
        'health_manager' => 'administradora de salud',
        'school_insurance' => 'seguro escolar',
        'disability' => 'discapacidad',
        'disability_certificate' => 'certificado de discapacidad',

        'mother_name' => 'nombre de la madre',
        'mother_email' => 'correo de la madre',
        'mother_document' => 'documento de la madre',
        'mother_expedition_city' => 'ciudad de expedición de la madre',
        'mother_residence_city' => 'ciudad de residencia de la madre',
        'mother_address' => 'dirección de la madre',
        'mother_telephone' => 'teléfono de la madre',
        'mother_cellphone' => 'celular de la madre',
        'mother_birthdate' => 'fecha de nacimiento de la madre',
        'mother_occupation' => 'ocupación de la madre',

        'father_name' => 'nombre del padre',
        'father_email' => 'correo del padre',
        'father_document' => 'documento del padre',
        'father_expedition_city' => 'ciudad de expedición del padre',
        'father_residence_city' => 'ciudad de residencia del padre',
        'father_address' => 'dirección del padre',
        'father_telephone' => 'teléfono del padre',
        'father_cellphone' => 'celular del padre',
        'father_birthdate' => 'fecha de nacimiento del padre',
        'father_occupation' => 'ocupación del padre',

        'tutor_name' => 'nombre del/la tutor/a',
        'tutor_email' => 'correo del/la tutor/a',
        'tutor_document' => 'documento del/la tutor/a',
        'tutor_expedition_city' => 'ciudad de expedición del/la tutor/a',
        'tutor_residence_city' => 'ciudad de residencia del/la tutor/a',
        'tutor_address' => 'dirección del/la tutor/a',
        'tutor_telephone' => 'teléfono del/la tutor/a',
        'tutor_cellphone' => 'celular del/la tutor/a',
        'tutor_birthdate' => 'fecha de nacimiento del/la tutor/a',
        'tutor_occupation' => 'ocupación del/la tutor/a',

        'zone' => 'zona',
        'residence_city' => 'ciudad de residencia',
        'address' => 'dirección',
        'social_stratum' => 'estrato social',
        'dwelling_type' => 'tipo de vivienda',
        'neighborhood' => 'barrio',

        'headquarters' => 'sede',
        'study_time' => 'jornada',
        'study_year' => 'año de estudio',
        'name' => 'nombre',
        'group_director' => 'director de grupo'
    ],

];
