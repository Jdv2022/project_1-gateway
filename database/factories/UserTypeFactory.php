<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserType>
 */
class UserTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
			'user_type_name' => 'admin',
			'user_type_description' => 'Superuser, has all previledges.',
			'user_type_icon' => 'mat:admin_panel_settings',
			'user_type_color' => '#D32F2F',
			'hierarchy_level' => 0,

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
        ];
    }
}
