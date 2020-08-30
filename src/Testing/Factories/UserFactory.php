<?php

namespace Orchestra\Model\Testing\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Model\User;
use Orchestra\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'email' => $this->faker->safeEmail,
            'fullname' => $this->faker->name,
            'password' => '$2y$04$Ri4Tj1yi9EnO6EI3lS11suHnymOKbC63D85NeHHo74uk4dHe9eah2',
            'remember_token' => Str::random(10),
            'status' => User::VERIFIED,
        ];
    }
}
