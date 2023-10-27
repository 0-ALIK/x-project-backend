<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Empresa;
use App\Models\Direccion;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Empresa_direcciones>
 */
class EmpresaDireccionesFactory extends Factory
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
            'empresa_id' => Empresa::factory(),
            'direccion_id' => Direccion::factory(),
        ];
    }
}
