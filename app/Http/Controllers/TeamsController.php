<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use protos_project_1\protos_client\ClientService;
use App\Services\AuthUserService;
use Illuminate\Http\JsonResponse;
use grpc\CreateTeam\CreateTeamResponse;
use grpc\CreateTeam\CreateTeamRequest;
use Log;

class TeamsController extends __ApiBaseController {
    
	public function __construct(AuthUserService $authService, ClientService $clientService) {
		$this->authService = $authService;
		$this->clientService = $clientService;
    }

	public function createTeam(Request $request): JsonResponse {
		Log::info("Creating team...");
		
		$validatedData = $request->validate([
            'team_name' => 'required|string|max:12',
            'description' => 'required|string',
        ]);  

		$requestModel = new CreateTeamRequest();
		$requestModel->setTeamName($validatedData['team_name']);
		$requestModel->setDescription($validatedData['description']);

		$userClient = $this->clientService->CreateTeamClient();
		list($response, $status) = $userClient->CreateTeam($requestModel)->wait();

		if($status->code === \Grpc\STATUS_OK) {
			Log::debug("Response: " . $response->serializeToJsonString() . PHP_EOL);
			if($response->getResult()) {
				return $this->returnSuccess(data: $response, message: "Team has been created successfully.");
			}
			else {
				return $this->returnFail(data: [], message: 'Create Team Unsuccessfull!');
			}
		} 
		else {
			Log::error("gRPC call failed with status: " . $status->details . PHP_EOL);
			return $this->returnFail(data: [], message: 'Save new team Unsuccessfull!');
		}
	}

}
