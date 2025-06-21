<?php

namespace Tests\Feature;

use Tests\TestCase;
use Log;
use DateTime;

class FEUtilityControllerTest extends FeatureBaseClassTest {

    public function test_day_today_returns_formatted_datetime() {
        $timezone = 'Asia/Manila';

        $response = $this->postRequest('api/web/private/user/attendance/day/today', [
            'timezone' => $timezone
        ]);
		$testObjectResponse = $response['testObjectResponse'];
		$payload = $response['payload'];

        $testObjectResponse->assertStatus(200);
		$this->assertTrue(isset($payload['payload']['date']));
		$this->assertTrue(isset($payload['payload']['time']));
    }

    public function test_day_today_requires_timezone() {
        $response = $this->postRequest('api/web/private/user/attendance/day/today', []);
		$testObjectResponse = $response['testObjectResponse'];
		$payload = $response['payload'];
        $this->assertEquals(1, $payload['error']);
    }
}
