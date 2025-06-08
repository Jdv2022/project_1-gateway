<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use grpc\userClockIn\UserClockInServiceClient;
use grpc\userClockOut\UserClockOutServiceClient;
use Tests\TestCase;
use Mockery;
use protos_project_1\protos_client\ClientService;

class AttendanceControllerTest extends FeatureBaseClassTest {

	public function test_set_clock_in(): void {
		$mockGrpcClient = Mockery::mock(UserClockInServiceClient::class);
		$mockGrpcClient->shouldReceive('UserClockInService')
			->andReturn(new class {
				public function wait() {
					$mockResponse = Mockery::mock();
					$mockResponse->shouldReceive('serializeToJsonString')
								->andReturn(json_encode(['result' => true]));

					$mockStatus = new \stdClass();
					$mockStatus->code = \Grpc\STATUS_OK;
					$mockStatus->details = '';

					return [$mockResponse, $mockStatus];
				}
			});

		$mockClientService = Mockery::mock(ClientService::class);
		$mockClientService->shouldReceive('clockIn')->andReturn($mockGrpcClient);
		
		$this->app->instance(ClientService::class, $mockClientService);

		$response = $this->postRequest('/api/web/private/user/attendance/clock/in', [
			'timezone' => 'America/New_York',
			'fk' => 1
		]);

		$testObjectResponse = $response['testObjectResponse'];
		$payload = $response['payload'];

		$testObjectResponse->assertStatus(200);
    }

	public function test_clock_out() {
		$mockGrpcClient = Mockery::mock(UserClockOutServiceClient::class);
		$mockGrpcClient->shouldReceive('SetUserClockOut')
			->andReturn(new class {
				public function wait() {
					$mockResponse = Mockery::mock();
					$mockResponse->shouldReceive('serializeToJsonString')
								->andReturn(json_encode(['result' => true]));

					$mockStatus = new \stdClass();
					$mockStatus->code = \Grpc\STATUS_OK;
					$mockStatus->details = '';

					return [$mockResponse, $mockStatus];
				}
			});

		$mockClientService = Mockery::mock(ClientService::class);
		$mockClientService->shouldReceive('clockOut')->andReturn($mockGrpcClient);
		
		$this->app->instance(ClientService::class, $mockClientService);

		$response = $this->postRequest('/api/web/private/user/attendance/clock/out', [
			'timezone' => 'America/New_York',
			'fk' => 1
		]);

		$testObjectResponse = $response['testObjectResponse'];
		$payload = $response['payload'];

		$testObjectResponse->assertStatus(200);
	}

}
