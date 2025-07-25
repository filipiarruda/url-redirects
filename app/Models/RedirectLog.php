<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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



    public static function topReferrers($redirectId)
    {
        return self::select('request_referer', DB::raw('count(*) as total'))
            ->where('redirect_id', $redirectId)
            ->whereNotNull('request_referer')
            ->groupBy('request_referer')
            ->orderByDesc('total')
            ->take(5)
            ->get();
    }

    public static function lastDaysAccess($redirectId)
    {
        $logs = self::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('count(*) as total'),
            DB::raw('count(distinct request_ip) as unique_ips')
        )
        ->where('redirect_id', '=', $redirectId)
        ->where('created_at', '>=', now()->subDays(10))
        ->groupBy('date')
        ->orderBy('date', 'desc')
        ->get();

        return $logs;

    }
}
