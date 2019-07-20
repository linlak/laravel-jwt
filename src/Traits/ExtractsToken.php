<?php

namespace Linlak\Jwt\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;

trait ExtractsToken
{
    use KeyTrait;
    /**
     * @var String
     */
    protected $tokenString = "";
    /**
     * The header name.
     *
     * @var string
     */
    protected $header = 'authorization';

    /**
     * The header prefix.
     *
     * @var string
     */
    protected $prefix = 'bearer';

    /**
     * Attempt to parse the token from some other possible headers.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return null|string
     */
    protected function fromAltHeaders(Request $request)
    {
        return $request->server->get('HTTP_AUTHORIZATION') ?: $request->server->get('REDIRECT_HTTP_AUTHORIZATION');
    }

    /**
     * Try to parse the token from the request header.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return null|string
     */
    public function parse(Request $request)
    {
        $header = $request->headers->get($this->header) ?: $this->fromAltHeaders($request);

        if ($header && preg_match('/' . $this->prefix . '\s*(\S+)\b/i', $header, $matches)) {
            return $matches[1];
        }

        if ($token = $request->input($this->getKey())) {
            return $token;
        }

        $route = $request->route();

        if (is_callable([$route, 'parameter'])) {
            return $route->parameter($this->key);
        }
    }
    protected function getToken()
    {
        if ($this->tokenString = $this->parse($this->request)) {
            $this->checkExpires();
            $this->parseToken($this->tokenString);
        }
        if (!is_null($this->refreshKey)) {
            if ($user = $this->provider->retrieveById($this->refreshKey->user_id)) {
                if ($this->shouldRefresh) {
                    $this->tk();
                }
                $this->refreshKey->last_seen = Date::now();
                $this->refreshKey->save();

                $this->setUser($user);
            }
        }
    }
    public function checkExpires()
    {
        if (!is_null($this->token)) {
            $exp = $this->token->getClaim('exp', false);
            $now = Carbon::now();
            $then = Carbon::now();
            $then->setTimestamp($exp);
            if (($now > $then) || ($then->diffInSeconds($now) < config('linjwt.refresh_at', 30))) {
                $this->shouldRefresh = true;
            }
        }
    }
    public function shouldRefresh()
    {
        return $this->shouldRefresh;
    }
}
