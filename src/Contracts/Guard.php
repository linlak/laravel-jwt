<?php
namespace Linlak\Jwt\Contracts;

use Illuminate\Contracts\Auth\StatefulGuard;

interface Guard extends StatefulGuard
{
    /**
     * Refresh users token
     */
    public function refreshToken();
    /**
     * Mark token as invalid
     */
    public function invalidate($id);
}
