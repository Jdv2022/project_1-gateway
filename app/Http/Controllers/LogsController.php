<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthUserService;
use protos_project_1\protos_client\ClientService;
use grpc\GetLogs\GetLogsResponse;
use grpc\GetLogs\GetLogsRequest;
use Illuminate\Http\JsonResponse;
use Log;

class LogsController extends __ApiBaseController {

	public function __construct(AuthUserService $authService, ClientService $clientService) {
		$this->authService = $authService;
		$this->clientService = $clientService;
    }

	public function getLogs(Request $request): JsonResponse {
		Log::info("Getting overview...");

		$superUser = $this->authService->authUser();

		$gprcRequest = new GetLogsRequest();
		$gprcRequest->setFk($superUser['id']);
		$gprcRequest->setTimeZone("test");

		$userClient = $this->clientService->GetLogsServiceClient();
		list($response, $status) = $userClient->GetLogs($gprcRequest)->wait();

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