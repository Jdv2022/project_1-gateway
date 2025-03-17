<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Tymon\JWTAuth\Http\Middleware\Authenticate;
use App\Http\Controllers\AuthControllers\MetaController;
use App\Http\Controllers\AuthControllers\AuthController;

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
Route::post('meta/data', [MetaController::class, 'metaData']);
Route::post('cue', [MetaController::class, 'cue']);

/* Authentication */
Route::post('web/login', [AuthController::class, 'webLogin']);
