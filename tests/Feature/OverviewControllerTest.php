<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use grpc\Overview\OverviewResponse;
use grpc\Overview\OverviewRequest;
use grpc\Overview\OverviewServiceClient;
use protos_project_1\protos_client\ClientService;
use Tests\TestCase;
use Mockery;
use Log;
use Illuminate\Support\Facades\Redis;
use Tests\FeatureBaseClassTest;

class OverviewControllerTest extends FeatureBaseClassTest {

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

	public function test_overview() {
		Log::info("test_overview");

		$mockGrpcClient = Mockery::mock(OverviewServiceClient::class);
		$mockGrpcClient->shouldReceive('Overview')
			->andReturn(new class {
				public function wait() {
					$mockResponse = Mockery::mock();
					$mockResponse->shouldReceive('serializeToJsonString')
								->andReturn(file_get_contents(base_path('tests/Fixtures/user.json'), true));

					$mockResponse->shouldReceive('getUserAccessCounter')
								->andReturn([
									[
										'created_at' => '2025-07',
										'count' => 5,
									]
								]);

					$mockResponse->shouldReceive('getUserDetailUserRole')
						->andReturn([
							[
								'user_role_id' => 1,
								'count' => 2,
								'user_role' => 'Admin'
							],
							[
								'user_role_id' => 2,
								'count' => 1,
								'user_role' => 'Manager'
							],
							[
								'user_role_id' => 3,
								'count' => 2,
								'user_role' => 'Team Leader'
							],
							[
								'user_role_id' => 4,
								'count' => 5,
								'user_role' => 'Team Member'
							],
							[
								'user_role_id' => 5,
								'count' => 1,
								'user_role' => 'Guest'
							],
						]);

					$mockResponse->shouldReceive('getUserDetail')
						->andReturn([
							[
								'date' => '2025-07',
								'totalUser' => 12,
							],
						]);


					$mockStatus = new \stdClass();
					$mockStatus->code = \Grpc\STATUS_OK;
					$mockStatus->details = '';

					return [$mockResponse, $mockStatus];
				}
			});

		$mockClientService = Mockery::mock(ClientService::class);
		$mockClientService->shouldReceive('OverviewServiceClient')->andReturn($mockGrpcClient);
		
		$this->app->instance(ClientService::class, $mockClientService);

		$response = $this->postRequest('api/web/private/get/overview', [
            'action_by_user_id' => 1,
        ]);

		$testObjectResponse = $response['testObjectResponse'];
		$payload = $response['payload'];

        $testObjectResponse->assertStatus(200);
	}

}
