<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Log;
use App\Models\User;
use Mockery;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Redis;
use App\Services\AuthUserService;
use Carbon\Carbon;
use protos_project_1\protos_client\ClientService;
use grpc\Register\RegisterServiceClient;
use grpc\userRegistrationFormData\UserRegistrationFormDataServiceClient;
use Tests\FeatureBaseClassTest;
use grpc\EditUserDetails\EditUserDetailsRequest;
use grpc\EditUserDetails\EditUserDetailsResponse;
use grpc\EditUserDetails\EditUserDetailsServiceClient;

class UserControllerTest extends FeatureBaseClassTest {
    /**
     * A basic feature test example.
     */
	use RefreshDatabase;

    public function test_gateway_registration_success() {
		$userId = 1;
        $redisKey = 'user_' . $userId;
		$userDataArray = file_get_contents(base_path('tests/Fixtures/user.json'), true);
        Redis::shouldReceive('get')
            ->once()
            ->with($redisKey)
            ->andReturn($userDataArray);
	
		$authUserService = Mockery::mock(AuthUserService::class);
		$authUserService->shouldReceive('getUser')
			->andReturn($userDataArray);
		$authUserService->shouldReceive('getUserTimeZone')
			->andReturn('+8:00');

		$mockGrpcClient = Mockery::mock(RegisterServiceClient::class);
		$mockGrpcClient->shouldReceive('RegisterUserDetails')
			->andReturn(new class {
				public function wait() {
					$mockResponse = Mockery::mock();
					$mockResponse->shouldReceive('serializeToJsonString')
						->andReturn(file_get_contents(base_path('tests/Fixtures/user.json')));
					$mockResponse->shouldReceive('getResult')
						->andReturn(true);
					$status = new \stdClass();
					$status->code = \Grpc\STATUS_OK;
					$status->details = '';
					return [$mockResponse, $status];
				}
			});
		
		$mockClientService = Mockery::mock(ClientService::class);
		$mockClientService->shouldReceive('getRegisterServiceClient')->andReturn($mockGrpcClient);
		
		$this->app->instance(ClientService::class, $mockClientService);

        $response = $this->postRequest('/api/web/private/user/register', [
            'username' => 'newuser123x',
            'password' => 'securePass1',
            'firstname' => 'John',
            'middlename' => 'M',
            'lastname' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '09123456789',
            'address' => '123 Street',
            'birthdate' => '1995-01-01',
            'gender' => 'Male',
            'department' => 'IT',
            'position' => 'Developer',
        ]);

		$testObjectResponse = $response['testObjectResponse'];
		$payload = $response['payload'];

        $testObjectResponse->assertStatus(200);
    }

	public function test_registration_form_data() {
		$userId = 1;
		$redisKey = 'user_' . $userId;

        $userDataArray = json_decode(file_get_contents(base_path('tests/Fixtures/user.json')), true);
        $userJson = json_encode($userDataArray);

        Redis::shouldReceive('get')
            ->once()
            ->with($redisKey)
            ->andReturn($userJson);
			
		$mockGrpcClient = Mockery::mock(UserRegistrationFormDataServiceClient::class);
		$mockGrpcClient->shouldReceive('UserRegistrationFormData')
			->andReturn(new class {
				public function wait() {
					$mockResponse = Mockery::mock();
					$mockResponse->shouldReceive('serializeToJsonString')
						->andReturn(file_get_contents(base_path('tests/Fixtures/user.json')));

					$status = new \stdClass();
					$status->code = \Grpc\STATUS_OK;
					$status->details = '';
					return [$mockResponse, $status];
				}
			});
		
		$mockClientService = Mockery::mock(ClientService::class);
		$mockClientService->shouldReceive('getRegistrationFormDataClient')->andReturn($mockGrpcClient);
		
		$this->app->instance(ClientService::class, $mockClientService);

		$response = $this->postRequest('/api/web/private/user/registration/form/data', []);

		$testObjectResponse = $response['testObjectResponse'];
		$payload = $response['payload'];
		
		$testObjectResponse->assertStatus(200);
	}

	public function testDaysBeforeCalculatesCorrectly() {
		$authUserService = Mockery::mock(AuthUserService::class);
		$clientService = new \protos_project_1\protos_client\ClientService();

		Carbon::setTestNow(Carbon::create(2025, 6, 3));
		$responseData = [
			'userDetailsDateOfBirth' => '1995-07-01',
		];

		$class = new \App\Http\Controllers\UserController($authUserService, $clientService); 
		$reflection = new \ReflectionClass($class);
		$method = $reflection->getMethod('daysbefore');
		$method->setAccessible(true);

		$result = $method->invokeArgs($class, [$responseData]);

		$this->assertEquals(28, $result);

		Carbon::setTestNow();
	}

	public function test_edit_user() {
		$userId = 1;
		$redisKey = 'user_' . $userId;

        $userDataArray = json_decode(file_get_contents(base_path('tests/Fixtures/user.json')), true);
        $userJson = json_encode($userDataArray);

        Redis::shouldReceive('get')
            ->once()
            ->with($redisKey)
            ->andReturn($userJson);
			
		$mockGrpcClient = Mockery::mock(EditUserDetailsServiceClient::class);
		$mockGrpcClient->shouldReceive('EditUserDetails')
			->andReturn(new class {
				public function wait() {
					$mockResponse = Mockery::mock();
					$mockResponse->shouldReceive('serializeToJsonString')
						->andReturn(file_get_contents(base_path('tests/Fixtures/user.json')));
					$mockResponse->shouldReceive('getResult')
						->andReturn(true);
					$status = new \stdClass();
					$status->code = \Grpc\STATUS_OK;
					$status->details = '';
					return [$mockResponse, $status];
				}
			});
		
		$mockClientService = Mockery::mock(ClientService::class);
		$mockClientService->shouldReceive('EditUserDetailsServiceClient')->andReturn($mockGrpcClient);
		
		$this->app->instance(ClientService::class, $mockClientService);
		$action_by_user_id = 1;
		$tz = "TEST";
		$first_name = "Test JD edited";
		$middle_name = "JD edited";
		$last_name = "JD edited";
		$email = "JD@com.edited";
		$phone = "0219370914";
		$address = "TEST ADdress edited";
		$department = "Pending Selection";
		$date_of_birth = '2025-05-25 00:00:00';
		$gender = true;
		$position = "Manager";
		$profile_image = "TEST PROFILE IMAGE edited";
		$set_profile_image_u_r_l = "TEST SET PROFILE IMAGE URL edited";
		$set_profile_image_Name = "TEST SET PROFILE IMAGE NAME edited";
		$fk = 1;
		$response = $this->postRequest('/api/web/private/user/edit', [
            'username' => 'superuser',
            'password' => 'securePass1',
            'firstname' => 'John',
            'middlename' => 'M',
            'lastname' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '09123456789',
            'address' => '123 Street',
            'birthdate' => '1995-01-01',
            'gender' => 'Male',
            'department' => 'IT',
            'position' => 'Developer',
		]);

		$testObjectResponse = $response['testObjectResponse'];
		$payload = $response['payload'];
		
		$testObjectResponse->assertStatus(200);
	}

}
