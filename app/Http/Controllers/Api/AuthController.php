<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;

use App\Interfaces\AuthInterface;


class AuthController extends Controller
{
    protected $authInteface;

    public function __construct(AuthInterface $authInterface)
    {
        $this->authInterface = $authInterface;
    }
    public function register(RegisterRequest $registerRequest)
    {
        return $this->authInterface->signUp($registerRequest);
    }


    public function login(LoginRequest $loginRequest)
    {
        return $this->authInterface->signIn($loginRequest);
    }

    public function logout(Request $request)
    {
        return $this->authInterface->signOut($request);
    }
}
