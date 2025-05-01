<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Tymon\JWTAuth\Http\Middleware\Authenticate;
use App\Http\Controllers\MetaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AuthUserMiddleware;

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

/* Startup Data */
Route::POST('meta/data', [MetaController::class, 'metaData']);

/* Authentication */
Route::POST('web/login', [AuthController::class, 'webLogin']);
Route::POST('web/private/refresh/token', [AuthController::class, 'refreshToken']);

/* Standard API */
Route::middleware([AuthUserMiddleware::class,])->group(function () {
	Route::POST('web/private/users', [AuthController::class, 'webLogin']);
	Route::POST('web/private/user/register', [UserController::class, 'gatewayRegistration']);
	Route::POST('web/private/validate/token', [AuthController::class, 'validateToken']);
});