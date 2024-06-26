<?php

namespace Database\Factories\Ubigeo;

use App\Models\Ubigeo\Province;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ubigeo\District>
 */
class DistrictFactory extends Factory
{
    protected $model = \App\Models\Ubigeo\District::class;

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
            'province_id' => Province::factory(),
            'code' => (string) $this->faker->unique()->numberBetween(100000, 999999),
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
        ];
    }
}
