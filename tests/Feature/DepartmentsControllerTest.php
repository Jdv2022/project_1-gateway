<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use grpc\GetDepartment\GetDepartmentServiceClient;
use grpc\CreateDepartment\CreateDepartmentServiceClient;
use grpc\GetDepartmentDetail\GetDepartmentDetailServiceClient;
use grpc\EditDepartment\EditDepartmentServiceClient;
use grpc\DeleteDepartment\DeleteDepartmentServiceClient;
use grpc\SuggestedMemberDepartment\SuggestedMemberDepartmentServiceClient;
use protos_project_1\protos_client\ClientService;
use grpc\GetDepartmentMember\GetDepartmentMemberServiceClient;
use Tests\TestCase;
use Mockery;
use Log;
use Illuminate\Support\Facades\Redis;
use Tests\FeatureBaseClassTest;

class DepartmentsControllerTest extends FeatureBaseClassTest {

	public function setUp(): void {
		parent::setUp();
		$userId = 1;
		$redisKey = 'user_' . $userId;

        $userDataArray = json_decode(file_get_contents(base_path('tests/Fixtures/user.json')), true);
        $userJson = json_encode($userDataArray);

        Redis::shouldReceive('get')
            ->once()
            ->with($redisKey)
            ->andReturn($userJson);
	}

	public function test_get_department() {
		Log::info("Getting department...");

		$mockGrpcClient = Mockery::mock(GetDepartmentServiceClient::class);
		$mockGrpcClient->shouldReceive('GetDepartment')
			->andReturn(new class {
				public function wait() {
					$mockResponse = Mockery::mock();
					$mockResponse->shouldReceive('serializeToJsonString')
								->andReturn(file_get_contents(base_path('tests/Fixtures/user.json'), true));
					$mockResponse->shouldReceive('getDepartments')
								->andReturn([1]);
					$mockStatus = new \stdClass();
					$mockStatus->code = \Grpc\STATUS_OK;
					$mockStatus->details = '';

					return [$mockResponse, $mockStatus];
				}
			});

		$mockClientService = Mockery::mock(ClientService::class);
		$mockClientService->shouldReceive('GetDepartmentServiceClient')->andReturn($mockGrpcClient);
		
		$this->app->instance(ClientService::class, $mockClientService);

		$response = $this->postRequest('api/web/private/user/departments', []);

		$testObjectResponse = $response['testObjectResponse'];
		$payload = $response['payload'];

        $testObjectResponse->assertStatus(200);
	}

	public function test_create_department() {
		Log::info("Creating department...");

		$mockGrpcClient = Mockery::mock(CreateDepartmentServiceClient::class);
		$mockGrpcClient->shouldReceive('CreateDepartment')
			->andReturn(new class {
				public function wait() {
					$mockResponse = Mockery::mock();
					$mockResponse->shouldReceive('serializeToJsonString')
								->andReturn(file_get_contents(base_path('tests/Fixtures/user.json'), true));
					$mockResponse->shouldReceive('getResult')
								->andReturn(true);
					$mockStatus = new \stdClass();
					$mockStatus->code = \Grpc\STATUS_OK;
					$mockStatus->details = '';

					return [$mockResponse, $mockStatus];
				}
			});

		$mockClientService = Mockery::mock(ClientService::class);
		$mockClientService->shouldReceive('CreateDepartmentServiceClient')->andReturn($mockGrpcClient);
		
		$this->app->instance(ClientService::class, $mockClientService);

		$response = $this->postRequest('api/web/private/user/create/department', [
			'department_name' => "Department 1",
			'description' => "Department 1 description: test"
		]);

		$testObjectResponse = $response['testObjectResponse'];
		$payload = $response['payload'];

        $testObjectResponse->assertStatus(200);
	}

	public function test_get_department_detail() {
		Log::info("Updating department...");

		$mockGrpcClient = Mockery::mock(GetDepartmentDetailServiceClient::class);
		$mockGrpcClient->shouldReceive('GetDepartmentDetail')
			->andReturn(new class {
				public function wait() {
					$mockResponse = Mockery::mock();
					$mockResponse->shouldReceive('serializeToJsonString')
								->andReturn(file_get_contents(base_path('tests/Fixtures/user.json'), true));

					$mockStatus = new \stdClass();
					$mockStatus->code = \Grpc\STATUS_OK;
					$mockStatus->details = '';

					return [$mockResponse, $mockStatus];
				}
			});

		$mockClientService = Mockery::mock(ClientService::class);
		$mockClientService->shouldReceive('GetDepartmentDetailServiceClient')->andReturn($mockGrpcClient);
		
		$this->app->instance(ClientService::class, $mockClientService);

		$response = $this->postRequest('api/web/private/user/department/details/1', []);

		$testObjectResponse = $response['testObjectResponse'];
		$payload = $response['payload'];

        $testObjectResponse->assertStatus(200);
	}

