<?php

namespace App\Models;

use App\Traits\FormatDate;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class HeadersRemission extends Model
{
    use Uuid;
    use FormatDate;

    protected $fillable = [
        'title',
        'content',
        'orientation_id'
    ];

    public function orientator()
    {
        return $this->belongsTo(Orientation::class, 'orientation_id', 'id');
    }

    public function contentHtml()
    {
        return nl2br($this->content);
    }
}
