<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Tymon\JWTAuth\Http\Middleware\Authenticate;
use App\Http\Controllers\AuthControllers\MetaController;
use App\Http\Controllers\AuthControllers\AuthController;
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
Route::POST('cue', [MetaController::class, 'cue']);

/* Authentication */
Route::POST('web/login', [AuthController::class, 'webLogin']);

/* Standard API */
Route::middleware([AuthUserMiddleware::class])->group(function () {
	Route::POST('web/private/users', [AuthController::class, 'webLogin']);
	// Route::POST('meta/data', [MetaController::class, 'metaData']);

});