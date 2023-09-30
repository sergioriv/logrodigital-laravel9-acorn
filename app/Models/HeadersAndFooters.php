<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeadersAndFooters extends Model
{
    use HasFactory;

    protected $table = 'headers_and_footers';
    public $timestamps = false;

    protected $fillable = [
        'header_docs',
        'footer_school_certificate'
    ];

    public function headerDocsHtml() { return nl2br($this->header_docs); }
    public function footerSchoolCertificateHtml() { return nl2br($this->footer_school_certificate); }
}
