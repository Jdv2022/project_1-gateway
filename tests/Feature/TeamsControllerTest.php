<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use grpc\CreateTeam\CreateTeamServiceClient;
use grpc\DeleteTeam\DeleteTeamServiceClient;
use protos_project_1\protos_client\ClientService;
use grpc\TeamLists\teamLists;
use grpc\TeamLists\TeamListsResponse;
use grpc\TeamLists\TeamListsServiceClient;
use grpc\TeamUsersLists\TeamUsersListsResponse;
use grpc\TeamUsersLists\TeamUsersListsServiceClient;
use grpc\EditTeam\EditTeamServiceClient;
use grpc\SuggestedMember\SuggestedMemberServiceClient;
use grpc\RemoveUserTeam\RemoveUserTeamServiceClient;
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
								->andReturn(13);
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

	public function test_team_details() {
		Log::info("test_team_details");

		$mockGrpcClient = Mockery::mock(TeamUsersListsServiceClient::class);
		$mockGrpcClient->shouldReceive('TeamUsersLists')
			->andReturn(new class {
				public function wait() {
					$mockResponse = Mockery::mock();
					$mockResponse->shouldReceive('serializeToJsonString')
								->andReturn(file_get_contents(base_path('tests/Fixtures/user.json'), true));

					$mockResponse->shouldReceive('getTeamUsersLists')
								->andReturn([]);

					$mockStatus = new \stdClass();
					$mockStatus->code = \Grpc\STATUS_OK;
					$mockStatus->details = '';

					return [$mockResponse, $mockStatus];
				}
			});
		
		$mockClientService = Mockery::mock(ClientService::class);
		$mockClientService->shouldReceive('TeamUsersListsServiceClient')->andReturn($mockGrpcClient);
		
		$this->app->instance(ClientService::class, $mockClientService);

		$response = $this->postRequest('api/web/private/user/team/details/' . 1, []);

		$testObjectResponse = $response['testObjectResponse'];
		$payload = $response['payload'];

		$testObjectResponse->assertStatus(200);
	}

	public function test_suggested_member() {
		Log::info("test_suggested_member");

		$mockGrpcClient = Mockery::mock(SuggestedMemberServiceClient::class);
		$mockGrpcClient->shouldReceive('SuggestedMember')
			->andReturn(new class {
				public function wait() {
					$mockResponse = Mockery::mock();
					$mockResponse->shouldReceive('serializeToJsonString')
								->andReturn(file_get_contents(base_path('tests/Fixtures/user.json'), true));

					$mockResponse->shouldReceive('getTeamLists')
								->andReturn([]);

					$mockStatus = new \stdClass();
					$mockStatus->code = \Grpc\STATUS_OK;
					$mockStatus->details = '';

					return [$mockResponse, $mockStatus];
				}
			});
		
		$mockClientService = Mockery::mock(ClientService::class);
		$mockClientService->shouldReceive('SuggestedMemberServiceClient')->andReturn($mockGrpcClient);
		
		$this->app->instance(ClientService::class, $mockClientService);

		$response = $this->postRequest('api/web/private/user/team/suggested/members', []);

		$testObjectResponse = $response['testObjectResponse'];
		$payload = $response['payload'];

		$testObjectResponse->assertStatus(200);
	}

	public function test_edit_team() {
		Log::info("test_edit_team");
		
		$mockGrpcClient = Mockery::mock(EditTeamServiceClient::class);
		$mockGrpcClient->shouldReceive('EditTeam')
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
		$mockClientService->shouldReceive('EditTeamServiceClient')->andReturn($mockGrpcClient);
		
		$this->app->instance(ClientService::class, $mockClientService);

		$response = $this->postRequest('api/web/private/user/teams/edit', [
            'team_name' => "Team 1",
			'description' => "Team 1 description: test",
			'team_id' => 1
        ]);

		$testObjectResponse = $response['testObjectResponse'];
		$payload = $response['payload'];

        $testObjectResponse->assertStatus(200);
	}

	public function test_remove_user_team() {
		Log::info("test_remove_user_team");

		$mockGrpcClient = Mockery::mock(RemoveUserTeamServiceClient::class);
		$mockGrpcClient->shouldReceive('RemoveUserTeam')
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
		$mockClientService->shouldReceive('RemoveUserTeamServiceClient')->andReturn($mockGrpcClient);
		
		$this->app->instance(ClientService::class, $mockClientService);

		$response = $this->postRequest('api/web/private/user/team/remove', [
			'team_id' => 1,
			'user_id' => 1
        ]);

		$testObjectResponse = $response['testObjectResponse'];
		$payload = $response['payload'];

        $testObjectResponse->assertStatus(200);
	}

	public function test_delete_user() {
		Log::info("test_delete_user");

		$mockGrpcClient = Mockery::mock(DeleteTeamServiceClient::class);
		$mockGrpcClient->shouldReceive('DeleteTeam')
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
		$mockClientService->shouldReceive('DeleteTeamServiceClient')->andReturn($mockGrpcClient);
		
		$this->app->instance(ClientService::class, $mockClientService);
		Log::debug("request");
		$response = $this->postRequest('api/web/private/user/team/delete', [
			'team_id' => 1,
			'user_id' => 1
		]);

		$testObjectResponse = $response['testObjectResponse'];
		$payload = $response['payload'];

		$testObjectResponse->assertStatus(200);
	}

}
