<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Categoria;
use App\Models\Prioridad;
use App\Models\Reclamo;
use App\Models\Estado;


class Modulo4Seeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void {
        # PRIORIDADES POR DEFECTO
        $dataPrioridades = array('baja', 'media', 'alta');
        # ESTADOS POR DEFECTO
        $dataEstados = array('espera', 'revisado', 'resuelto');
        # CATEGORÍAS POR DEFECTO
        $dataCategorias = array('retraso', 'equivocado', 'dañado', 'devolución', 'otro');
        
        foreach($dataPrioridades as $data) {
            Prioridad::create(
                ['prioridad' => $data]
            );
        }

        foreach($dataCategorias as $data) {
            Categoria::create(
                ['categoria' => $data]
            );
        }

        foreach($dataEstados as $data) {
            Estado::create(
                ['estado' => $data]
            );
        }

        # EJEMPLO DE RECLAMO
        Reclamo::create(
            [
                'cliente_id'    => 1,
                'pedido_id'     => 1,
                'categoria_id'  => 1,
                'descripcion'   => 'Pedí el paquete ayer y todavía no me llega',
                'evidencia'     => '',
                'prioridad_id'  => 1
            ]
        );
    }
}
