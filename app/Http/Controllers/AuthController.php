<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Log;
use App\Models\User;
use App\Models\UserDetail;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use protos_project_1\protos_client\ClientService;
use grpc\GetUserDetails\GetUserDetailsRequest;
use Illuminate\Support\Facades\Redis;

class AuthController extends __ApiBaseController {
    
    public function __construct(ClientService $clientService) {
		$this->clientService = $clientService;
    }

    public function webLogin(Request $request):JsonResponse {
        $validatedData = $request->validate([
            'username' => 'required|string|max:12|regex:/^[a-zA-Z0-9_]+$/',
            'password' => 'required|min:8'
        ]);  

        Log::info("[AuthController@webLogin] Logging in username ". $validatedData['username']);
        $token = JWTAuth::attempt($validatedData);
        if($token) {
			$user = User::where('username', $validatedData['username'])->first();
			
			$userClient = $this->clientService->getUserServiceClient();
			$gprcRequest = new GetUserDetailsRequest();
			$gprcRequest->setFK($user->id);

			list($response, $status) = $userClient->GetUserDetails($gprcRequest)->wait();

			if($status->code === \Grpc\STATUS_OK) {
				Log::debug("Response: Success");
				$responseArray = json_decode($response->serializeToJsonString(), true);
				$value = [...$user->toArray(), ...$responseArray];
				$key = 'user_' . $user->id;
				Redis::set($key, json_encode($value));
				Log::info("User Logged In Successfully...");
				Log::info("User data CACHED!");
			} 
			else {
				Log::error("gRPC call failed with status: " . $status->details . PHP_EOL);
				return $this->returnFail(data: null, message: "Account Incomplete!", status: 400);
			}
        }
		else {
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
