<?php

namespace Linlak\Jwt\Traits;

use Illuminate\Contracts\Auth\Authenticatable;
use Lcobucci\JWT\Builder;
use Linlak\Jwt\Models\TokenRefreshKey;
use Illuminate\Support\Str;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Parser;
use Illuminate\Support\Facades\Date;

trait GeneratesToken
{
    /**
     * @var Lcobucci\JWT\Token
     */
    private $token;
    protected $refreshKey;
    protected $shouldRefresh = false;

    protected function newToken()
    {
        $ref = new TokenRefreshKey();
        $ref->user_id = $this->user()->getAuthIdentifier();
        $ref->revoke_key = Str::random(32);
        $ref->provider = config('app.name');
        $ref->is_rem = false;
        $ref->last_seen = Date::now();
        if ($ref->save()) {
            $ref->refresh();
            $this->refreshKey = $ref;
            $this->tk();
        }
    }
    protected function tk()
    {
        $time = time();
        $siner = new Sha256();
        $builder = new Builder();
        $builder->issuedAt($time)
            ->canOnlyBeUsedAfter($time)
            ->expiresAt($time + config('linjwt.max_age', 3600))
            ->setIssuer(config('app.url', 'http://localhost'))
            ->permittedFor(config('app.url', 'http://localhost'))
            ->set('uid', $this->refreshKey->id)
            ->set('revoke_key', $this->refreshKey->revoke_key);
        $this->token = $builder->getToken($siner, new Key(config('linjwt.secret', 'testing')));
    }
    public function token()
    {
        if (is_null($this->token)) {
            return Null;
        }
        $data = [
            'accessToken' => $this->token->__toString(),
            "user_id" => encrypt($this->refreshKey->user_id),
            "token_id" => encrypt($this->refreshKey->id),
            "refresh_token" => encrypt($this->refreshKey->revoke_key)
        ];
        return $data;
    }
    public function parseToken($token)
    {
        $this->token = (new Parser())->parse((string) $token);
        if ($this->isValid()) {
            if (!$this->token->isExpired() || $this->shouldRefresh) {
                $this->refreshKey = TokenRefreshKey::where('id', $this->token->getClaim('uid'))->where('revoke_key', $this->token->getClaim('revoke_key'))->get()->first();
            }
        }
    }
    protected function isValid()
    {
        if (!is_null($this->token)) {
            $siner = new Sha256();
            return $this->token->verify($siner, new Key(config('linjwt.secret', 'testing')));
        }
        return false;
    }
}
