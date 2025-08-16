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
use App\Http\Controllers\OverviewController;
use App\Http\Controllers\LogsController;
use App\Http\Controllers\DepartmentsController;
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
	Route::POST('web/private/user/edit', [UserController::class, 'editUserProfile']);
	
	Route::POST('web/private/user/list', [UsersController::class, 'getUserLists']);

	Route::POST('web/private/user/attendance/day/today', [FEUtilityController::class, 'dayToday']);

	Route::POST('web/private/user/attendance', [AttendanceController::class, 'getAttandance']);
	Route::POST('web/private/user/attendance/clock/in', [AttendanceController::class, 'setClockIn']);
	Route::POST('web/private/user/attendance/clock/out', [AttendanceController::class, 'setClockOut']);

	Route::POST('web/private/user/teams/create', [TeamsController::class, 'createTeam']);
	Route::POST('web/private/assign/user/to/teams', [TeamsController::class, 'assignUsersToTeam']);
	Route::POST('web/private/user/teams/lists', [TeamsController::class, 'getTeamLists']);
	Route::POST('web/private/user/team/details/{id}', [TeamsController::class, 'getTeamDetails']);
	Route::POST('web/private/user/team/suggested/members', [TeamsController::class, 'getSuggestedMembers']);
	Route::POST('web/private/user/teams/edit', [TeamsController::class, 'editTeam']);
	Route::POST('web/private/user/team/remove', [TeamsController::class, 'removeUserTeam']);
	Route::POST('web/private/user/team/delete', [TeamsController::class, 'deleteTeam']);
	
	Route::POST('web/private/user/shift/create', [UserShiftController::class, 'createShift']);
	Route::POST('web/private/user/shift/assign', [UserShiftController::class, 'assignShift']);
	
	Route::POST('web/private/get/users/archives', [ArchivesController::class, 'getArchives']);
	Route::POST('web/private/add/users/archives', [ArchivesController::class, 'addArchives']);
	Route::POST('web/private/remove/users/archives', [ArchivesController::class, 'removeArchives']);

	Route::POST('web/private/get/overview', [OverviewController::class, 'getOverview']);

	Route::POST('web/private/get/logs', [LogsController::class, 'getLogs']);

	Route::POST('web/private/user/departments', [DepartmentsController::class, 'getDepartments']);
	Route::POST('web/private/user/create/department', [DepartmentsController::class, 'createDepartment']);
	Route::POST('web/private/user/department/details/{id}', [DepartmentsController::class, 'getDepartmentDetail']);
	Route::POST('web/private/user/department/edit', [DepartmentsController::class, 'editDepartment']);
	Route::POST('web/private/user/department/delete', [DepartmentsController::class, 'deleteDepartment']);
	Route::POST('web/private/suggested/department/members', [DepartmentsController::class, 'getDepartmentSuggestedMembers']);
	Route::POST('web/private/department/lists', [DepartmentsController::class, 'getDepartmentMembers']);
});