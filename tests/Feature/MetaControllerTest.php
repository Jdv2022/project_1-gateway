<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Log;
use Tests\FeatureBaseClassTest;

class MetaControllerTest extends FeatureBaseClassTest {
    /**
     * A basic feature test example.
     */
	public function test_meta_data() {
        $response = $this->postRequest('api/meta/data', [], false);

		$testObjectResponse = $response['testObjectResponse'];
		$payload = $response['payload'];

		$testObjectResponse->assertStatus(200);
		$this->assertIsString($payload['payload']['gateway_version'], "Expected gateway_version to be a string.");
		$this->assertIsString($payload['payload']['database_version'], "Expected database_version to be a string.");
		$this->assertIsString($payload['payload']['frontend_version'], "Expected frontend_version to be a string.");
		$this->assertIsString($payload['payload']['cue'], "Expected cue to be a string.");
    }
}
