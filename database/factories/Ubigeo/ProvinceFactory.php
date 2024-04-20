<?php

namespace Database\Factories\Ubigeo;

use App\Models\Ubigeo\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ubigeo\Province>
 */
class ProvinceFactory extends Factory
{
    protected $model = \App\Models\Ubigeo\Province::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $this->faker->locale('es_PE');

        return [
            'name' => $this->faker->text(20),
            'department_id' => Department::factory(),
            'code' => (string) $this->faker->unique()->numberBetween(1000, 9999),
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
        ];
    }
}
