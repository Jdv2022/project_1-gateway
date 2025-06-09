<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use grpc\CreateTeam\CreateTeamResponse;
use grpc\CreateTeam\CreateTeamRequest;
use grpc\CreateTeam\CreateTeamServiceClient;
use protos_project_1\protos_client\ClientService;
use Tests\TestCase;
use Mockery;
use Log;

class TeamsControllerTest extends FeatureBaseClassTest {

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

}
