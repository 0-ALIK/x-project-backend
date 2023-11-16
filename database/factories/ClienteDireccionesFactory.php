<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Cliente;
use App\Models\Direccion;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cliente_direcciones>
 */
class ClienteDireccionesFactory extends Factory
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
            'cliente_id' => Cliente::factory(),
            'direccion_id' => Direccion::factory(),
        ];
    }
}
