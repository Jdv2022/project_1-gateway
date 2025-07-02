<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\AuthUserService;
use Illuminate\Support\Facades\Redis;
use Mockery;

class AuthUserServiceTest extends TestCase {
    /**
     * A basic unit test example.
     */
    protected function tearDown(): void {
        Mockery::close();
        parent::tearDown();
    }

    public function test_authUser_returns_user_data_from_redis() {
        $userId = 1;
        $redisKey = 'user_' . $userId;

        $userDataArray = json_decode(file_get_contents(base_path('tests/Fixtures/user.json')), true);
        $userJson = json_encode($userDataArray);

        Redis::shouldReceive('get')
            ->once()
            ->with($redisKey)
            ->andReturn($userJson);

        $service = new AuthUserService($userId, 'UCT');

        $this->assertEquals($userDataArray, $service->authUser());
    }
}
