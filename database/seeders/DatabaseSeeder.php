<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Factories\UserFactory;
use Database\Factories\UserDetailFactory;
use Database\Factories\UserTypeFactory;
use Database\Factories\UserUserTypeFactory;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void {
		$this->UserSeeder();
    }

	private function UserSeeder() {
		$user = (new UserFactory())->create();

		(new UserDetailFactory())->create([
			'user_id' => $user->id, 
		]);

		$userType = (new UserTypeFactory())->create();

		(new UserUserTypeFactory())->create([
			'user_id' => $user->id,
			'user_type_id' => $userType->id,
		]);
	}
}
