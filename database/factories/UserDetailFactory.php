<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserDetail>
 */
class UserDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
			'first_name' => 'super',
            'middle_name' => "superuser",
			'last_name' => "user",
            'email' => "master@localhost",
            'phone' => "0000000000",
			'address' => "localhost",
			'country' => "localhost",
			'date_of_birth' => "2000-01-01",
			'age' => 25,
			'gender' => true,
			'profile_image' => "localhost",
			'department' => "localhost",
			'position' => "localhost",

            'created_at' => now(),
            'created_at_timezone' => '+08:00',
            'created_by_user_id' => 0,
            'created_by_username' => 'factory',
            'created_by_user_type' => 'dev',
            'updated_at' => now(),
            'updated_at_timezone' => '+08:00',
            'updated_by_user_id' => 0,
            'updated_by_username' => 'factory',
            'updated_by_user_type' => 'dev',

			'user_id' => 1,
        ];
    }
}
