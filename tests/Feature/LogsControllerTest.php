<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use grpc\GetLogs\GetLogsResponse;
use grpc\GetLogs\GetLogsRequest;
use grpc\GetLogs\GetLogsServiceClient;
use protos_project_1\protos_client\ClientService;
use Tests\TestCase;
use Mockery;
use Log;
use Illuminate\Support\Facades\Redis;
use Tests\FeatureBaseClassTest;

class LogsControllerTest extends FeatureBaseClassTest {

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

	public function test_get_logs() {
		Log::info("Get logs");

		$mockGrpcClient = Mockery::mock(GetLogsServiceClient::class);
		$mockGrpcClient->shouldReceive('GetLogs')
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
		$mockClientService->shouldReceive('GetLogsServiceClient')->andReturn($mockGrpcClient);
		
		$this->app->instance(ClientService::class, $mockClientService);

		$response = $this->postRequest('api/web/private/get/logs', [
            'action_by_user_id' => 1,
        ]);

		$testObjectResponse = $response['testObjectResponse'];
		$payload = $response['payload'];

        $testObjectResponse->assertStatus(200);
	}

}
