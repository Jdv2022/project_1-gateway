<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use protos_project_1\protos_client\ClientService;
use App\Services\AuthUserService;
use Illuminate\Http\JsonResponse;
use grpc\Overview\OverviewRequest;
use grpc\Overview\OverviewResponse;
use grpc\Overview\UserData;
use Log;

class OverviewController extends __ApiBaseController {

	public function __construct(AuthUserService $authService, ClientService $clientService) {
		$this->authService = $authService;
		$this->clientService = $clientService;
    }

	public function getOverview(Request $request): JsonResponse {
		Log::info("Getting overview...");

		$superUser = $this->authService->authUser();

		$gprcRequest = new OverviewRequest();
		$gprcRequest->setActionByUserId($superUser['id']);

		$userClient = $this->clientService->OverviewServiceClient();
		list($response, $status) = $userClient->Overview($gprcRequest)->wait();

		if($status->code === \Grpc\STATUS_OK) {
			Log::debug("Response: " . $response->serializeToJsonString() . PHP_EOL);
			return $this->returnSuccess(data: $response, message: "Team has been created successfully.");
		} 
		else {
			Log::error("gRPC call failed with status: " . $status->details . PHP_EOL);
			return $this->returnFail(data: [], message: 'Save new team Unsuccessfull!');
		}

	}

}
