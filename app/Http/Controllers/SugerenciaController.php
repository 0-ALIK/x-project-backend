<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sugerencia;
use Illuminate\Support\Carbon;

use Exception;

class SugerenciaController extends Controller
{
    public function guardarSugerencia(Request $request)
    {
        try{
            Sugerencia::create(
                [
                    'cliente_id'    => $request['cliente_id'],
                    'contenido'     => $request['contenido'],
                    'valoracion'    => $request['valoracion'],
                    'fecha'         => Carbon::now()
                ]
            );
        } catch (Exception $e) {
            print($e);
            return response()->json(["mensaje" => "No se pudo registrar la sugerencia"], 500, ['Access-Control-Allow-Origin' => 'http://localhost:4200']);
        }
        return response()->json(["mensaje" => "Sugerencia registrada"], 200, ['Access-Control-Allow-Origin' => 'http://localhost:4200']);
    }

    //Aqui se obtienen las sugerencias guardadas
    public function getSugerencia()
    {
        try {
            $sugerencias = Sugerencia::join('cliente', 'sugerencia.cliente_id', '=', 'cliente.id_cliente')
                ->join('usuario', 'cliente.usuario_id', '=', 'usuario.id_usuario')
                ->select(
                    'sugerencia.id_sugerencia',
                    'sugerencia.cliente_id',
                    'usuario.nombre as usuario_nombre',
                    'sugerencia.contenido',
                    'sugerencia.fecha',
                    'sugerencia.valoracion'
                )
                ->get();
    
            return response()->json(["sugerencias" => $sugerencias], 200);
        } catch (Exception $e) {
            return response($e, 500);
        }
    }
}