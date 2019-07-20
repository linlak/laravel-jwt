<?php

namespace Linlak\Jwt\Auth\Guards;

use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Authenticatable;
use Linlak\Jwt\Contracts\Guard;
use Illuminate\Auth\GuardHelpers;
use Linlak\Jwt\Traits\GeneratesToken;
use Linlak\Jwt\Traits\ExtractsToken;
use Illuminate\Support\Facades\Date;

class JwtGuard implements Guard
{
    use GuardHelpers, GeneratesToken, ExtractsToken;

    protected $request;

    public function __construct(UserProvider $provider, Request $request)
    {
        $this->request = $request;
        $this->setProvider($provider);
        $this->user = NULL;
        $this->getToken();
    }

    public function user()
    {
        if (is_null($this->user)) {
            $this->getToken();
        }
        if (!is_null($this->user)) {
            return $this->user;
        }
    }


    /**
     * Validate a user's credentials.
     *
     * @param  array  $credentials
     * @return bool
     */
    public function validate(array $credentials = [])
    {
        if (empty($credentials) || empty($credentials['password']) || (count($credentials) == 1 && !empty($credentials['password']))) {
            return false;
        }
        $user = $this->provider->retrieveByCredentials($credentials);
        if (!is_null($user) && $this->provider->validateCredentials($user, $credentials)) {
            $this->setUser($user);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Attempt to authenticate a user using the given credentials.
     *
     * @param  array  $credentials
     * @param  bool   $remember
     * @return bool
     */
    public function attempt(array $credentials = [], $remember = false)
    {
        if ($this->validate($credentials)) {
            //set token
            $this->newToken();
            return true;
        }
        return false;
    }

    /**
     * Log a user into the application without sessions or cookies.
     *
     * @param  array  $credentials
     * @return bool
     */
    public function once(array $credentials = [])
    {
        return $this->validate($credentials);
    }

    /**
     * Log a user into the application.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  bool  $remember
     * @return void
     */
    public function login(Authenticatable $user, $remember = false)
    {

        $this->setUser($user);
        //setToken
        $this->newToken();
    }

    /**
     * Log the given user ID into the application.
     *
     * @param  mixed  $id
     * @param  bool   $remember
     * @return \Illuminate\Contracts\Auth\Authenticatable
     */
    public function loginUsingId($id, $remember = false)
    {

        $user = $this->provider->retrieveById($id);

        if (!is_null($user)) {
            $this->setUser($user);
            //setToken
            $this->newToken();
            return $this->user();
        }
    }

    /**
     * Log the given user ID into the application without sessions or cookies.
     *
     * @param  mixed  $id
     * @return bool
     */
    public function onceUsingId($id)
    {

        $user = $this->provider->retrieveById($id);

        if (!is_null($user)) {
            $this->setUser($user);
            return true;
        }
        return false;
    }

    /**
     * Determine if the user was authenticated via "remember me" cookie.
     *
     * @return bool
     */
    public function viaRemember()
    { }

    /**
     * Log the user out of the application.
     *
     * @return void
     */
    public function logout()
    {
        //destroy token
        if ($this->check()) {
            $this->refreshKey->delete();
            $this->refreshKey = null;
        }
        $this->user = NULL;
    }
    /**
     * Refresh users token
     */
    public function refreshToken()
    {
        $this->getToken();
    }
    /**
     * Mark token as invalid
     */
    public function invalidate($id)
    { }
}
