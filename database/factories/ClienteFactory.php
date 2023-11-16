<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Usuario;
use App\Models\Empresa;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cliente>
 */
class ClienteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'usuario_id' => Usuario::factory(),
            'empresa_id' => Empresa::factory(),
            'apellido' => $this->faker->lastName(),
            'cedula' => $this->faker->unique()->numerify('##########'),
            'genero' => $this->faker->randomElement(['M', 'F']),
            'estado' => 'activo',
        ];
    }
}
