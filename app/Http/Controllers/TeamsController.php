<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use protos_project_1\protos_client\ClientService;
use App\Services\AuthUserService;
use Illuminate\Http\JsonResponse;
use grpc\CreateTeam\CreateTeamRequest;
use grpc\AssignUserToTeam\AssignUserToTeamRequest;
use grpc\AssignUserToTeam\fK;
use grpc\TeamLists\TeamListsRequest;
use grpc\TeamUsersLists\TeamUsersListsRequest;
use grpc\EditTeam\EditTeamRequest;
use grpc\SuggestedMember\SuggestedMemberRequest;
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

		$superUser = $this->authService->authUser();
		$tz = $this->authService->getUserTimeZone();

		$requestModel = new CreateTeamRequest();
		$requestModel->setActionByUserId($superUser['id']);
		$requestModel->setTeamName($validatedData['team_name']);
		$requestModel->setDescription($validatedData['description']);
		$requestModel->setTimeZone($tz);

		$userClient = $this->clientService->CreateTeamClient();
		list($response, $status) = $userClient->CreateTeam($requestModel)->wait();

		if($status->code === \Grpc\STATUS_OK) {
			Log::debug("Response: " . $response->serializeToJsonString() . PHP_EOL);
			if(is_numeric($response->getResult())) {
				return $this->returnSuccess(data: $response->serializeToJsonString(), message: "Team has been created successfully.");
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

	public function assignUsersToTeam(Request $request): JsonResponse {
		Log::info("Assigning user to team...");
		$validatedData = $request->validate([
			'team_id' => 'required',
			'user_id' => 'required|array',
		]);

		$superUser = $this->authService->authUser();
		$tz = $this->authService->getUserTimeZone();

		$requestModel = new AssignUserToTeamRequest();
		$requestModel->setActionByUserId($superUser['id']);
		$requestModel->setTeamId($validatedData['team_id']);
		$requestModel->setTimezone($tz);

		$fkList = [];
		foreach ($validatedData['user_id'] as $value) {
			$fk = new fK();
			$fk->setFk($value);
			$fkList[] = $fk;
		}
		$requestModel->setFk($fkList);

		$userClient = $this->clientService->AssignUserToTeam();
		list($response, $status) = $userClient->AssignUser($requestModel)->wait();

		if($status->code === \Grpc\STATUS_OK) {
			Log::debug("Response: " . $response->serializeToJsonString() . PHP_EOL);
			if($response->getResult()) {
				return $this->returnSuccess(data: $response, message: "Successfully assigned to team.");
			}
			else {
				return $this->returnFail(data: [], message: 'Assign to team Unsuccessfull!');
			}
		} 
		else {
			Log::error("gRPC call failed with status: " . $status->details . PHP_EOL);
			return $this->returnFail(data: [], message: 'Save new user to team Unsuccessfull!');
		}
	}

	public function getTeamLists(Request $request): JsonResponse {
		Log::info("Getting team lists...");

		$gprcRequest = new TeamListsRequest();

		$userClient = $this->clientService->TeamListsServiceClient();
		list($response, $status) = $userClient->TeamLists($gprcRequest)->wait();

		if($status->code === \Grpc\STATUS_OK) {
			Log::debug("Response: " . $response->serializeToJsonString() . PHP_EOL);
			if($response->getTeamLists()) {
				return $this->returnSuccess(data: $response->getTeamLists(), message: "Successfully fetched team lists.");
			}
			else {
				return $this->returnFail(data: [], message: 'Fetch team lists Unsuccessfull!');
			}
		} 
		else {
			Log::error("gRPC call failed with status: " . $status->details . PHP_EOL);
			return $this->returnFail(data: [], message: 'Connection error to User Service!');
		}
	}

	public function getTeamDetails(Request $resquest, $id): JsonResponse {
		Log::info("Getting team members...");

		$superUser = $this->authService->authUser();
		$tz = $this->authService->getUserTimeZone();

		$gprcRequest = new TeamUsersListsRequest();
		$gprcRequest->setTeamId($id);
		$gprcRequest->setTimeZone($tz);
		$gprcRequest->setActionByUserId($superUser['id']);
		
		$userClient = $this->clientService->TeamUsersListsServiceClient();
		list($response, $status) = $userClient->TeamUsersLists($gprcRequest)->wait();

		if($status->code === \Grpc\STATUS_OK) {
			Log::debug("Response: " . $response->serializeToJsonString() . PHP_EOL);
			return $this->returnSuccess(data: $response->serializeToJsonString(), message: "Successfully fetched team lists.");
		} 
		else {
			Log::error("gRPC call failed with status: " . $status->details . PHP_EOL);
			return $this->returnFail(data: [], message: 'Connection error to User Service!');
		}
	}
	
	public function getSuggestedMembers(Request $request): JsonResponse {	
		Log::info("Getting suggested members...");

		$superUser = $this->authService->authUser();
		$tz = $this->authService->getUserTimeZone();

		$gprcRequest = new SuggestedMemberRequest();
		$gprcRequest->setTimeZone($tz);
		$gprcRequest->setActionByUserId($superUser['id']);
		
		$userClient = $this->clientService->SuggestedMemberServiceClient();
		list($response, $status) = $userClient->SuggestedMember($gprcRequest)->wait();

		if($status->code === \Grpc\STATUS_OK) {
			Log::debug("Response: " . $response->serializeToJsonString() . PHP_EOL);
			return $this->returnSuccess(data: $response->serializeToJsonString(), message: "Successfully fetched suggested members.");
		} 
		else {
			Log::error("gRPC call failed with status: " . $status->details . PHP_EOL);
			return $this->returnFail(data: [], message: 'Connection error to User Service!');
		}
	}

	public function editTeam(Request $request): JsonResponse {
		Log::info("Editing team...");

		$validator = $request->validate([
			'team_name' => 'required',
			'description' => 'required',
			'team_id' => 'required',
		]);

		$superUser = $this->authService->authUser();
		$tz = $this->authService->getUserTimeZone();

		$gprcRequest = new EditTeamRequest();
		$gprcRequest->setTimeZone($tz);
		$gprcRequest->setActionByUserId($superUser['id']);
		$gprcRequest->setTeamId($validator['team_id']);
		$gprcRequest->setTeamName($validator['team_name']);
		$gprcRequest->setDescription($validator['description']);
		
		$userClient = $this->clientService->EditTeamServiceClient();
		list($response, $status) = $userClient->EditTeam($gprcRequest)->wait();

		if($status->code === \Grpc\STATUS_OK) {
			Log::debug("Response: " . $response->serializeToJsonString() . PHP_EOL);
			if(is_numeric($response->getResult())) {
				return $this->returnSuccess(data: $response->serializeToJsonString(), message: "Team has been created successfully.");
			}
			else {
				return $this->returnFail(data: [], message: $response->getResult());
			}
		} 
		else {
			Log::error("gRPC call failed with status: " . $status->details . PHP_EOL);
			return $this->returnFail(data: [], message: 'Save new team Unsuccessfull!');
		}
	}

}
