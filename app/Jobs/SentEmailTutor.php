<?php

namespace App\Jobs;

use App\Http\Controllers\Mail\SmtpMail;
use App\Models\SentEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SentEmailTutor implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $subject;
    private $message;

    private $tutorName;
    private $tutorEmail;

    public function __construct($subject, $message, $tutorName, $tutorEmail)
    {
        $this->subject = $subject;
        $this->message = $message;
        $this->tutorName = $tutorName;
        $this->tutorEmail = $tutorEmail;
    }

    public function handle()
    {
        // enviar un correo cada 2 segundos
        usleep(2000000);
        // SmtpMail::init()->mailToTutor($this->subject, $this->message, $this->tutorName, $this->tutorEmail);
    }
}
