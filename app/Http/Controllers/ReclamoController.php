<?php

namespace App\Http\Controllers;

use App\Models\Reclamo;
use Exception;
use Illuminate\Http\Request;

class ReclamoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function guardarReclamo(Request $request) {
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
    }

    public function getAllReclamos() {
        try {
            $reclamos = Reclamo::all();
            return response()->json($reclamos);

        } catch(Exception $e){
            print($e);
            return response()->json( ["mensaje" => "Ocurrió un problema al buscar los reclamos", "status" => 404] );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Reclamo $reclamo)
    {
        //
    }

    // Retorna los reclamos de un cliente en especifico
    public function getReclamosCliente($cliente_id) {
        $reclamos = Reclamo::where('cliente_id', $cliente_id)->get();

        if (!$reclamos) {
            return response()->json(["mensaje" => "Cliente no existe", "status" => 400]);
        }

        return response()->json(["data" => $reclamos, "status" => 200]);
    }

    // Retorna un reclamo en especifico
    public function getReclamoById($reclamo_id) {
        $reclamo = Reclamo::where('id', $reclamo_id)->first();

        if (!$reclamo) {
            return response()->json(["mensaje" => "Reclamo no existe", "status" => 400]);
        }

        return response()->json(["data" => $reclamo, "status" => 200]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reclamo $reclamo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reclamo $reclamo)
    {
        //
    }

    // Actualiza la prioridad del reclamo
    public function updatePrioridad(Request $request, $reclamo_id) {
        $reclamo = Reclamo::find($reclamo_id);
        $prioridad_id = $request->input('prioridad_id');

        if (!$prioridad_id || ($prioridad_id < 1 && $prioridad_id > 3)) {
            print($prioridad_id);
            return response()->json(["mensaje" => "Parametro PRIORIDAD incorrecto", "status" => 400]);
        }

        if (!$reclamo) {
            return response()->json(["mensaje" => "Reclamo no encontrado", "status" => 400]);
        }

        if ($reclamo->estado == 3) {
            return response()->json(["mensaje" => "Reclamo ha sido resuelto", "status" => 400]);;
        }

        $reclamo->prioridad_id = $prioridad_id;

        try {
            $reclamo->save();
        } catch (Exception $e) {
            return response()->json(["mensaje" => "No se pudo actualizar la prioridad", "status" => 500]);
        }

        return response()->json(["data" => $reclamo, "status" => 200]);
    }

    // Actualiza el estado del reclamo
    public function updateEstado(Request $request, $reclamo_id) {
        $reclamo = Reclamo::find($reclamo_id);
        $estado_id = $request->input('estado_id');

        if (!$estado_id || ($estado_id < 1 && $estado_id > 3)) {
            return response()->json(["mensaje" => "Parametro ESTADO incorrecto", "status" => 400]);
        }

        if (!$reclamo) {
            return response()->json(["mensaje" => "Reclamo no encontrado", "status" => 400]);
        }

        if ($reclamo->estado_id == 3) {
            return response()->json(["mensaje" => "Reclamo ya ha sido resuelto", "status" => 400]);
        }

        $reclamo->estado_id = $estado_id;

        try {
            $reclamo->save();
        } catch (Exception $e) {
            return response()->json(["mensaje" => "No se pudo actualizar el estado", "status" => 500]);
        }

        return response()->json(["data" => $reclamo, "status" => 200]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reclamo $reclamo)
    {
        //
    }
}
