<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DeviceType>
 */
class DeviceTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "device_type_name" => $this->faker->word,
            "device_model_number" => $this->faker->word,
            "device_description" => $this->faker->text,
        ];
    }
}
