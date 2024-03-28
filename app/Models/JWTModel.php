<?php

namespace App\Models;


use DateTimeImmutable;
use Lcobucci\JWT\Encoding\CannotDecodeContent;
use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token\Builder;
use Lcobucci\JWT\Token\InvalidTokenStructure;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Token\UnsupportedHeaderFound;
use Lcobucci\JWT\UnencryptedToken;

class JWTModel
{

    const string JWT_SESSION_COOKIE_NAME = "BasicToken";

    private Builder $tokenBuilder;
    private Sha256 $algorithm;
    private $signingKey;

    public function __construct()
    {
        $this->init();
        return $this;
    }

    private function init()
    {
        $this->tokenBuilder = (new Builder(new JoseEncoder(), ChainedFormatter::default()));
        $this->algorithm = new Sha256();
        $this->signingKey = InMemory::plainText('01234567890123456789012345678901');
    }

    public function getJWT(array $data)
    {
        $now = new DateTimeImmutable();
        $token = $this->tokenBuilder
            ->issuedBy(config('app.url'))
            ->permittedFor(config('app.url'))
            ->relatedTo('secure-auth')
            ->identifiedBy('secure-auth-1')
            ->issuedAt($now)
            ->canOnlyBeUsedAfter($now)
            ->expiresAt($now->modify('+8 hour'))
            ->withClaim('data', $data)
            ->withHeader('author', 'Jean-Marc (vive Dijon !) <jean-marc.picaule@gmail.com>')
            ->getToken($this->algorithm, $this->signingKey);

        return $token->toString();
    }

    public function parseJWT(string $jwt): UnencryptedToken|null
    {
        $parser = new Parser(new JoseEncoder());

        try {
            return $parser->parse($jwt);
        } catch (CannotDecodeContent|InvalidTokenStructure|UnsupportedHeaderFound $e) {
            return null;
        }
    }
}
