<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgetPasswordRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;


class ForgetPasswordController extends Controller
{

	public function forgetPassword(ForgetPasswordRequest $request)
	{
		
			$credentials = request()->validate(['email' => 'required|email']);
	
			Password::sendResetLink($credentials);
	
			return response()->json(["msg" => 'Reset password link sent on your email id.']);
		}
	

	public function resetPassword(Request $request)
	{
			$credentials = request()->validate([
				'email' => 'required|email',
				'token' => 'required|string',
				'password' => 'required|string|min:8'
			]);
	
			$reset_password_status = Password::reset($credentials, function ($user, $password) {
				$user->password = $password;
				$user->save();
			});
	
			if ($reset_password_status == Password::INVALID_TOKEN) {
				return response()->json(["msg" => "Invalid token provided"], 400);
			}
	
			return response()->json(["msg" => "Password has been successfully changed"]);
		}
}
