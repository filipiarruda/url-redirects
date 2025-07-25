<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class RedirectLog extends Model
{
    protected $fillable = [
        'redirect_id',
        'request_ip',
        'user_agent',
        'request_referer',
        'request_query_params',
    ];
    protected $primaryKey = 'id';

    public function redirect()
    {
        return $this->belongsTo(Redirect::class, 'redirect_id');
    }


}