	public function test_update_department() {
		Log::info("Updating department...");

		$mockGrpcClient = Mockery::mock(EditDepartmentServiceClient::class);
		$mockGrpcClient->shouldReceive('EditDepartment')
			->andReturn(new class {
				public function wait() {
					$mockResponse = Mockery::mock();
					$mockResponse->shouldReceive('serializeToJsonString')
								->andReturn(file_get_contents(base_path('tests/Fixtures/user.json'), true));
					$mockResponse->shouldReceive('getResult')
								->andReturn(13);
					$mockStatus = new \stdClass();
					$mockStatus->code = \Grpc\STATUS_OK;
					$mockStatus->details = '';

					return [$mockResponse, $mockStatus];
				}
			});

		$mockClientService = Mockery::mock(ClientService::class);
		$mockClientService->shouldReceive('EditDepartmentServiceClient')->andReturn($mockGrpcClient);
		
		$this->app->instance(ClientService::class, $mockClientService);

		$response = $this->postRequest('api/web/private/user/department/edit', [
			'department_id' => 1,
			'department_name' => "Department 1",
			'description' => "Department 1 description: test",
		]);

		$testObjectResponse = $response['testObjectResponse'];
		$payload = $response['payload'];

        $testObjectResponse->assertStatus(200);
	}

	public function test_delete_department() {
		Log::info("Deleting department...");

		$mockGrpcClient = Mockery::mock(DeleteDepartmentServiceClient::class);
		$mockGrpcClient->shouldReceive('DeleteDepartment')
			->andReturn(new class {
				public function wait() {
					$mockResponse = Mockery::mock();
					$mockResponse->shouldReceive('serializeToJsonString')
								->andReturn(file_get_contents(base_path('tests/Fixtures/user.json'), true));
					$mockResponse->shouldReceive('getResult')
								->andReturn(13);
					$mockStatus = new \stdClass();
					$mockStatus->code = \Grpc\STATUS_OK;
					$mockStatus->details = '';

					return [$mockResponse, $mockStatus];
				}
			});

		$mockClientService = Mockery::mock(ClientService::class);
		$mockClientService->shouldReceive('DeleteDepartmentServiceClient')->andReturn($mockGrpcClient);
		
		$this->app->instance(ClientService::class, $mockClientService);

		$response = $this->postRequest('api/web/private/user/department/delete', [
			'department_id' => 1
		]);

		$testObjectResponse = $response['testObjectResponse'];
		$payload = $response['payload'];

        $testObjectResponse->assertStatus(200);
	}

	public function test_get_suggested_department_members() {
		Log::info("Getting department members...");

		$mockGrpcClient = Mockery::mock(SuggestedMemberDepartmentServiceClient::class);
		$mockGrpcClient->shouldReceive('SuggestedMemberDepartment')
			->andReturn(new class {
				public function wait() {
					$mockResponse = Mockery::mock();
					$mockResponse->shouldReceive('serializeToJsonString')
								->andReturn(file_get_contents(base_path('tests/Fixtures/user.json'), true));
					$mockResponse->shouldReceive('getDepartmentLists')
								->andReturn([]);
					$mockStatus = new \stdClass();
					$mockStatus->code = \Grpc\STATUS_OK;
					$mockStatus->details = '';

					return [$mockResponse, $mockStatus];
				}
			});

		$mockClientService = Mockery::mock(ClientService::class);
		$mockClientService->shouldReceive('SuggestedMemberDepartmentServiceClient')->andReturn($mockGrpcClient);
		
		$this->app->instance(ClientService::class, $mockClientService);

		$response = $this->postRequest('api/web/private/suggested/department/members', [
			'department_id' => 1
		]);

		$testObjectResponse = $response['testObjectResponse'];
		$payload = $response['payload'];

        $testObjectResponse->assertStatus(200);
	}

	public function test_get_department_members() {
		Log::info("Getting department members...");

		$mockGrpcClient = Mockery::mock(GetDepartmentMemberServiceClient::class);
		$mockGrpcClient->shouldReceive('GetDepartmentMember')
			->andReturn(new class {
				public function wait() {
					$mockResponse = Mockery::mock();
					$mockResponse->shouldReceive('serializeToJsonString')
								->andReturn(file_get_contents(base_path('tests/Fixtures/user.json'), true));
					$mockResponse->shouldReceive('getDepartmentLists')
								->andReturn([]);
					$mockStatus = new \stdClass();
					$mockStatus->code = \Grpc\STATUS_OK;
					$mockStatus->details = '';

					return [$mockResponse, $mockStatus];
				}
			});

		$mockClientService = Mockery::mock(ClientService::class);
		$mockClientService->shouldReceive('GetDepartmentMemberServiceClient')->andReturn($mockGrpcClient);
		
		$this->app->instance(ClientService::class, $mockClientService);

		$response = $this->postRequest('api/web/private/department/lists', [
			'department_id' => 1
		]);

		$testObjectResponse = $response['testObjectResponse'];
		$payload = $response['payload'];

        $testObjectResponse->assertStatus(200);
	}

}
