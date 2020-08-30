<?php

namespace Orchestra\Model\Testing\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Model\Role;
use Orchestra\Support\Str;

class RoleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Role::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
        ];
    }
}
