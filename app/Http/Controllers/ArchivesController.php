<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use protos_project_1\protos_client\ClientService;
use grpc\GetArchives\GetArchivesRequest;
use grpc\AddArchive\AddArchiveRequest;
use grpc\RemoveArchive\RemoveArchiveRequest;
use App\Services\AuthUserService;
use Log;

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

	public function addArchives(Request $request): JsonResponse {
		Log::info("Add Archives status");
		$validatedData = $request->validate([
			'action_by_user_id' => 'required',
			'user_id' => 'required',
			'timezone' => 'required',
			'archive_reason' => 'required',
		]);

		$gprcRequest = new AddArchiveRequest();
		$gprcRequest->setActionByUserId($validatedData['action_by_user_id']);
		$gprcRequest->setUserId($validatedData['user_id']);
		$gprcRequest->setTimezone($validatedData['timezone']);
		$gprcRequest->setArchiveReason($validatedData['archive_reason']);

		$userClient = $this->clientService->AddArchiveServiceClient();
		list($response, $status) = $userClient->AddArchive($gprcRequest)->wait();

		if($status->code === \Grpc\STATUS_OK) {
			Log::debug("Response: " . $response->serializeToJsonString() . PHP_EOL);
			if($response->getResult()) {
				return $this->returnSuccess(data: $response, message: "Archives add successfully.");
			}
			else {
				return $this->returnFail(data: [], message: 'Archives add Unsuccessfull!');
			}
		} 
		else {
			Log::error("gRPC call failed with status: " . $status->details . PHP_EOL);
			return $this->returnFail(data: [], message: 'Archives add Unsuccessfull!');
		}
	}

	public function removeArchives(Request $request): JsonResponse {
		Log::info("Remove Archives status");
		$validatedData = $request->validate([
			'action_by_user_id' => 'required',
			'user_id' => 'required',
			'timezone' => 'required',
		]);

		$gprcRequest = new RemoveArchiveRequest();
		$gprcRequest->setActionByUserId($validatedData['action_by_user_id']);
		$gprcRequest->setUserId($validatedData['user_id']);
		$gprcRequest->setTimezone($validatedData['timezone']);

		$userClient = $this->clientService->RemoveArchiveServiceClient();
		list($response, $status) = $userClient->RemoveArchive($gprcRequest)->wait();

		if($status->code === \Grpc\STATUS_OK) {
			Log::debug("Response: " . $response->serializeToJsonString() . PHP_EOL);
			if($response->getResult()) {
				return $this->returnSuccess(data: $response, message: "Archives remove successfully.");
			}
			else {
				return $this->returnFail(data: [], message: 'Archives remove Unsuccessfull!');
			}
		} 
		else {
			Log::error("gRPC call failed with status: " . $status->details . PHP_EOL);
			return $this->returnFail(data: [], message: 'Archives remove Unsuccessfull!');
		}
	}

}
