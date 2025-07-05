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
use grpc\getAttendance\GetAttendanceRequest;
use App\Services\AuthUserService;
use grpc\userClockIn\UserClockInRequest;
use grpc\userClockOut\UserClockOutRequest;

class AttendanceController extends __ApiBaseController {
    
    public function __construct(AuthUserService $authService, ClientService $clientService) {
		$this->authService = $authService;
		$this->clientService = $clientService;
    }

	public function getAttandance(Request $request): JsonResponse {
		Log::info("Get Attandance status");
		$validated = $request->validate([
			'timezone' => 'required',
			'month' => 'required|string'
		]);
		$superUser = $this->authService->authUser();

		$userClient = $this->clientService->getAttendanceClient();

		$gprcRequest = new GetAttendanceRequest();
		$gprcRequest->setFK($superUser['id']);
		$gprcRequest->setMonth($validated['month']);
		$gprcRequest->setTimeZone($validated['timezone']);

		list($response, $status) = $userClient->GetAttendance($gprcRequest)->wait();

		if($status->code === \Grpc\STATUS_OK) {
			Log::debug("Response: " . $response->serializeToJsonString() . PHP_EOL);
			$retData = json_decode($response->serializeToJsonString());
			$retData->dayToday = Carbon::now($validated['timezone'])->format('l F j');
			return $this->returnSuccess(data: $retData, message: "User Attendance Fetched!");
		} 
		else {
			Log::error("gRPC call failed with status: " . $status->details . PHP_EOL);
			return $this->returnFail(data: [], message: 'Fetch attandance failed!');
		}
	}


	public function setClockIn(Request $request): JsonResponse {
		Log::info('[UsersController][setClockIn] Clocking In...');
		$validated = $request->validate([
			'timezone' => 'required'
		]);

		$superUser = $this->authService->authUser();
		$tz = $this->authService->getUserTimeZone();
		$userClient = $this->clientService->clockIn();

		$gprcRequest = new UserClockInRequest();
		$gprcRequest->setFK($superUser['id']);
		$gprcRequest->setTimeZone($tz);

		list($response, $status) = $userClient->UserClockInService($gprcRequest)->wait();

		if($status->code === \Grpc\STATUS_OK) {
			Log::debug("Response: " . $response->serializeToJsonString() . PHP_EOL);
			$res = collect(json_decode($response->serializeToJsonString(), true)); 
			$res = $res['result'] ?? null;

			if($res) {
				return $this->returnSuccess(data: null, message: "TIME IN SUCCESS!", status: 200, isEnc: false);
			}
			else {
				return $this->returnFail(data: null, message: "TIME IN FAIL!", status: 500, isEnc: false);
			}
		} 
		else {
			Log::error("gRPC call failed with status: " . $status->details . PHP_EOL);
			$message = $status->details;
			$parts = explode(':', $message);
			$cleanMessage = trim(end($parts));
			return $this->returnFail(data: [], message: $message, status: 500, isEnc: false);
		}
	}

	public function setClockOut(Request $request): JsonResponse {
		Log::info('[UsersController][setClockOut] Clocking Out...');
		$validated = $request->validate([
			'timezone' => 'required'
		]);

		$superUser = $this->authService->authUser();
		$tz = $this->authService->getUserTimeZone();
		$userClient = $this->clientService->clockOut();

		$gprcRequest = new UserClockOutRequest();
		$gprcRequest->setFK($superUser['id']);
		$gprcRequest->setTimeZone($tz);

		list($response, $status) = $userClient->SetUserClockOut($gprcRequest)->wait();

		if($status->code === \Grpc\STATUS_OK) {
			Log::debug("Response: " . $response->serializeToJsonString() . PHP_EOL);
			$res = collect(json_decode($response->serializeToJsonString(), true)); 
			$res = $res['result'] ?? null;

			if($res) {
				return $this->returnSuccess(data: null, message: "TIME OUT SUCCESS!", status: 200, isEnc: false);
			}
			else {
				return $this->returnFail(data: null, message: "TIME OUT FAIL!", status: 500, isEnc: false);
			}
		} 
		else {
			Log::error("gRPC call failed with status: " . $status->details . PHP_EOL);
			$message = $status->details;
			$parts = explode(':', $message);
			$cleanMessage = trim(end($parts));
			return $this->returnFail(data: [], message: $message, status: 500, isEnc: false);
		}
	}

}
