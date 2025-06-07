<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use grpc\getUsers\GetUsersRequest;
use App\Models\User;
use App\Services\AuthUserService;
use Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use protos_project_1\protos_client\ClientService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redis;

class UsersController extends __ApiBaseController {

    public function __construct(AuthUserService $authService, ClientService $clientService) {
		$this->authService = $authService;
		$this->clientService = $clientService;
    }

	public function getUserLists(Request $request): JsonResponse {
		Log::info('[UsersController][getUserLists] Getting user lists...');
		$superUser = $this->authService->authUser();
		$gprcRequest = new GetUsersRequest();
		$gprcRequest->setActionByUserId($superUser['id']);
		$userClient = $this->clientService->getUsers();
		list($response, $status) = $userClient->getUsers($gprcRequest)->wait();

		if($status->code === \Grpc\STATUS_OK) {
			Log::debug("Response: " . $response->serializeToJsonString() . PHP_EOL);
			$res = collect(json_decode($response->serializeToJsonString(), true)); 
			$res = $res['users'] ?? [];

			$users = User::select('id', 'username')->get()->map(function ($user) use ($res) {
				$userArray = $user->toArray();
				$moreDetails = collect($res)->firstWhere('userDetailsId', $userArray['id']);
				if($moreDetails) {
					$userArray = array_merge($userArray, $moreDetails);
				}
				return $userArray;
			});
			return $this->returnSuccess(data: $users, message: "Success");
		} 
		else {
			Log::error("gRPC call failed with status: " . $status->details . PHP_EOL);
			$message = $status->details;
			$parts = explode(':', $message);
			$cleanMessage = trim(end($parts));
			return $this->returnFail(data: [], message: $message);
		}
	}


}