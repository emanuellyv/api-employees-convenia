<?php

namespace Database\Factories;

use App\Models\Manager;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'       => $this->faker->name,
            'email'      => $this->faker->unique()->safeEmail,
            'cpf'        => $this->faker->numerify('###########'),
            'city'       => $this->faker->city,
            'state'      => $this->faker->stateAbbr,
            'manager_id' => Manager::factory(),
        ];
    }
}
