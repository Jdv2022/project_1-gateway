<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthUserService;
use protos_project_1\protos_client\ClientService;
use grpc\GetDepartment\GetDepartmentResponse;
use grpc\GetDepartment\GetDepartmentRequest;
use grpc\CreateDepartment\CreateDepartmentRequest;
use grpc\CreateDepartment\CreateDepartmentResponse;
use grpc\GetDepartmentDetail\GetDepartmentDetailRequest;
use grpc\EditDepartment\EditDepartmentRequest;
use grpc\SuggestedMemberDepartment\SuggestedMemberDepartmentRequest;
use grpc\DeleteDepartment\DeleteDepartmentRequest;
use grpc\GetDepartmentMember\GetDepartmentMemberRequest;
use Illuminate\Http\JsonResponse;
use Log;

class DepartmentsController extends __ApiBaseController {

	public function __construct(AuthUserService $authService, ClientService $clientService) {
		$this->authService = $authService;
		$this->clientService = $clientService;
    }

	public function getDepartments(Request $request) {
		Log::info("Getting departments...");

		$gprcRequest = new GetDepartmentRequest();
		$gprcRequest->setActionByUserId(1);

		$userClient = $this->clientService->GetDepartmentServiceClient();
		list($response, $status) = $userClient->GetDepartment($gprcRequest)->wait();

		if($status->code === \Grpc\STATUS_OK) {
			Log::debug("Response: " . $response->serializeToJsonString() . PHP_EOL);
			if($response->getDepartments()) {
				return $this->returnSuccess(data: $response->serializeToJsonString(), message: "Successfully fetched team lists.");
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

	public function createDepartment(Request $request) {
		Log::info("Creating departments...");
		$validatedData = $request->validate([
			'department_name' => 'required',
			'description' => 'required',
		]);

		$superUser = $this->authService->authUser();
		$tz = $this->authService->getUserTimeZone();

		$gprcRequest = new CreateDepartmentRequest();
		$gprcRequest->setActionByUserId($superUser['id']);
		$gprcRequest->setDepartmentName($validatedData['department_name']);
		$gprcRequest->setDescription($validatedData['description']);

		$userClient = $this->clientService->CreateDepartmentServiceClient();
		list($response, $status) = $userClient->CreateDepartment($gprcRequest)->wait();

		if($status->code === \Grpc\STATUS_OK) {
			Log::debug("Response: " . $response->serializeToJsonString() . PHP_EOL);
			if($response->getResult()) {
				return $this->returnSuccess(data: $response->serializeToJsonString(), message: "Successfully fetched team lists.");
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

	public function getDepartmentDetail(Request $request, $id) {
		Log::info("Updating department...");	

		$superUser = $this->authService->authUser();
		$tz = $this->authService->getUserTimeZone();

		$gprcRequest = new GetDepartmentDetailRequest();
		$gprcRequest->setActionByUserId($superUser['id']);
		$gprcRequest->setDepartmentId($id);

		$userClient = $this->clientService->GetDepartmentDetailServiceClient();
		list($response, $status) = $userClient->GetDepartmentDetail($gprcRequest)->wait();

		if($status->code === \Grpc\STATUS_OK) {
			return $this->returnSuccess(data: $response->serializeToJsonString(), message: "Successfully fetched team lists.");
		} 
		else {
			Log::error("gRPC call failed with status: " . $status->details . PHP_EOL);
			return $this->returnFail(data: [], message: 'Connection error to User Service!');
		}
	}

	public function editDepartment(Request $request) {
		Log::info("Updating department...");	

		$superUser = $this->authService->authUser();
		$tz = $this->authService->getUserTimeZone();

		$gprcRequest = new EditDepartmentRequest();
		$gprcRequest->setActionByUserId($superUser['id']);
		$gprcRequest->setTimeZone($tz);
		$gprcRequest->setDepartmentId($request->department_id);
		$gprcRequest->setDepartmentName($request->department_name);
		$gprcRequest->setDescription($request->description);

		$userClient = $this->clientService->EditDepartmentServiceClient();
		list($response, $status) = $userClient->EditDepartment($gprcRequest)->wait();

		if($status->code === \Grpc\STATUS_OK) {
			Log::debug("Response: " . $response->serializeToJsonString() . PHP_EOL);
			if($response->getResult()) {
				return $this->returnSuccess(data: $response->serializeToJsonString(), message: "Successfully fetched team lists.");
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

	public function deleteDepartment(Request $request) {
		Log::info("Deleting department...");

		$validatedData = $request->validate([
			'department_id' => 'required',
		]);

		$superUser = $this->authService->authUser();
		$tz = $this->authService->getUserTimeZone();

		$gprcRequest = new DeleteDepartmentRequest();
		$gprcRequest->setActionByUserId($superUser['id']);
		$gprcRequest->setTimeZone($tz);
		$gprcRequest->setDepartmentId($validatedData['department_id']);

		$userClient = $this->clientService->DeleteDepartmentServiceClient();
		list($response, $status) = $userClient->DeleteDepartment($gprcRequest)->wait();

		if($status->code === \Grpc\STATUS_OK) {
			Log::debug("Response: " . $response->serializeToJsonString() . PHP_EOL);
			if($response->getResult()) {
				return $this->returnSuccess(data: $response->serializeToJsonString(), message: "Successfully fetched team lists.");
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

	public function getDepartmentSuggestedMembers(Request $request) {
		Log::info("Getting department members...");

		$superUser = $this->authService->authUser();
		$tz = $this->authService->getUserTimeZone();

		$gprcRequest = new SuggestedMemberDepartmentRequest();
		$gprcRequest->setActionByUserId($superUser['id']);
		$gprcRequest->setTimeZone($tz);

		$userClient = $this->clientService->SuggestedMemberDepartmentServiceClient();
		list($response, $status) = $userClient->SuggestedMemberDepartment($gprcRequest)->wait();

		if($status->code === \Grpc\STATUS_OK) {
			Log::debug("Response: " . $response->serializeToJsonString() . PHP_EOL);
			return $this->returnSuccess(data: $response->serializeToJsonString(), message: "Successfully fetched department lists.");
		} 
		else {
			Log::error("gRPC call failed with status: " . $status->details . PHP_EOL);
			return $this->returnFail(data: [], message: 'Connection error to User Service!');
		}
	}

	public function getDepartmentMembers(Request $request) {
		Log::info("Getting department members...");

		$superUser = $this->authService->authUser();
		$tz = $this->authService->getUserTimeZone();
		$validatedData = $request->validate([
			'department_id' => 'required',
		]);

		$gprcRequest = new GetDepartmentMemberRequest();
		$gprcRequest->setActionByUserId($superUser['id']);
		$gprcRequest->setTimeZone($tz);

		$userClient = $this->clientService->GetDepartmentMemberServiceClient();
		list($response, $status) = $userClient->GetDepartmentMember($gprcRequest)->wait();

		if($status->code === \Grpc\STATUS_OK) {
			Log::debug("Response: " . $response->serializeToJsonString() . PHP_EOL);
			return $this->returnSuccess(data: $response->serializeToJsonString(), message: "Successfully fetched department lists.");
		} 
		else {
			Log::error("gRPC call failed with status: " . $status->details . PHP_EOL);
			return $this->returnFail(data: [], message: 'Connection error to User Service!');
		}
	}

}