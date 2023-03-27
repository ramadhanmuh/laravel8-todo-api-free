<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\EmailConfirmationController;
use App\Http\Controllers\ForgotPasswordController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('users')->group(function () {
    Route::get('email/verify/{id}/{token}', EmailConfirmationController::class);

    Route::middleware(['throttle:send-email'])->group(function () {
        Route::post('register', [RegisterController::class, 'store']);
        Route::post('forgot-password', [ForgotPasswordController::class, 'sendEmail']);
    });
});
