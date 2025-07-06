<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Tymon\JWTAuth\Http\Middleware\Authenticate;
use App\Http\Controllers\MetaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\FEUtilityController;
use App\Http\Controllers\UserShiftController;
use App\Http\Controllers\TeamsController;
use App\Http\Controllers\ArchivesController;
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

// Route::options('{any}', function() {
//     return response()->json([], 200);
// })->where('any', '.*');

/* Startup Data */
Route::POST('meta/data', [MetaController::class, 'metaData']);

/* Authentication */
Route::POST('web/login', [AuthController::class, 'webLogin']);
Route::POST('web/private/refresh/token', [AuthController::class, 'refreshToken']);

/* Standard API */
Route::middleware([AuthUserMiddleware::class,])->group(function () {
	Route::POST('web/private/validate/token', [AuthController::class, 'validateToken']);

	Route::POST('web/private/user/register', [UserController::class, 'gatewayRegistration']);
	Route::POST('web/private/user/registration/form/data', [UserController::class, 'registrationFormData']);
	Route::POST('web/private/user/profile/{id}', [UserController::class, 'getUserProfile']);
	
	Route::POST('web/private/user/list', [UsersController::class, 'getUserLists']);

	Route::POST('web/private/user/attendance/day/today', [FEUtilityController::class, 'dayToday']);

	Route::POST('web/private/user/attendance', [AttendanceController::class, 'getAttandance']);
	Route::POST('web/private/user/attendance/clock/in', [AttendanceController::class, 'setClockIn']);
	Route::POST('web/private/user/attendance/clock/out', [AttendanceController::class, 'setClockOut']);

	Route::POST('web/private/user/teams/create', [TeamsController::class, 'createTeam']);
	Route::POST('web/private/assign/user/to/teams', [TeamsController::class, 'assignUsersToTeam']);
	Route::POST('web/private/user/teams/lists', [TeamsController::class, 'getTeamLists']);
	
	Route::POST('web/private/user/shift/create', [UserShiftController::class, 'createShift']);
	Route::POST('web/private/user/shift/assign', [UserShiftController::class, 'assignShift']);
	
	Route::POST('web/private/get/users/archives', [ArchivesController::class, 'getArchives']);
	Route::POST('web/private/add/users/archives', [ArchivesController::class, 'addArchives']);
	Route::POST('web/private/remove/users/archives', [ArchivesController::class, 'removeArchives']);
});