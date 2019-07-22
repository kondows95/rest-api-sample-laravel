<?php
namespace App;
use \Firebase\JWT\JWT; 
use phpseclib\Crypt\RSA;
use phpseclib\Math\BigInteger;
class CognitoJWT
{
    public static function getPublicKey(string $kid, string $region, string $userPoolId): ?string
    {
        //I guess the jwks rarely change, so get jwks in advance from following URL and save it in ".env".
        //https://cognito-idp.{region}.amazonaws.com/{userPoolId}/.well-known/jwks.json
        //I have no idea when "jwks" is updated.
        //If it is updated regularly, you should cache jwks in DB instead of ".env".
        $storedJwks = [env('AWS_COGNITO_JWKS_1')];
        
        //Create a public key (pem) using jwk corresponding to JWK kid.
        foreach ($storedJwks as $jwks) {
            $pubKey = static::_getPublicKeyByJwks($jwks, $kid);
            if ($pubKey) {
                return $pubKey;
            }
        }
        
        //Get jwks from URL, because the jwks may change.
        $jwksUrl = sprintf('https://cognito-idp.%s.amazonaws.com/%s/.well-known/jwks.json', $region, $userPoolId);
        $ch = curl_init($jwksUrl);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 3,
        ]);
        $jwks = curl_exec($ch);
        if ($jwks) {
            $pubKey = static::_getPublicKeyByJwks($jwks, $kid);
            if ($pubKey) {
                return $pubKey;
            }
        }
        
        return null;
    }
    
    private static function _getPublicKeyByJwks(string $jwks, string $kid): ?string
    {
        $json = json_decode($jwks, false);
        if ($json && isset($json->keys) && is_array($json->keys)) {
            foreach ($json->keys as $jwk) {
                if ($jwk->kid === $kid) {
                    return static::jwkToPem($jwk);
                }
            }
        }
        return null;
    }
    
    public static function jwkToPem(object $jwk): ?string
    {
        if (isset($jwk->e) && isset($jwk->n)) {
            $rsa = new RSA();
            $rsa->loadKey([
                'e' => new BigInteger(JWT::urlsafeB64Decode($jwk->e), 256),
                'n' => new BigInteger(JWT::urlsafeB64Decode($jwk->n),  256)
            ]);
            return $rsa->getPublicKey();
        }
        return null;
    }
    
    public static function getKid(string $jwt): ?string
    {
        $tks = explode('.', $jwt);
        if (count($tks) === 3) {
            $header = JWT::jsonDecode(JWT::urlsafeB64Decode($tks[0]));
            if (isset($header->kid)) {
                return $header->kid;
            }
        }
        return null;
    }
    
    public static function verifyToken(string $jwt, string $region, string $userPoolId): ?object
    {
        $kid = static::getKid($jwt);
        if ($kid) {
            $pubKey = static::getPublicKey($kid, $region, $userPoolId);
            if ($pubKey) {
                return JWT::decode($jwt, $pubKey, array('RS256'));
            }
        }
        return null;
    }
}