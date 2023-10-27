<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Categoria;
use App\Models\Prioridad;
use App\Models\Reclamo;
use App\Models\Estado;
use App\Models\Sugerencia;

use Illuminate\Support\Carbon;



class Modulo4Seeder extends Seeder
{
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

        # EJEMPLO DE SUGERENCIA
        Sugerencia::create(
            [
                'cliente_id'    => 1,
                'contenido'     => 'Me gustaría que agregaran más opciones de pago',
                'valoracion'    => 4,
                'fecha' => Carbon::now()
            ]
        );
    }
}
