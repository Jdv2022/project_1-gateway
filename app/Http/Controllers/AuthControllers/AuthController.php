<?php

namespace App\Http\Controllers\AuthControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Log;
use App\Models\User;
use App\Models\UserDetail;
use App\Http\Controllers\ApiBaseController;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AuthController extends ApiBaseController {
    
    public function __construct() {

    }

    public function webLogin(Request $request):JsonResponse {
        $validatedData = $request->validate([
            'username' => 'required|string|max:12|regex:/^[a-zA-Z0-9_]+$/',
            'password' => 'required|min:8|regex:/^[a-zA-Z0-9_]+$/'
        ]);  

        Log::info("[AuthController@webLogin] Logging in username ". $validatedData['username']);
        $token = JWTAuth::attempt($validatedData);
        if(!$token) {
            return $this->returnFail(data: [], message: "Incorrect credentials!", status: 404);
        }
        Log::info("[AuthController@webLogin] " . $validatedData['username'] . ' login success!');

		$payload = [
			'access_token' => $token,
			'token_expire' => JWTAuth::factory()->getTTL() * 60,
		];
        
        return $this->returnSuccess(data: $payload, message: "Login success!");
    }

    public function webRegistration(Request $request):JsonResponse {
        $validatedData = $request->validate([
            'username' => 'required|string|max:12|regex:/^[a-zA-Z0-9_]+$/',
            'password' => 'required|string|min:8|regex:/^[a-zA-Z0-9_]+$/',
            'first_name' => 'required|string|regex:/^[a-zA-Z0-9_]+$/',
            'middle_name' => 'required|string|regex:/^[a-zA-Z0-9_]+$/',
            'last_name' => 'required|string|regex:/^[a-zA-Z0-9_]+$/',
            'email' => 'required|email|unique:user_details|regex:/^[a-zA-Z0-9_]+$/',
            'phone' => 'required|numeric|digits_between:10,15|regex:/^[a-zA-Z0-9_]+$/',
            'address' => 'required|string|regex:/^[a-zA-Z0-9_]+$/',
            'country' => 'required|string|regex:/^[a-zA-Z0-9_]+$/',
            'date_of_birth' => 'required|date|regex:/^[a-zA-Z0-9_]+$/',
            'age' => 'required|integer|min:0|regex:/^[a-zA-Z0-9_]+$/',
            'gender' => 'required|in:0,1|regex:/^[a-zA-Z0-9_]+$/',
            'profile_image' => 'required|image|mimes:jpeg,png,jpg|max:2048|regex:/^[a-zA-Z0-9_]+$/',
            'action_by_user_id' => 'required|numeric|regex:/^[a-zA-Z0-9_]+$/',
        ]);  
        $model = new UserDetail();
        $superUser = $model->getAuthUser();

        if(!$superUser) throw new Exception("Your account does not exist!");
        $now = Carbon::now();
        
        $user = User::create([
            'username' => $validatedData['username'],
            'password' => Hash::make($validatedData['password']),
            'is_active' => false,
            'created_at_timezone' => '+8:00',
            'created_by_user_id' => $now,
            'created_by_username' => $superUser['username'],
            'created_by_user_type' => $superUser['user_type'],
            'updated_at_timezone' => '+8:00',
            'updated_by_user_id' => $now,
            'updated_by_username' => $superUser['username'],
            'updated_by_user_type' => $superUser['user_type'],
            'enabled' => false,
        ]);

        if(!$user) throw new Exception("User registration failed!");

        $userDetail = UserDetail::create([
            'first_name' => $validatedData['first_name'],
            'middle_name' => $validatedData['middle_name'],
            'last_name' => $validatedData['last_name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'address' => $validatedData['address'],
            'country' => $validatedData['country'],
            'date_of_birth' => $validatedData['date_of_birth'],
            'age' => $validatedData['age'],
            'gender' => $validatedData['gender'],
            'profile_image' => $validatedData['profile_image'],
        ]);        

        if(!$userDetail) throw new Exception("User_registration registration failed!");
        Log::info('User Registered!');
        return $this->returnSuccess(data: [], message: "Registration success!");
    }

    public function refreshToken(Request $request):JsonResponse {
        $validatedData = $request->validate([
            'token' => 'required|string',
        ]);  
        $newToken = JWTAuth::refresh($validatedData['token']);
        Log::info("Refresh Token Successfull!");
        return $this->returnSuccess(data: $newToken, message: "Refresh success!");
    }

    public function webLogout(Request $reqeust):JsonResponse {
        $model = new UserDetail();
        $superUser = $model->getAuthUser();

        JWTAuth::invalidate(JWTAuth::getToken());

        Log::info("Logout Success!");
        return $this->returnSuccess(data: $newToken, message: "Refresh success!");
    }

}
