<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Redis;
use App\Services\AuthUserService;
use grpc\CreateShift\CreateShiftServiceClient;
use protos_project_1\protos_client\ClientService;
use App\Controllers\UserShiftController;
use Tests\TestCase;
use Carbon\Carbon;
use Mockery;
use Log;

class UserShiftTest extends FeatureBaseClassTest {

    public function test_create_user_shift(): void {
		Log::info("test_create_user_shift");

		$mockGrpcClient = Mockery::mock(CreateShiftServiceClient::class);
		$mockGrpcClient->shouldReceive('CreateShift')
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
		$mockClientService->shouldReceive('CreateUserShiftServiceClient')->andReturn($mockGrpcClient);

		$this->app->instance(ClientService::class, $mockClientService);

		$response = $this->postRequest('/api/web/private/user/shift/create', [
			'shift_name' => 'Test Shift',
			'description' => 'Test Shift'
		]);

		$testObjectResponse = $response['testObjectResponse'];
		$payload = $response['payload'];
		Log::debug($payload);
		$testObjectResponse->assertStatus(200);
    }

}
