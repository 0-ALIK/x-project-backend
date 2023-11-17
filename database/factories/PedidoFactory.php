<?php

namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Pedido;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pedido>
 */
// database/factories/PedidoFactory.php



class PedidoFactory extends Factory
{
    protected $model = Pedido::class;

    public function definition()
    {
        return [
            'cliente_id' => $this->faker->numberBetween(1, 10),
            'estado_id' => $this->faker->numberBetween(1, 5),
            'direccion_id' => $this->faker->numberBetween(1, 10),
            'detalles' => $this->faker->text,
            'fecha' => $this->faker->dateTimeThisMonth(),
            'fecha_cambio_estado' => $this->faker->dateTimeThisMonth(),
        ];
    }
}

