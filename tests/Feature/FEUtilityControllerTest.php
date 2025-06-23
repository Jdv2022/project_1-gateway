<?php

namespace Tests\Feature;

use Tests\TestCase;
use Log;
use DateTime;
use Illuminate\Support\Facades\Redis;

class FEUtilityControllerTest extends FeatureBaseClassTest {

    public function test_day_today_returns_formatted_datetime() {

		$userId = 1;
		$redisKey = 'user_' . $userId;

        $userDataArray = json_decode(file_get_contents(base_path('tests/Fixtures/user.json')), true);
        $userJson = json_encode($userDataArray);

        Redis::shouldReceive('get')
            ->once()
            ->with($redisKey)
            ->andReturn($userJson);
		
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
		$userId = 1;
		$redisKey = 'user_' . $userId;

        $userDataArray = json_decode(file_get_contents(base_path('tests/Fixtures/user.json')), true);
        $userJson = json_encode($userDataArray);

        Redis::shouldReceive('get')
            ->once()
            ->with($redisKey)
            ->andReturn($userJson);

        $response = $this->postRequest('api/web/private/user/attendance/day/today', []);
		$testObjectResponse = $response['testObjectResponse'];
		$payload = $response['payload'];
        $this->assertEquals(1, $payload['error']);
    }
}
