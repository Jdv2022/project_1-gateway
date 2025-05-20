<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use grpc\Register\RegisterUserDetailsRequest;
use grpc\Register\RegisterUserDetailsResponse;
use grpc\ProfileImage\ProfileImageRequest;
use grpc\ProfileImage\ProfileImageResponse;
use App\Models\User;
use App\Services\AuthUserService;
use Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use protos_project_1\protos_client\ClientService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

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
			'created_by_user_type' => $superUser['getUserRolesType1'],
			'updated_at_timezone' => '+8:00',
			'updated_by_user_id' => $superUser['id'],
			'updated_by_username' => $superUser['username'],
			'updated_by_user_type' => $superUser['getUserRolesType1'],
			'enabled' => false,
		]);

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
				$imageName = date('Ymd_His') . '_' . time();
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
			$gprcRequest->setFK($user->id);
			$gprcRequest->setSetProfileImageURL($url);
			$gprcRequest->setSetProfileImageName($originalImageName);
			$gprcRequest->setActionByUserId($superUser['id']);

			list($response, $status) = $userClient->RegisterUserDetails($gprcRequest)->wait();

			if($status->code === \Grpc\STATUS_OK) {
				Log::debug("Response: " . $response->getUserDetailsId() . PHP_EOL);
				if($response->getUserDetailsId()) {
					return $this->returnSuccess(data: json_decode($response->serializeToJsonString()), message: "Registration success!");
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

	public function gatewayRegistrationAttachment(Request $request) {
		$request->validate([
			'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
		]);
		
		$disk = Storage::disk('public');  
		$directory = 'profile_pictures';
		
		if (!$disk->exists($directory)) {
			Log::info("DIR does not exist, creating...");
			$disk->makeDirectory($directory);
		}
		Log::debug("Checking directory: " . $disk->path($directory));
		
		$image = $request->file('image');
		$imageName = time() . '_' . $image->getClientOriginalName();
		
		$path = $image->storeAs($directory, $imageName, 'public');
		
		$url = Storage::url($directory . '/' . $imageName);

		$userClient = $this->clientService->getRegisterServiceClient();
		$gprcRequest = new ProfileImageRequest();

		$gprcRequest->setFK($validatedData['firstname']);

		Log::debug("File stored at: " . $path);
		Log::debug("Public URL: " . $url);
		Log::debug("PASSED!");
	}

}
