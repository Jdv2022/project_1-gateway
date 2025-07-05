<?php

namespace Tests;

use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use App\Http\Middleware\Encrypt;
use App\Http\Middleware\Decrypt;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Log;

class FeatureBaseClassTest extends TestCase {
	use RefreshDatabase;
    protected $token;

	protected function tearDown(): void {
		Schema::dropIfExists('users');
		parent::tearDown();
	}

    protected function setUp(): void {
        parent::setUp();
		if (!Schema::hasTable('users')) {
			Schema::create('users', function (Blueprint $table) {
				$table->id();
				$table->string('username', 45)->unique();
				$table->string('password', 500);
				$table->boolean('is_active')->default(false);
				
				$table->datetime('created_at');
				$table->string('created_at_timezone', 10)->nullable();
				$table->integer('created_by_user_id')->nullable();
				$table->string('created_by_username', 45)->nullable();
				$table->string('created_by_user_type', 45)->nullable();
				$table->datetime('updated_at');
				$table->string('updated_at_timezone', 10)->nullable();
				$table->integer('updated_by_user_id')->nullable();
				$table->string('updated_by_username', 45)->nullable();
				$table->string('updated_by_user_type', 45)->nullable();
				$table->boolean('enabled')->default(true);
			});
		}

        $user = User::factory()->create();
        $this->token = JWTAuth::fromUser($user);
    }

    /**
     * Helper to attach Authorization header and other headers (like encryption)
     */
    protected function withAuthHeaders(array $headers = []) {
        $defaultHeaders = [
            'Authorization' => "Bearer {$this->token}",
			'Timezone' => 'UTC'
        ];
        return array_merge($defaultHeaders, $headers);
    }

    /**
     * Helper to make authenticated JSON POST request easily
     */
    protected function postRequest(
		string $uri, 
		array|null $data = [], 
		bool $isEncrypted = true, 
		bool $isFile = true, 
		array $headers = []
	) {
		$enc = new Encrypt();
		if($isEncrypted) {
			$encData = $enc->encryptData(json_encode($data));
		}
		else {
			$encData = $data;
		}

		$requestData = [
			'endpoint' => $uri,
			'params' => [],
			'payload' => $encData,
			'isEncrypt' => $isEncrypted,
			'isfile' => false,
			'file' => null,
			'metaData' => null,
		];
		
		$response = $this->postJson($uri, $requestData, $this->withAuthHeaders($headers));
		$original = $response->original;

		if($isEncrypted) {
			$dec = new Decrypt();
			$original['payload'] = $dec->decryptData($original['payload'] ?? []);
			return [
				'payload' => $original,
				'testObjectResponse' => $response
			];
		}
		else {
			return [
				'payload' => $original,
				'testObjectResponse' => $response
			];
		}
    }

	protected function postRequestAttachment(
		string $uri, 
		array|null $data = [], 
		bool $isEncrypted = true, 
		bool $isFile = true, 
		array $headers = []
	) {
		$enc = new Encrypt();
		if($isEncrypted) {
			$encData = $enc->encryptData(json_encode($data));
		}
		else {
			$encData = $data;
		}

		$requestData = [
			'endpoint' => $uri,
			'params' => [],
			'payload' => $encData,
			'isEncrypt' => $isEncrypted,
			'isfile' => false,
			'file' => null,
			'metaData' => null,
		];
		
		$response = $this->post($uri, $requestData, $this->withAuthHeaders($headers));
		$original = $response->original;

		if($isEncrypted) {
			$dec = new Decrypt();
			$original['payload'] = $dec->decryptData($original['payload'] ?? '');
			return [
				'payload' => $original,
				'testObjectResponse' => $response
			];
		}
		else {
			return [
				'payload' => $original,
				'testObjectResponse' => $response
			];
		}
    }

}