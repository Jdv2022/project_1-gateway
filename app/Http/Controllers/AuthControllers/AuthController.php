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

class AuthController extends ApiBaseController {
    
    public function __construct() {

    }

    public function webLogin(Request $request):JsonResponse {
        $validatedData = $request->validate([
            'username' => 'required|string|max:12',
            'password' => 'required|min:8'
        ]);  

        Log::info("[AuthController@webLogin] Logging in username ". $validatedData['username']);
        $token = JWTAuth::attempt($validatedData);
        if(!$token) {
            return $this->returnFail(data: [], message: "Incorrect credentials!", status: 404);
        }
        Log::info("[AuthController@webLogin] " . $validatedData['username'] . ' login success!');
        
        $tokenDetails = [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ];
        return $this->returnSuccess(data: $tokenDetails, message: "Login success!");
    }

    public function webRegistration(Request $request) {
        $validatedData = $request->validate([
            'username' => 'required|string|max:12',
            'password' => 'required|string|min:8',
            'first_name' => 'required|string',
            'middle_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:user_details',
            'phone' => 'required|numeric|digits_between:10,15',
            'address' => 'required|string',
            'country' => 'required|string',
            'date_of_birth' => 'required|date',
            'age' => 'required|integer|min:0',
            'gender' => 'required|in:0,1',
            'profile_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'action_by_user_id' => 'required|numeric',
        ]);  
        $superUser = User::with(['user_detail'])
            ->where('id', $validatedData['action_by_user_id'])
            ->first();
        if(!$superUser) throw new Exception("Your account does not exist!");

        $user = User::create([
            'username' => $validatedData['username'],
            'password' => Hash::make($validatedData['password']),
            'is_active' => false,
            'created_at_timezone' => '+8:00',
            'created_by_user_id' => $superUser->user_detail->id,
            'created_by_username' => $superUser->user_detail->username,
            'created_by_user_type' => $superUser->user_detail->user_type,
            'updated_at_timezone' => '+8:00',
            'updated_by_user_id' => $superUser->user_detail->id,
            'updated_by_username' => $superUser->user_detail->username,
            'updated_by_user_type' => $superUser->user_detail->user_type,
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
        return $this->returnSuccess(data: [], message: "Registration success!");
    }

}
