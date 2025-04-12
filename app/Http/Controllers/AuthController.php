<?php

namespace App\Http\Controllers;

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

class AuthController extends __ApiBaseController {
    
    public function __construct() {

    }

    public function webLogin(Request $request):JsonResponse {
        $validatedData = $request->validate([
            'username' => 'required|string|max:12|regex:/^[a-zA-Z0-9_]+$/',
            'password' => 'required|min:8'
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

	public function validateToken(Request $request):JsonResponse {
        return $this->returnSuccess(data: null, message: "Token Valid");
    }

	public function refreshToken(Request $request): JsonResponse {
		$token = $request->bearerToken(); 
		if (!$token) {
			return $this->returnFail(data: null, message: "Token not provided", status: 400);
		}
	
		$newToken = JWTAuth::setToken($token)->refresh();
		Log::info("Refresh Token Successful!");
		$data = [
			'access_token' => $newToken,
			'token_expire' => JWTAuth::factory()->getTTL() * 60,
		];
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
