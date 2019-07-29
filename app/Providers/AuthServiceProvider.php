<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\CognitoJWT;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Auth::viaRequest('cognito', function ($request) {
            $jwt = $request->bearerToken();
            $region = env('AWS_REGION', '');//must be string
            $userPoolId = env('AWS_COGNITO_USER_POOL_ID', '');//must be string
            if ($jwt) {
                return CognitoJWT::verifyToken($jwt, $region, $userPoolId);
            }
            return null;
        });
    }
}
