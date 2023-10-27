<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Usuario;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Empresa>
 */
class EmpresaFactory extends Factory
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
            'ruc' => $this->faker->unique()->numerify('##########'),
            'razon_social' => $this->faker->company(),
            'documento' => $this->faker->imageUrl(),
            'estado' => $this->faker->randomElement(['aprobado', 'pendiente', 'denegado']),
        ];
    }
}
