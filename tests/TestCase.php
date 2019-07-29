<?php

namespace Tests;

use App\Exceptions\Handler;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

use \Firebase\JWT\JWT; 
use App\Models\PublicKey;


abstract class TestCase extends BaseTestCase
{
    const MYSQL_DATE_FORMAT = 'Y-m-d H:i:s';
    
    use CreatesApplication;
    
    protected function setUp(): void
    {
        /**
         * This disables the exception handling to display the stacktrace on the console
         * the same way as it shown on the browser
         */
        parent::setUp();
        $this->disableExceptionHandling();
    }

    protected function disableExceptionHandling()
    {
        $this->app->instance(ExceptionHandler::class, new class extends Handler {
            public function __construct() {}

            public function report(\Exception $e)
            {
                // no-op
            }

            public function render($request, \Exception $e) {
                throw $e;
            }
        });
    }
    
    protected static function toMySqlDateFromTime($time)
    {
        return date(self::MYSQL_DATE_FORMAT,$time);
    }
    
    protected static function toMySqlDateFromJson($strJsonDate)
    {
        return self::toMySqlDateFromTime(strtotime($strJsonDate));
    }
    
    function getDummyDecodedJwt($time)
    {
        $header = [
            "kid" => 'testKid0123456789=',
            "alg" => "RS256"
        ];
        
        $payload = [
            "sub" => "9409a930-6094-42be-b719-abcdef123456",
            "event_id" => "712da547-a679-42b1-a53d-abcdef123456",
            "token_use" => "access",
            "scope" => "aws.cognito.signin.user.admin",
            "auth_time" => $time,
            "iss" => "https://cognito-idp.ap-northeast-1.amazonaws.com/ap-northeast-1_abcdef123",
            "iat" => $time,
            "exp" => $time+600,
            "jti" => "cdbe2aa6-5c68-44f5-b602-abcdef123456",
            "client_id" => "abcdef1234567abcdef1234567",
            "username" => "9409a930-6094-42be-b719-abcdef123456"
        ];
        
        return [$header, $payload];
    }
    
    function getAuthHeader($time=null)
    {
        if (is_null($time)) {
            $time = time();
        }
        
        //create key pair.
        $key = openssl_pkey_new();
        
        //create private key.
        $passphrase = 'passphrase!';
        openssl_pkey_export($key, $privateKey, $passphrase);
        $privateKeyId = openssl_get_privatekey($privateKey, $passphrase);
        
        //create public key.
        $keyDetails = openssl_pkey_get_details($key);
        $publicKey = $keyDetails['key'];
        
        //get payload.
        list($header, $payload) = $this->getDummyDecodedJwt($time);
        
        //insert DB tabale row.
        PublicKey::create(['kid' => $header['kid'], 'public_key' => $publicKey]);
        
        //create JWT
        $jwt =  JWT::encode($payload, $privateKeyId, 'RS256', null, $header);
        
        //return header
        return [
            'Authorization' => 'Bearer '. $jwt, 
            'Accept' => 'application/json'
        ];
    }
}
