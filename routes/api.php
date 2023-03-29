<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\EmailConfirmationController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\TodoController;

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

    Route::post('login', [LoginController::class, 'authenticate'])
            ->middleware(['throttle:auth']);

    Route::middleware('has-token')->group(function () {
        Route::post('logout', [LogoutController::class, 'deleteToken']);

        Route::prefix('profile')->group(function () {
            Route::get('/', [ProfileController::class, 'index']);
            Route::put('/', [ProfileController::class, 'update']);
            Route::delete('/', [ProfileController::class, 'destroy']);
        });

        Route::put('password', [PasswordController::class, 'update']);
    });
});

Route::prefix('todos')->group(function () {
    Route::middleware('has-token')->group(function () {
        Route::controller(TodoController::class)->group(function () {
            Route::post('/', 'store');
            Route::get('/', 'index');
            Route::get('count', 'count');
            Route::get('{id}', 'show');
            Route::put('{id}', 'update');
            Route::delete('{id}', 'destroy');
            Route::delete('/', 'destroy');
        });
    });
});
