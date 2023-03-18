<?php

namespace App\Listeners;

use Illuminate\Auth\Events as LaravelEvents;

class LogActivityListener
{

    public function login(LaravelEvents\Login $event)
    {
        /*
         * Se registrará el día que entraron por ultima vez
         *  */
        $event->user->forceFill([
            'last_access' => now()->format('Y-m-d')
        ])->save();
    }
}
