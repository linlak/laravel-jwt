<?php

namespace Linlak\Jwt\Traits;

use App\User;

trait DeletesTokens
{
    public function crlTokens($id)
    {
        $user = User::with('token_keys')->find($id);
        if (!is_null($user)) {
            if ($user->token_keys->count() > 0) {
                foreach ($user->token_keys as $token) {
                    $token->delete();
                }
            }
        }
        return true;
    }
}
