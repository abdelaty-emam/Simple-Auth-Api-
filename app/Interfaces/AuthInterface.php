<?php

namespace App\Interfaces;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;

interface AuthInterface
{

    public function signUp($request);



    public function signIn($request);


    public function signOut($request);
}
