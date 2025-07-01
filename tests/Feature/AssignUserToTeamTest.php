<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use grpc\userClockIn\UserClockInServiceClient;
use grpc\userClockOut\UserClockOutServiceClient;
use Tests\TestCase;
use Mockery;
use protos_project_1\protos_client\ClientService;
use grpc\AssignUserToTeam\AssignUserToTeamServiceClient;
use Illuminate\Support\Facades\Redis;
use Log;

class AssignUserToTeamTest extends FeatureBaseClassTest {

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

    public function test_assign_user_to_team(): void {
		$mockGrpcClient = Mockery::mock(AssignUserToTeamServiceClient::class);
		$mockGrpcClient->shouldReceive('AssignUser')
			->andReturn(new class {
				public function wait() {
					$mockResponse = Mockery::mock();
					$mockResponse->shouldReceive('serializeToJsonString')
								->andReturn(json_encode(['result' => true]));
					$mockResponse->shouldReceive('getResult')  
								->andReturn(true);

					$mockStatus = new \stdClass();
					$mockStatus->code = \Grpc\STATUS_OK;
					$mockStatus->details = '';

					return [$mockResponse, $mockStatus];
				}
			});

		$mockClientService = Mockery::mock(ClientService::class);
		$mockClientService->shouldReceive('AssignUserToTeam')->andReturn($mockGrpcClient);
		
		$this->app->instance(ClientService::class, $mockClientService);

		$response = $this->postRequest('/api/web/private/assign/user/to/teams', [
			'team_id' => 1,
			'user_id' => [1]
		]);

		$testObjectResponse = $response['testObjectResponse'];
		$payload = $response['payload'];

		$testObjectResponse->assertStatus(200);
    }

}
