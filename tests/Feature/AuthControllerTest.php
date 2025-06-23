<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Redis;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use Mockery;
use Log;
use grpc\GetUserDetails\GetUserDetailsServiceClient;
use grpc\GetUserDetails\GetUserDetailsResponse;
use protos_project_1\protos_client\ClientService;
use stdClass;

class AuthControllerTest extends FeatureBaseClassTest {
	protected function tearDown(): void {
        Mockery::close();
        parent::tearDown();
    }
    /**
     * A basic feature test example.
     */
	public function test_wrong_password() {
        $response = $this->postRequest('api/web/login', [
            'username' => 'jhon'
        ]);

		$testObjectResponse = $response['testObjectResponse'];
		$payload = $response['payload'];

        $testObjectResponse->assertStatus(500);

		$response = $this->postRequest('api/web/login', [
            'password' => '123123aA?'
        ]);

		$testObjectResponse = $response['testObjectResponse'];
		$payload = $response['payload'];
		
        $testObjectResponse->assertStatus(500);

		$response = $this->postRequest('api/web/login', [
            'username' => 'superuser',
            'password' => '123123aA?'
        ]);

		$testObjectResponse = $response['testObjectResponse'];
		$payload = $response['payload'];

		$status = $testObjectResponse->getStatusCode();
        $this->assertEquals(404, $status, "Incorrect credentials [username][password] should result in response code 404 user not found.");

	}
	public function test_auth_web_login() {
		$userData = json_decode(file_get_contents(base_path('tests/Fixtures/user.json')), true);
		Redis::shouldReceive('set')->andReturn($userData);
		Redis::shouldReceive('get')->andReturn($userData);
		$mockGrpcClient = Mockery::mock(GetUserDetailsServiceClient::class);
		$mockGrpcClient->shouldReceive('GetUserDetails')
			->andReturn(new class {
				public function wait() {
					$mockResponse = Mockery::mock();
					$mockResponse->shouldReceive('serializeToJsonString')
						->andReturn(json_encode(['first_name' => 'John', 'last_name' => 'Doe']));
		
					$status = new \stdClass();
					$status->code = \Grpc\STATUS_OK;
					$status->details = '';
					return [$mockResponse, $status];
				}
			});
		
		$mockClientService = Mockery::mock(ClientService::class);
		$mockClientService->shouldReceive('getUserServiceClient')->andReturn($mockGrpcClient);
		
		$this->app->instance(ClientService::class, $mockClientService);

        $response = $this->postRequest('/api/web/login', [
            'username' => 'superuser',
            'password' => '123123aA',
        ]);
		$content = $response['payload'];
		$content['isEncrypted'] = (int) $content['isEncrypted']; 
        $this->assertIsInt($content['isEncrypted']);
		$this->assertIsString($content['payload']['access_token'], "Expected access_token to be a string.");
        $this->assertIsInt($content['payload']['token_expire'], "Expected token_expire to be a string.");
	}

	public function test_token_validation() {
		$userId = 1;
        $redisKey = 'user_' . $userId;

        $userDataArray = json_decode(file_get_contents(base_path('tests/Fixtures/user.json')), true);
        $userJson = json_encode($userDataArray);

        Redis::shouldReceive('get')
            ->once()
            ->with($redisKey)
            ->andReturn($userJson);
			
        $response = $this->postRequest('api/web/private/validate/token', [], false);

		$testObjectResponse = $response['testObjectResponse'];
		$payload = $response['payload'];

        $testObjectResponse->assertStatus(200);
    }

	public function test_resfresh_token() {
        $response = $this->postRequest('api/web/private/refresh/token', []);

		$testObjectResponse = $response['testObjectResponse'];
		$payload = $response['payload'];	
		$testObjectResponse->assertStatus(200);
    }
}
