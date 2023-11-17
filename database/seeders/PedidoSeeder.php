<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pedido;
use App\Models\PedidoEstado;
use App\Models\Cliente;
use Illuminate\Support\Facades\DB;

class PedidoSeeder extends Seeder
{
    public function run()
    {
        // Obtén todos los estados de pedido existentes
        $estadosPedido = PedidoEstado::all();

        // Verifica si hay estados de pedido
        if ($estadosPedido->isEmpty()) {
            $this->command->info('No hay estados de pedido para asociar a los pedidos.');
            return;
        }

        // Obtén el cliente con ID 1
        $cliente = Cliente::find(1);

        // Verifica si el cliente existe
        if (!$cliente) {
            $this->command->info('No se encontró el cliente con ID 1.');
            return;
        }

        // Crea pedidos asociados a estados de pedido existentes y al cliente con ID 1
        Pedido::factory(10)->create()->each(function ($pedido) use ($cliente, $estadosPedido) {
            $estadoPedido = $estadosPedido->random();
            $pedido->cliente()->associate($cliente);
            $pedido->estado()->associate($estadoPedido);
            $pedido->save();
        });

        $this->command->info('Pedidos creados y asociados a estados de pedido existentes y al cliente con ID 1.');
    }
}

