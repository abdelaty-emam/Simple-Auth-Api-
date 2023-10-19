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
		$request->validate(['email' => 'required|email']);

		$status = Password::sendResetLink(
			$request->only('email')
		);

		if ($status === Password::RESET_LINK_SENT) {
			return response()->json(['message' => __($status)], 200);
		} else {
			throw ValidationException::withMessages([
				'email' => __($status)
			]);
		}
	}

	public function resetPassword(Request $request)
	{
		$request->validate([
			'token' => 'required',
			'email' => 'required|email',
			'password' => 'required|min:8',
		]);

		//         $validator = \Validator::make($request->all(), [
		//             'token' => 'required',
		// 			// 'email' => 'required|email',
		// 			'password' => 'required|min:8',
		//         ]);

		//         // Check validation failure
		//         if ($validator->fails()) {
		// dd('aa');
		//  }

		//         // Check validation success
		//         if ($validator->passes()) {
		//             dd('paaa');
		//         }

		// Retrieve errors message bag
		// $errors = $validator->errors();
		$status = Password::reset(
			$request->only('email', 'password',  'token'),
			function ($user, $password) use ($request) {
				$user->forceFill([
					'password' => Hash::make($password)
				]);
				// setRememberToken(\Str::random(60));

				$user->save();

				event(new PasswordReset($user));
			}
		);
		// dd($status);
		if ($status == Password::PASSWORD_RESET) {
			// dd('reset');
			return response()->json(['message' => __($status)], 200);
		} else {
			// dd($status);
			throw ValidationException::withMessages([
				'email' => __($status)
			]);
		}
	}
}
