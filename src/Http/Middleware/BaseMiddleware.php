<?php

namespace Linlak\Jwt\Http\Middleware;

use Illuminate\Http\Response;

class BaseMiddleware
{
    /**
     * Set the authentication header.
     *
     * @param  \Illuminate\Http\Response|\Illuminate\Http\JsonResponse  $response
     * @param  string|null  $token
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    protected function setAuthenticationHeader($response, $token = null)
    {
        if ($response instanceof Response) {
            // $token = $token ?: $this->auth->refresh();
            // $response->headers->set('Authorization', 'Bearer ' . $token);
            $response->headers->set('Fromlin', 'it works');
        }
        return $response;
    }
}
