<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ForgetPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::middleware("localization")->group(function () {

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forget/email', [ForgetPasswordController::class, 'forgetPassword']);
Route::post('/password/reset/{token}', [ForgetPasswordController::class, 'resetPassword'])->name('password.reset');
});


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();

// });
    // Route::get('/user', [AuthController::class, 'user']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', function (Request $request) {
               return $request->user();
    });
});
