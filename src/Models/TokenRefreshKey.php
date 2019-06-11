<?php

namespace Linlak\Jwt\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class TokenRefreshKey extends Model
{
    protected $fillable = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
