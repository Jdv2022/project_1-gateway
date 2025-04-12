<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Factories\UserFactory;
use Database\Factories\UserDetailFactory;
use Database\Factories\UserTypeFactory;
use Database\Factories\UserUserTypeFactory;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void {
		$this->UserSeeder();
    }

	private function UserSeeder() {
		if(!User::exists()) {
			(new UserFactory())->create();
			$this->command->info("Initial user populated!");
		}
		else{
			$this->command->info("Initial user already exist!");
		}
	}
}
