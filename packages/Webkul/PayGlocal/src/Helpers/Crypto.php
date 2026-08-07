<?php

namespace Webkul\PayGlocal\Helpers;

use Jose\Component\Core\AlgorithmManager;
use Jose\Component\Encryption\Algorithm\ContentEncryption\A128CBCHS256;
use Jose\Component\Encryption\Algorithm\KeyEncryption\RSAOAEP256;
use Jose\Component\Encryption\JWEBuilder;
use Jose\Component\Encryption\Serializer\CompactSerializer as JWESerializer;
use Jose\Component\KeyManagement\JWKFactory;
use Jose\Component\Signature\Algorithm\RS256;
use Jose\Component\Signature\JWSBuilder;
use Jose\Component\Signature\JWSVerifier;
use Jose\Component\Signature\Serializer\CompactSerializer as JWSSerializer;

class Crypto
{
    /**
     * PayGlocal expects `exp` to be a relative lifetime in milliseconds
     */
    const TOKEN_EXPIRY = 300000;

    /**
     * Digest algorithm named in the JWS payload.
     */
    const DIGEST_ALGORITHM = 'SHA-256';

    /**
     * Encrypt the payload into a JWE for use as the request body.
     */
    public function encrypt(array $payload): string
    {
        $jwk = JWKFactory::createFromKey($this->getConfigData('payglocal_public_key'), null, [
            'kid' => $this->getConfigData('public_key_id'),
            'use' => 'enc',
        ]);

        $jweBuilder = new JWEBuilder(new AlgorithmManager([
            new RSAOAEP256,
            new A128CBCHS256,
        ]));

        $jwe = $jweBuilder
            ->create()
            ->withPayload(json_encode($payload))
            ->withSharedProtectedHeader([
                'alg' => 'RSA-OAEP-256',
                'enc' => 'A128CBC-HS256',
                'kid' => $this->getConfigData('public_key_id'),
                'iat' => $this->timestamp(),
                'exp' => self::TOKEN_EXPIRY,
                'issued-by' => $this->getConfigData('merchant_id'),
            ])
            ->addRecipient($jwk)
            ->build();

        return (new JWESerializer)->serialize($jwe, 0);
    }

    /**
     * Sign a digest of the given JWE, producing the `x-gl-token-external` header value.
     */
    public function sign(string $jwe): string
    {
        $merchantId = $this->getConfigData('merchant_id');

        $privateKeyId = $this->getConfigData('private_key_id');

        $jwk = JWKFactory::createFromKey($this->getConfigData('merchant_private_key'), null, [
            'kid' => $privateKeyId,
            'use' => 'sig',
        ]);

        $jwsBuilder = new JWSBuilder(new AlgorithmManager([new RS256]));

        $jws = $jwsBuilder
            ->create()
            ->withPayload(json_encode([
                'digest' => base64_encode(hash('sha256', $jwe, true)),
                'digestAlgorithm' => self::DIGEST_ALGORITHM,
                'iat' => $this->timestamp(),
                'exp' => self::TOKEN_EXPIRY,
            ]))
            ->addSignature($jwk, [
                'alg' => 'RS256',
                'kid' => $privateKeyId,
                'x-gl-merchantId' => $merchantId,
                'issued-by' => $merchantId,
                'is-digested' => 'true',
                'x-gl-enc' => 'true',
            ])
            ->build();

        return (new JWSSerializer)->serialize($jws, 0);
    }

    /**
     * Verify a JWS issued by PayGlocal and return its claims.
     *
     * Returns null when the token is malformed or the signature does not match.
     */
    public function verify(string $token): ?array
    {
        try {
            $jwk = JWKFactory::createFromKey($this->getConfigData('payglocal_public_key'), null, [
                'use' => 'sig',
            ]);

            $jws = (new JWSSerializer)->unserialize($token);

            $jwsVerifier = new JWSVerifier(new AlgorithmManager([new RS256]));

            if (! $jwsVerifier->verifyWithKey($jws, $jwk, 0)) {
                return null;
            }

            return json_decode($jws->getPayload(), true);
        } catch (\Exception $e) {
            report($e);

            return null;
        }
    }

    /**
     * Read the claims of a JWS without checking who signed it.
     */
    public function decode(string $token): ?array
    {
        try {
            $jws = (new JWSSerializer)->unserialize($token);

            return json_decode($jws->getPayload(), true);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Retrieve information from the payment configuration.
     */
    protected function getConfigData(string $field): mixed
    {
        return core()->getConfigData('sales.payment_methods.payglocal.'.$field);
    }

    /**
     * Current epoch in milliseconds, as a string.
     */
    protected function timestamp(): string
    {
        return (string) (int) round(microtime(true) * 1000);
    }
}
