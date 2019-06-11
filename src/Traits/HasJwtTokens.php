<?php
namespace Linlak\Jwt\Traits;

use Linlak\Jwt\Models\TokenRefreshKey;

trait HasJwtTokens
{
    public function tokenKeys()
    {
        return $this->hasMany(TokenRefreshKey::class);
    }
}
