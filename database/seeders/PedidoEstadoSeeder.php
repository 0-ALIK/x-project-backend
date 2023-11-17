<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PedidoEstado;


class PedidoEstadoSeeder extends Seeder
{
    public function run()
    {
        // Crea algunos estados ficticios
        PedidoEstado::create(['nombre' => 'Estado 1']);
        PedidoEstado::create(['nombre' => 'Estado 2']);
        PedidoEstado::create(['nombre' => 'Estado 3']);
    }
}

