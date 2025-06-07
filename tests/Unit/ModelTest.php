<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;

class ModelTest extends TestCase {
    /**
     * A basic unit test example.
     */
    public function test_getJWTIdentifier_returns_primary_key() {
		$user = User::factory()->make(['id' => 123]);
		$this->assertEquals(123, $user->getJWTIdentifier());
	}

	public function test_getJWTCustomClaims_returns_empty_array() {
		$user = User::factory()->make();
		$this->assertEquals([], $user->getJWTCustomClaims());
	}

	public function test_hidden_attributes_are_not_visible_in_json() {
		$user = User::factory()->make(['password' => 'secret', 'remember_token' => 'token']);
		$array = $user->toArray();

		$this->assertArrayNotHasKey('password', $array);
		$this->assertArrayNotHasKey('remember_token', $array);
	}
}
