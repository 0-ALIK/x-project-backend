<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Usuario>
 */
class UsuarioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $rol = $this->faker->randomElement(['cliente', 'empresa']);
        $nombre = $rol =='cliente' ? $this->faker->firstName(): $this->faker->company();
        return [
            'nombre' => $nombre,
            'correo' => $this->faker->unique()->safeEmail(),
            'pass' => $this->faker->password(),
            'rol' => $rol,
            'foto' => $this->faker->imageUrl(),
            'telefono' => $this->faker->phoneNumber(),
            'detalles' => $this->faker->text(),
        ];
    }
}
