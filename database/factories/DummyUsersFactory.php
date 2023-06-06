<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DummyUsersFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
	protected $model = User::class;
    public function definition()
    {
        return [
            'name' => $this->faker->firstName,
            'username' => $this->faker->userName,
            'email' => $faker->unique()->safeEmail,
			'user_type'         =>'user',
			'email_verified_at' => now(),
			'password'          => bcrypt(12345678), // secret
			'remember_token'    => str_random(10),
        ];
    }
}
