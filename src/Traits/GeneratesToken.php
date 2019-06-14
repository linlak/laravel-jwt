<?php
namespace Linlak\Jwt\Traits;

use Illuminate\Contracts\Auth\Authenticatable;
use Lcobucci\JWT\Builder;
use Linlak\Jwt\Models\TokenRefreshKey;
use Illuminate\Support\Str;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Parser;

trait GeneratesToken
{
    /**
     * @var Lcobucci\JWT\Token
     */
    private $token;


    protected function newToken()
    {
        // $ref = new TokenRefreshKey();
        // $ref->user_id = $user->getAuthIdentifier();
        // $ref->revoke_key = Str::random(32);
        // $ref->provider = cofig('app.name');
        // $ref->is_rem = false;
        $time = time();
        $siner = new Sha256();
        $builder = new Builder();
        $builder->issuedAt($time)
            ->canOnlyBeUsedAfter($time)
            ->expiresAt($time + config('linjwt.max_age', 3600))
            ->setIssuer(config('app.url', 'http://localhost'))
            ->permittedFor(config('app.url', 'http://localhost'))
            ->set('uid', time())
            ->set('revoke_key', Str::random(32));
        $this->token = $builder->getToken($siner, new Key(config('linjwt.secret', 'testing')));
    }
    public function token()
    {
        if (is_null($this->token)) {
            return Null;
        }
        return $this->token->__toString();
    }
    public function parseToken($token)
    {
        $this->token = (new Parser())->parse((string)$token);
        \dump($this->isValid());
        exit();
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
