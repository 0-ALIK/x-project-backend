<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Reclamo;
use App\Models\RCategoria;
use App\Models\Estado;
use App\Models\Prioridad;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

use Exception;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isEmpty;

class ReclamoController extends Controller
{
    public function guardarReclamo(Request $request) {
        $id_usuario = $request->user()->currentAccessToken()->tokenable_id;
        $cliente = Cliente::where('usuario_id', $id_usuario)->first();

        if (!Storage::disk('public')->exists('documento_reclamo')){
            Storage::disk('public')->makeDirectory('documento_reclamo');
        }

        if (isset($request['evidencia'])) {
            $evidencia = $request['evidencia']->storeOnCloudinary('documento_empresa');
            $evidenciaFile = $evidencia->getSecurePath();
        } else {
            $evidenciaFile = '-';
        }


        $existeReclamo = Reclamo::where('pedido_id', $request['pedido_id'])->first();
        if ( !isset($existeReclamo) ) {

            $prio = 1;

            switch($request['categoria']){
                case 1:
                    $prio = 1;
                    break;
                case 2:
                    $prio = 2;
                    break;
                case 3:
                    $prio = 3;
                    break;
            }
            // Hacemos el insert a la base de datos con los datos que nos llegaron del reclamo
            try {
                Reclamo::create(
                    [
                        'cliente_id'    => $cliente->id_cliente,
                        'pedido_id'     => $request['pedido_id'],
                        'categoria_id'  => $request['categoria'],
                        'descripcion'   => $request['descripcion'],
                        'evidencia'     => $evidenciaFile,
                        'prioridad_id'  => $prio,
                        'estado_id'     => 1
                    ]
                );
            } catch (Exception $e) {
                print($e);
                return response()->json( ["mensaje" => "Ocurrió un error"], 500, ['Access-Control-Allow-Origin' => 'http://localhost:4200'] );
            }
            return response()->json( ["mensaje" => "Reclamo registrado"], 200, ['Access-Control-Allow-Origin' => 'http://localhost:4200'] );
        } else {
            return response()->json( ["mensaje" => "Ya has registrado un reclamo a este pedido"], 400, ['Access-Control-Allow-Origin' => 'http://localhost:4200'] );
        }
    }

    public function getAllReclamos() {
        try {
            $reclamos = Reclamo::with(['pedido','pedido.estado', 'cliente.usuario', 'categoria', 'prioridad', 'estado'])->get();

            if ($reclamos->isEmpty()) {
                return response()->json(["mensaje" => "No hay reclamos disponibles"], 404);
            }

            return response()->json(["data" => $reclamos], 200);
        } catch(Exception $e){
            print($e);
            return response()->json(["mensaje" => "Ocurrió un problema al buscar los reclamos"], 500);
        }
    }

    // Retorna los reclamos de un cliente en especifico
    public function getReclamosCliente($cliente_id) {
        try {
            $reclamos = Reclamo::with(['pedido','pedido.estado', 'cliente.usuario', 'categoria', 'prioridad', 'estado'])->where('cliente_id', $cliente_id)->get();

            if ($reclamos->isEmpty()) {
                return response()->json(["mensaje" => "Cliente no tiene reclamos"], 404);
            }


            return response()->json(["data" => $reclamos], 200);
        } catch(Exception $e){
            print($e);
            return response()->json(["mensaje" => "Ocurrió un problema al buscar los reclamos"], 500);
        }
    }

    // Retorna un reclamo en especifico
    public function getReclamoById($reclamo_id) {
        try {
            $reclamo = Reclamo::with(['pedido','pedido.estado','cliente.usuario', 'categoria', 'prioridad', 'estado'])->find($reclamo_id);

            if (!$reclamo) {
                return response()->json(["mensaje" => "Reclamo no existe"], 404);
            }

            return response()->json(["data" => $reclamo], 200);
        } catch (\Exception $e) {
            print($e);
            return response()->json(["mensaje" => "Ocurrió un problema al buscar el reclamo"], 500);
        }
    }

    // Actualiza la prioridad del reclamo
    public function updatePrioridad(Request $request, $reclamo_id) {
        $reclamo = Reclamo::find($reclamo_id);
        $prioridad_id = $request->input('prioridad_id');

        if (!$prioridad_id || ($prioridad_id < 1 && $prioridad_id > 3)) {
            print($prioridad_id);
            return response()->json(["mensaje" => "Parametro PRIORIDAD incorrecto"], 400, ['Access-Control-Allow-Origin' => 'http://localhost:4200']);
        }

        if (!$reclamo) {
            return response()->json(["mensaje" => "Reclamo no encontrado"], 400, ['Access-Control-Allow-Origin' => 'http://localhost:4200']);
        }

        if ($reclamo->estado == 3) {
            return response()->json(["mensaje" => "Reclamo ha sido resuelto"], 400, ['Access-Control-Allow-Origin' => 'http://localhost:4200']);;
        }

        $reclamo->prioridad_id = $prioridad_id;

        try {
            $reclamo->save();
        } catch (Exception $e) {
            return response()->json(["mensaje" => "No se pudo actualizar la prioridad"], 500, ['Access-Control-Allow-Origin' => 'http://localhost:4200']);
        }

        return response()->json(["data" => $reclamo], 200, ['Access-Control-Allow-Origin' => 'http://localhost:4200']);
    }

    // Actualiza el estado del reclamo
    public function updateEstado(Request $request, $reclamo_id) {
        $reclamo = Reclamo::find($reclamo_id);

        $estado_id = $request->input('estado_id');

        if (!$estado_id || ($estado_id < 1 && $estado_id > 3)) {
            return response()->json(["mensaje" => "Parametro ESTADO incorrecto"], 400, ['Access-Control-Allow-Origin' => 'http://localhost:4200']);
        }

        if (!$reclamo) {
            return response()->json(["mensaje" => "Reclamo no encontrado"], 400, ['Access-Control-Allow-Origin' => 'http://localhost:4200']);
        }

        if ($reclamo->estado_id == 3) {
            return response()->json(["mensaje" => "Reclamo ya ha sido resuelto"], 400, ['Access-Control-Allow-Origin' => 'http://localhost:4200']);
        }

        $reclamo->estado_id = $estado_id;

        try {
            $reclamo->save();
        } catch (Exception $e) {
            return response()->json(["mensaje" => "No se pudo actualizar el estado"], 500, ['Access-Control-Allow-Origin' => 'http://localhost:4200']);
        }

        return response()->json(["data" => $reclamo], 200, ['Access-Control-Allow-Origin' => 'http://localhost:4200']);
    }

    public function getAllCategorias() {
        try {
            $categorias = RCategoria::all();
            return response()->json( ["data" => $categorias], 200 );

        } catch(Exception $e){
            print($e);
            return response()->json( ["mensaje" => "Ocurrió un problema al buscar las categorías"], 404 );
        }
    }

    public function getAllEstados() {
        try {
            $estados = Estado::all();
            return response()->json( ["data" => $estados], 200 );

        } catch(Exception $e){
            print($e);
            return response()->json( ["mensaje" => "Ocurrió un problema al buscar los estados"], 404 );
        }
    }

    public function getAllPrioridades() {
        try {
            $prioridades = Prioridad::all();
            return response()->json( ["data" => $prioridades], 200 );

        } catch(Exception $e){
            print($e);
            return response()->json( ["mensaje" => "Ocurrió un problema al buscar los estados"], 404 );
        }
    }
}
