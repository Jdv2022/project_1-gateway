<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use grpc\Register\RegisterUserDetailsRequest;
use grpc\Register\RegisterUserDetailsResponse;
use App\Models\User;
use App\Services\AuthUserService;
use Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use protos_project_1\protos_client\ClientService;

class UserController extends __ApiBaseController {

    public function __construct(AuthUserService $authService, ClientService $clientService) {
		$this->authService = $authService;
		$this->clientService = $clientService;
    }

	public function gatewayRegistration(Request $request):JsonResponse {
		$validatedData = $request->validate([
            'username' => 'required|string|max:12|unique:users|regex:/^[a-zA-Z0-9_]+$/',
            'password' => 'required|string|min:8|regex:/^[a-zA-Z0-9_]+$/',
			'firstname' => 'required|string|max:255',
			'middlename' => 'required|string|max:255',
			'lastname' => 'required|string|max:255',
			'email' => 'required|email|max:255',
			'phone' => 'required|string|max:20',
			'address' => 'required|string|max:255',
			'birthdate' => 'required|date',
			'gender' => 'required|string|max:10',
			'department' => 'required',
			'position' => 'required',
			// 'profile_image' => 'nullable|string|max:255',
        ]);  

		Log::info("[User@gatewayRegistration]: request data validated");

		$superUser = $this->authService->authUser();
		log::debug($superUser);
        if(!$superUser) throw new Exception("Your account does not exist!");
        $now = Carbon::now();
		$user = new User();
		$user->fill([
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
		
		if($user->save()) {
			$userClient = $this->clientService->userServiceClient();
			$gprcRequest = new RegisterUserDetailsRequest();
			$gprcRequest->setFirstName($validatedData['first_name']);
			$gprcRequest->setMiddleName($validatedData['middle_name']);
			$gprcRequest->setLastName($validatedData['last_name']);
			$gprcRequest->setEmail($validatedData['email']);
			$gprcRequest->setPhone($validatedData['phone']);
			$gprcRequest->setAddress($validatedData['address']);
			$gprcRequest->setDateOfBirth($validatedData['birthdate']);
			$gprcRequest->setDepartment($validatedData['department']);
			$gprcRequest->setGender($validatedData['gender']);
			$gprcRequest->setFK($user->id);
			// $gprcRequest->setProfileImage($validatedData['profile_image']);
			// $gprcRequest->setActionByUserId($validatedData['action_by_user_id']);

			list($response, $status) = $userClient->RegisterUserDetails($gprcRequest)->wait();

			if($status->code === \Grpc\STATUS_OK) {
				Log::debug("Response: " . $response->getSaved() . PHP_EOL);
				if($response->getSaved()) {
					return $this->returnSuccess(data: [], message: "Registration success!");
				}
				else {
					$isDelete = User::find($user->id);
					if($isDelete) $isDelete->delete();
				}
			} 
			else {
				$isDelete = User::find($user->id);
				if($isDelete) $isDelete->delete();
				Log::error("gRPC call failed with status: " . $status->details . PHP_EOL);
			}
		}
		else {
			throw new Exception("User registration failed!");
		}

        Log::info('User Registered!');
        return $this->returnFail(data: [], message: "Registration Fail!");
    }

}
