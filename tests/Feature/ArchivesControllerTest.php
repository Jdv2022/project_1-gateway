<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Mockery;
use protos_project_1\protos_client\ClientService;
use grpc\GetArchives\GetArchivesServiceClient;
use grpc\AddArchive\AddArchiveServiceClient;
use grpc\RemoveArchive\RemoveArchiveServiceClient;
use Illuminate\Support\Facades\Redis;
use Tests\FeatureBaseClassTest;
use Log;

class ArchivesControllerTest extends FeatureBaseClassTest {

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

    public function test_get_archives(): void {
		Log::info("Testing get archives");

		$mockGrpcClient = Mockery::mock(GetArchivesServiceClient::class);
		$mockGrpcClient->shouldReceive('GetArchives')
			->andReturn(new class {
				public function wait() {
					$mockResponse = Mockery::mock();
					$mockResponse->shouldReceive('serializeToJsonString')
								->andReturn(json_encode([
									'id' => 1,
									'first_name' => 'JD',
									'middle_name' => 'TEST',
									'last_name' => "LD",
									'position' => "TL",
									'archived_reason' => "NX",
									'archived_at' => "2021-01-01 00:00:00"
								]));
					$mockResponse->shouldReceive('getArchives')  
								->andReturn([
									'id' => 1,
									'first_name' => 'JD',
									'middle_name' => 'TEST',
									'last_name' => "LD",
									'position' => "TL",
									'archived_reason' => "NX",
									'archived_at' => "2021-01-01 00:00:00"
								]);

					$mockStatus = new \stdClass();
					$mockStatus->code = \Grpc\STATUS_OK;
					$mockStatus->details = '';

					return [$mockResponse, $mockStatus];
				}
			});

		$mockClientService = Mockery::mock(ClientService::class);
		$mockClientService->shouldReceive('GetArchivesServiceClient')->andReturn($mockGrpcClient);
		
		$this->app->instance(ClientService::class, $mockClientService);

		$response = $this->postRequest('/api/web/private/get/users/archives', [
			'action_by_user_id' => 1,
		]);

		$testObjectResponse = $response['testObjectResponse'];
		$payload = $response['payload'];

		$testObjectResponse->assertStatus(200);
    }

	public function test_add_archive(): void {
		Log::info("Testing add archive");

		$mockGrpcClient = Mockery::mock(AddArchiveServiceClient::class);
		$mockGrpcClient->shouldReceive('AddArchive')
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
		$mockClientService->shouldReceive('AddArchiveServiceClient')->andReturn($mockGrpcClient);
		
		$this->app->instance(ClientService::class, $mockClientService);

		$response = $this->postRequest('/api/web/private/add/users/archives', [
			'action_by_user_id' => 1,
			'user_id' => 2,
			'timezone' => 'test',
			'archive_reason' => 'test',
		]);

		$testObjectResponse = $response['testObjectResponse'];
		$payload = $response['payload'];

		$testObjectResponse->assertStatus(200);
	}

	public function test_remove_archive(): void {
		Log::info("Testing remove archive");

		$mockGrpcClient = Mockery::mock(RemoveArchiveServiceClient::class);
		$mockGrpcClient->shouldReceive('RemoveArchive')
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
		$mockClientService->shouldReceive('RemoveArchiveServiceClient')->andReturn($mockGrpcClient);
		
		$this->app->instance(ClientService::class, $mockClientService);

		$response = $this->postRequest('/api/web/private/remove/users/archives', [
			'action_by_user_id' => 1,
			'user_id' => 2,
			'timezone' => 'test',
		]);

		$testObjectResponse = $response['testObjectResponse'];
		$payload = $response['payload'];

		$testObjectResponse->assertStatus(200);
	}

}
