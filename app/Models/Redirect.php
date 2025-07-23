<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Redirect extends Model
{
    protected $fillable = ['code', 'redirect_url', 'active'];

    /**
     * Get the redirect URL.
     *
     * @return string
     */
    public function getRedirectUrlAttribute()
    {
        return $this->attributes['redirect_url'];
    }

    /**
     * Check if the redirect is active.
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->active;
    }

    public function logs()
    {
        return $this->hasMany(RedirectLog::class, 'redirect_id');
    }
}
