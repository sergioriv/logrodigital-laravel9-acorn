<?php

namespace App\Http\Controllers;

class ProviderUser
{

    private static $providers = [

        // 'logro.digital' => 'microsoft', // correo institucional

        'hotmail.com'   => 'microsoft',
        'hotmail.es'    => 'microsoft',
        'outlook.com'   => 'microsoft',
        'outlook.es'    => 'microsoft',
        'live.com'      => 'microsoft',
        'live.es'       => 'microsoft',
        // 'gmail.com'     => 'google',
    ];

    public static function provider_validate($email)
    {
        return isset( self::$providers[ explode('@', $email)[1] ] ) === FALSE
            ? null
            : self::$providers[ explode('@', $email)[1] ];
    }
}
