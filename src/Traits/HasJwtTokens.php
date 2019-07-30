<?php

namespace Linlak\Jwt\Traits;

use Linlak\Jwt\Models\TokenRefreshKey;

trait HasJwtTokens
{

    public function token_keys()
    {
        return $this->hasMany(TokenRefreshKey::class);
    }
}
