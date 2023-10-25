<?php

namespace App\Http\Controllers;

use App\Models\Reclamo;
use App\Models\Categoria;
use App\Models\Estado;
use App\Models\Prioridad;

use Exception;
use Illuminate\Http\Request;

class ReclamoController extends Controller
{
    public function guardarReclamo(Request $request) {
        //Solo se acepta un reclamo por pedido
        $existeReclamo = Reclamo::where('pedido_id', $request['pedido_id'])->first();
        if ( !isset($existeReclamo) ) {
            //Empieza con prioridad baja
            $prio = 1;
            // Dependiendo de la categoria cambia la prioridad (Sujeto a cambio)
            switch($request['categoria']){
                case 1:
                    $prio = 2;
                case 2:
                    $prio = 3;
                case 3:
                    $prio = 3;
            }
            // Hacemos el insert a la base de datos con los datos que nos llegaron del reclamo
            try {
                Reclamo::create(
                    [
                        'cliente_id'    => $request['cliente_id'],
                        'pedido_id'     => $request['pedido_id'],
                        'categoria_id'  => $request['categoria'],
                        'descripcion'   => $request['descripcion'],
                        'evidencia'     => $request['evidencia'],
                        'prioridad_id'  => $prio
                    ]
                );
            } catch (Exception $e) {
                print($e);
                return response()->json( ["mensaje" => "Ocurrió un error", "status" => 500] );
            }
            return response()->json( ["mensaje" => "Reclamo registrado", "status" => 200] );
        } else {
            return response()->json( ["mensaje" => "Ya has registrado un reclamo a este pedido", "status" => 400] );
        }
    }

    public function getAllReclamos() {
        try {
            $reclamos = Reclamo::all();
            return response()->json( ["data" => $reclamos, "status" => 200] );

        } catch(Exception $e){
            print($e);
            return response()->json( ["mensaje" => "Ocurrió un problema al buscar los reclamos", "status" => 404] );
        }
    }

    public function getAllCategorias() {
        try {
            $categorias = Categoria::all();
            return response()->json( ["data" => $categorias, "status" => 200] );

        } catch(Exception $e){
            print($e);
            return response()->json( ["mensaje" => "Ocurrió un problema al buscar las categorías", "status" => 404] );
        }
    }

    public function getAllEstados() {
        try {
            $estados = Estado::all();
            return response()->json( ["data" => $estados, "status" => 200] );

        } catch(Exception $e){
            print($e);
            return response()->json( ["mensaje" => "Ocurrió un problema al buscar los estados", "status" => 404] );
        }
    }

    public function getAllPrioridades() {
        try {
            $prioridades = Prioridad::all();
            return response()->json( ["data" => $prioridades, "status" => 200] );

        } catch(Exception $e){
            print($e);
            return response()->json( ["mensaje" => "Ocurrió un problema al buscar los estados", "status" => 404] );
        }
    }

    
}
