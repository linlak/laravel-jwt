<?php

namespace Linlak\Jwt\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class TokenRefreshKey extends Model
{
    protected $fillable = ['user_id', 'revoke_key', 'provider', "last_seen"];
    protected $casts = ["last_seen" => "datetime"];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
