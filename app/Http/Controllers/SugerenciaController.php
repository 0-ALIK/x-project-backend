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
            return response()->json(["mensaje" => "No se pudo registrar la sugerencia", "status" => 400]);
        }
        return response()->json(["mensaje" => "Sugerencia registrada", "status" => 200]);
    }

    //Aqui se obtienen las sugerencias guardadas
    public function getSugerencia(){
        try {
            $sugerencias = Sugerencia::get();
            return $sugerencias;
        } catch (Exception $e) {
            return "No se pudo obtener las sugerencias";
        }
    }
}