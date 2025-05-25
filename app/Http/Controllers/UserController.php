<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use grpc\Register\RegisterUserDetailsRequest;
use grpc\userRegistrationFormData\UserRegistrationFormDataRequest;
use grpc\GetUserDetails\GetUserDetailsRequest;
use App\Models\User;
use App\Services\AuthUserService;
use Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use protos_project_1\protos_client\ClientService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redis;

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
			'file' => 'image|mimes:jpeg,png,jpg,gif,svg',
        ]);  

		Log::info("[User@gatewayRegistration]: request data validated");

		$superUser = $this->authService->authUser();

        if(!$superUser) throw new Exception("Your account does not exist!");
		$user = new User();
		$user->fill([
			'username' => $validatedData['username'],
			'password' => Hash::make($validatedData['password']),
			'is_active' => false,
			'created_at_timezone' => '+8:00',
			'created_by_user_id' => $superUser['id'],
			'created_by_username' => $superUser['username'],
			'created_by_user_type' => $superUser['userRolesType1'],
			'updated_at_timezone' => '+8:00',
			'updated_by_user_id' => $superUser['id'],
			'updated_by_username' => $superUser['username'],
			'updated_by_user_type' => $superUser['userRolesType1'],
			'enabled' => false,
		]);
		$message = "";
		if($user->save()) {
			Log::info("Saving user...");

			$disk = Storage::disk('public');  
			$directory = 'profile_pictures';
			
			if (!$disk->exists($directory)) {
				Log::info("DIR does not exist, creating...");
				$disk->makeDirectory($directory);
			}
			Log::debug("Checking directory: " . $disk->path($directory));
			
			$image = $request->file('file');
			if($image) {
				$imageName = date('Ymd_His') . '_' . time() . '.' . $image->getClientOriginalExtension();
				$originalImageName = $image->getClientOriginalName();
				$path = $image->storeAs($directory, $imageName, 'public');
				$url = Storage::url($directory . '/' . $imageName);
			}
			else {
				$originalImageName = 'null';
				$url = 'null';
			}
			$userClient = $this->clientService->getRegisterServiceClient();
			$gprcRequest = new RegisterUserDetailsRequest();

			$gprcRequest->setFirstName($validatedData['firstname']);
			$gprcRequest->setMiddleName($validatedData['middlename']);
			$gprcRequest->setLastName($validatedData['lastname']);
			$gprcRequest->setEmail($validatedData['email']);
			$gprcRequest->setPhone($validatedData['phone']);
			$gprcRequest->setAddress($validatedData['address']);
			$gprcRequest->setDateOfBirth($validatedData['birthdate']);
			$gprcRequest->setDepartment($validatedData['department']);
			$gprcRequest->setGender($validatedData['gender']);
			$gprcRequest->setPosition($validatedData['position']);
			$gprcRequest->setFK($user->id);
			$gprcRequest->setSetProfileImageURL($url);
			$gprcRequest->setSetProfileImageName($originalImageName);
			$gprcRequest->setActionByUserId($superUser['id']);

			list($response, $status) = $userClient->RegisterUserDetails($gprcRequest)->wait();
			$message = $status->details;
			if($status->code === \Grpc\STATUS_OK) {
				Log::debug("Response: " . $response->serializeToJsonString() . PHP_EOL);
				if($response->getResult()) {
					return $this->returnSuccess(data: ['user_id' => $user->id], message: "Registration success!");
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
				return $this->returnFail(data: [], message: 'Save Unsuccessfull!');
			}
		}
		else {
			throw new Exception("User registration failed!");
		}

		$parts = explode(':', $message);
		$cleanMessage = trim(end($parts));
        Log::info('User not fully Registered!');
        return $this->returnFail(data: [], message: $cleanMessage);
    }

	public function registrationFormData(Request $request):JsonResponse {
		Log::info("Getting registration form data...");

		$userClient = $this->clientService->getRegistrationFormDataClient();
		$gprcRequest = new UserRegistrationFormDataRequest();

		$superUser = $this->authService->authUser();

		$gprcRequest->setActionByUserId($superUser['id']);

		list($response, $status) = $userClient->UserRegistrationFormData($gprcRequest)->wait();

		if($status->code === \Grpc\STATUS_OK) {
			Log::debug("Response: " . $response->serializeToJsonString() . PHP_EOL);
			return $this->returnSuccess(data: json_decode($response->serializeToJsonString()), message: "Registration form data success!");
		} 
		else {
			Log::error("gRPC call failed with status: " . $status->details . PHP_EOL);
			return $this->returnFail(data: [], message: "Fetch Error");
		}
	}

	public function getUserProfile(Request $request):JsonResponse {
		Log::info("Getting user profile...");
		$validatedData = $request->validate([
            'user_id' => 'required|integer',
        ]);  
		
		$gprcRequest = new GetUserDetailsRequest();
		$gprcRequest->setFK($validatedData['user_id']);

		$userClient = $this->clientService->getUserServiceClient();
		list($response, $status) = $userClient->GetUserDetails($gprcRequest)->wait();

		if($status->code === \Grpc\STATUS_OK) {
			Log::debug("Response: " . $response->serializeToJsonString() . PHP_EOL);
			$user = User::find($validatedData['user_id']);
			$responseData = json_decode($response->serializeToJsonString(), true);
			$userData = $user->toArray();
			$userData['created_at'] = Carbon::parse($userData['created_at'])->format('F j, Y \a\t g:i A');
			$userData['updated_at'] = Carbon::parse($userData['updated_at'])->format('F j, Y \a\t g:i A');
			if(isset($responseData['userDetailsDateOfBirth'])) {
				$date = Carbon::parse($responseData['userDetailsDateOfBirth']);
				$responseData['userDetailsDateOfBirth'] = $date->translatedFormat('F j, Y');
				$userData['daysTillBirthday'] = $this->daysbefore($responseData); 
			}
			else {
				$responseData['userDetailsDateOfBirth'] = '-';
				$userData['daysTillBirthday'] = '-'; 
			}
			$modifiedByUser = User::whereIn('id', [$userData['created_by_user_id'], $userData['updated_by_user_id']]);
			if(Redis::exists('user_' . $userData['created_by_user_id'])) {
				Log::info("Created by record found on cached!");
				$promotedBy = json_decode(Redis::get('user_' . $userData['created_by_user_id']), true);
				$userData['created_by'] = $promotedBy['userDetailsFirstName'] . ' ' . $promotedBy['userDetailsLastName'];
			}
			else {
				$gprcRequest = new GetUserDetailsRequest();
				$gprcRequest->setFK($userData['created_by_user_id']);
				list($response2, $status) = $userClient->GetUserDetails($gprcRequest)->wait();
				$responseData = json_decode($response2->serializeToJsonString(), true);
				$userData['created_by'] = $responseData['userDetailsFirstName'] . ' ' . $responseData['userDetailsLastName'];
			}

			if(Redis::exists('user_' . $userData['updated_by_user_id'])) {
				Log::info("Created by record found on cached!");
				$promotedBy = json_decode(Redis::get('user_' . $userData['updated_by_user_id']), true);
				$userData['updated_by'] = $promotedBy['userDetailsFirstName'] . ' ' . $promotedBy['userDetailsLastName'];
			}
			else {
				$gprcRequest = new GetUserDetailsRequest();
				$gprcRequest->setFK($userData['created_by_user_id']);
				list($response2, $status) = $userClient->GetUserDetails($gprcRequest)->wait();
				$responseData = json_decode($response2->serializeToJsonString(), true);
				$userData['updated_by'] = $responseData['userDetailsFirstName'] . ' ' . $responseData['userDetailsLastName'];
			}

			$mergedData = array_merge($userData, $responseData);
			
			return $this->returnSuccess(data: $mergedData, message: "User profile success!");		
		} 
		else {
			Log::error("gRPC call failed with status: " . $status->details . PHP_EOL);
			return $this->returnFail(data: [], message: "Server Error");
		}
	}

	private function daysbefore(array $responseData): Int {
		$dob = Carbon::parse($responseData['userDetailsDateOfBirth']);
		$today = Carbon::now();
		$next = $dob->copy()->year($today->year);
		if($next->isPast()) {
			$next->addYear();
		}
		$daysTill = $today->diffInDays($next);
		return (int) ceil($daysTill);
	}

}
