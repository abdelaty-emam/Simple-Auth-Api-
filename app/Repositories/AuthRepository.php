<?php

namespace App\Repositories;

use App\Http\Requests\RegisterRequest as registerRequest;
use App\Http\Requests\LoginRequest as loginRequest;
use App\Interfaces\AuthInterface;
use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Api\ApiResponseTrait;

class AuthRepository implements AuthInterface
{
    use ApiResponseTrait;

    public function signUp($registerRequest)
    {
        $data = $registerRequest->validated();
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;
        $cookie = cookie('token', $token, 60 * 24);
        return $this->success('user', new UserResource($user))->withCookie($cookie);
    }

    public function signIn($loginRequest)
    {
        $data = $loginRequest->validated();

        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return $this->error('Email or password is incorrect!', 401);
        }
        // delete old token
        $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        $cookie = cookie('token', $token, 60 * 24); // 1 day

        return $this->success(trans('auth.loginSucess'), new UserResource($user))->withCookie($cookie);
    }

    public function signOut($request)
    {
        $request->user()->currentAccessToken()->delete();
        $cookie = cookie()->forget('token');
        return $this->success('Logged out successfully!')->withCookie($cookie);
    }
}
