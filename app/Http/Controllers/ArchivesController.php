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
use grpc\GetArchives\GetArchivesRequest;
use grpc\GetArchives\GetArchivesResponse;
use Illuminate\Support\Facades\Redis;
use grpc\getAttendance\GetAttendanceRequest;
use App\Services\AuthUserService;
use grpc\userClockIn\UserClockInRequest;
use grpc\userClockOut\UserClockOutRequest;

class ArchivesController extends __ApiBaseController {

    public function __construct(AuthUserService $authService, ClientService $clientService) {
		$this->authService = $authService;
		$this->clientService = $clientService;
    }

	public function getArchives(Request $request): JsonResponse {
		Log::info("Get Archives status");
		$validatedData = $request->validate([
			'action_by_user_id' => 'required',
		]);

		$gprcRequest = new GetArchivesRequest();
		$gprcRequest->setActionByUserId($validatedData['action_by_user_id']);

		$userClient = $this->clientService->GetArchivesServiceClient();
		list($response, $status) = $userClient->GetArchives($gprcRequest)->wait();

		if($status->code === \Grpc\STATUS_OK) {
			Log::debug("Response: " . $response->serializeToJsonString() . PHP_EOL);
			if($response->getArchives()) {
				return $this->returnSuccess(data: $response->getArchives(), message: "Archives fetched successfully.");
			}
			else {
				return $this->returnFail(data: [], message: 'Archives fetch Unsuccessfull!');
			}
		} 
		else {
			Log::error("gRPC call failed with status: " . $status->details . PHP_EOL);
			return $this->returnFail(data: [], message: 'Archives Unsuccessfull!');
		}
	}

}
