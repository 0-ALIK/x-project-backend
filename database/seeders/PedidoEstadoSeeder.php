<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PedidoEstado;


class PedidoEstadoSeeder extends Seeder
{
    public function run()
    {
        // Puedes ajustar los nombres de los estados según tus necesidades
        $estados = [
            ['nombre' => 'Proceso'],
            ['nombre' => 'Enviado'],
            ['nombre' => 'Recibido'],
            // Agrega más estados según sea necesario
        ];

        PedidoEstado::insert($estados);
    }
}

