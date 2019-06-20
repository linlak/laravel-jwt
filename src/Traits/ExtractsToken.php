<?php
namespace Linlak\Jwt\Traits;

use Illuminate\Http\Request;

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
    public function checkExpires()
    { }
    public function shouldRefresh()
    {
        return $this->shouldRefresh;
    }
}
