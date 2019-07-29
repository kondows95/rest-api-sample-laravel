<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function autheduser_returns_jwt_claims()
    {
        $time = time();
        $res = $this->withHeaders($this->getAuthHeader($time))->json('GET', '/api/autheduser');
        $res->assertStatus(200); 
        $json = $res->json();
        $res->assertExactJson([
            'sub' => '9409a930-6094-42be-b719-abcdef123456',
            'event_id' => '712da547-a679-42b1-a53d-abcdef123456',
            'token_use' => 'access',
            'scope' => 'aws.cognito.signin.user.admin',
            'auth_time' => $time,
            'iss' => 'https://cognito-idp.ap-northeast-1.amazonaws.com/ap-northeast-1_abcdef123',
            'iat' => $time,
            'exp' => $time+600,
            'jti' => 'cdbe2aa6-5c68-44f5-b602-abcdef123456',
            'client_id' => 'abcdef1234567abcdef1234567',
            'username' => '9409a930-6094-42be-b719-abcdef123456',
        ]);
    }
}
