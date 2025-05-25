<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use grpc\Register\RegisterUserDetailsRequest;
use grpc\userRegistrationFormData\UserRegistrationFormDataRequest;
use grpc\GetUserDetails\GetUserDetailsRequest;
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

}