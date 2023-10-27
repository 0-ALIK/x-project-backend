<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Provincia;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Direccion>
 */
class DireccionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'provincia_id' => Provincia::factory(),
            'codigo_postal' => $this->faker->randomNumber(),
            'telefono' => $this->faker->phoneNumber(),
            'detalles'=> $this->faker->text(),
        ];
    }
}
