<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use grpc\CreateTeam\CreateTeamResponse;
use grpc\CreateTeam\CreateTeamRequest;
use grpc\CreateTeam\CreateTeamServiceClient;
use protos_project_1\protos_client\ClientService;
use grpc\TeamLists\TeamListsResponse;
use grpc\TeamLists\teamLists;
use grpc\TeamLists\TeamListsServiceClient;
use Tests\TestCase;
use Mockery;
use Log;
use Illuminate\Support\Facades\Redis;
use Tests\FeatureBaseClassTest;

class TeamsControllerTest extends FeatureBaseClassTest {

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

	public function test_create_team() {
		Log::info("test_create_team");

		$mockGrpcClient = Mockery::mock(CreateTeamServiceClient::class);
		$mockGrpcClient->shouldReceive('CreateTeam')
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
		$mockClientService->shouldReceive('CreateTeamClient')->andReturn($mockGrpcClient);
		
		$this->app->instance(ClientService::class, $mockClientService);

		$response = $this->postRequest('api/web/private/user/teams/create', [
            'team_name' => "Team 1",
			'description' => "Team 1 description: test"
        ]);

		$testObjectResponse = $response['testObjectResponse'];
		$payload = $response['payload'];

        $testObjectResponse->assertStatus(200);
	}

	public function test_get_team_lists() {
		Log::info("test_get_team_lists");

		$mockGrpcClient = Mockery::mock(TeamListsServiceClient::class);
		$mockGrpcClient->shouldReceive('TeamLists')
			->andReturn(new class {
				public function wait() {
					$mockResponse = Mockery::mock();
					$mockResponse->shouldReceive('serializeToJsonString')
								->andReturn(file_get_contents(base_path('tests/Fixtures/user.json'), true));
					
					$response = new TeamListsResponse();			
					$teamArray = [];
					$teams = new teamLists([
						'id' => 1,
						'team_name' => "Test",
						'description' => "Test",
						'created_at' => "2025-05-25T13:26:41.000000Z",
						'updated_at' => "2025-05-25T13:26:41.000000Z"
					]);
					$teamArray[] = $teams;
					$response->setTeamLists($teamArray);

					$mockResponse->shouldReceive('getTeamLists')
								->andReturn($response);

					$mockStatus = new \stdClass();
					$mockStatus->code = \Grpc\STATUS_OK;
					$mockStatus->details = '';

					return [$mockResponse, $mockStatus];
				}
			});
		
		$mockClientService = Mockery::mock(ClientService::class);
		$mockClientService->shouldReceive('TeamListsServiceClient')->andReturn($mockGrpcClient);
		
		$this->app->instance(ClientService::class, $mockClientService);

		$response = $this->postRequest('api/web/private/user/teams/lists', []);

		$testObjectResponse = $response['testObjectResponse'];
		$payload = $response['payload'];

		$testObjectResponse->assertStatus(200);
	}

}
