<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use protos_project_1\protos_client\ClientService;
use grpc\GetUserDetails\GetUserDetailsRequest;
use grpc\getAttendance\GetAttendanceRequest;
use App\Services\AuthUserService;
use Illuminate\Http\JsonResponse;
use grpc\CreateShift\CreateShiftRequest;
use Log;

class UserShiftController extends __ApiBaseController {
    
	public function __construct(AuthUserService $authService, ClientService $clientService) {
		$this->authService = $authService;
		$this->clientService = $clientService;
    }
	
	public function createShift(Request $request): JsonResponse {
		Log::info("User Shift Controller");

		$validatedData = $request->validate([
			'shift_name' => 'required|string',
			'description' => 'required|string'
		]);

		$in = new CreateShiftRequest();

		$in->setShiftName($validatedData['shift_name']);
		$in->setDescription($validatedData['description']);

		$userClient = $this->clientService->CreateUserShiftServiceClient();
		list($response, $status) = $userClient->CreateShift($in)->wait();
		
		if($status->code === \Grpc\STATUS_OK) {
			Log::debug("Response: " . $response->serializeToJsonString() . PHP_EOL);
			if($response->getResult()) {
				return $this->returnSuccess(data: $response, message: "Shift has been created successfully.");
			}
			else {
				return $this->returnFail(data: [], message: 'Create Shift Unsuccessfull!');
			}
		} 
		else {
			Log::error("gRPC call failed with status: " . $status->details . PHP_EOL);
			return $this->returnFail(data: [], message: 'Save new Shift Unsuccessfull!');
		}
	}	

}
