<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RedirectLog extends Model
{
    protected $fillable = [
        'redirect_id',
        'redirect_ip',
        'request_ip',
        'user_agent',
        'request_referer',
        'request_query_params',
    ];

    public function redirect()
    {
        return $this->belongsTo(Redirect::class, 'redirect_id');
    }
}
