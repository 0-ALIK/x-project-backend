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

    
    public function guardarReclamo(Request $request)
    {
        //Empieza con prioridad baja
        $prio = 1;
        // Dependiendo de la categoria cambia la prioridad
        switch($request['categoria']){
            case 'retraso':
                $prio = 2;
        }
        // Hacemos el insert a la base de datos con los datos que nos llegaron del reclamo
        try{
            Reclamo::create(
                [
                    'cliente_id'    => $request['cliente_id'],
                    'pedido_id'     => $request['pedido_id'],
                    'categoria'     => $request['categoria'],
                    'descripcion'   => $request['descripcion'],
                    'evidencia'     => $request['evidencia'],
                    'prioridad_id'  => $prio
                ]
            );
        } catch (Exception $e) {
            print($e);
            return "No se pudo registrar el reclamo";
        }
        

        return "Reclamo registrado";
    }

    /**
     * Display the specified resource.
     */
    public function show(Reclamo $reclamo)
    {
        //
    }

    // Retorna los reclamos de un cliente en especifico
    public function getReclamosCliente($cliente_id)
    {
        $reclamos = Reclamo::where('cliente_id', $cliente_id)->get();

        if (!$reclamos) {
            return response()->json(['error' => "Cliente no existe"]);
        }

        return response()->json($reclamos);
    }

    // Retorna un reclamo en especifico
    public function getReclamoById($reclamo_id)
    {
        $reclamo = Reclamo::where('id', $reclamo_id)->first();

        if (!$reclamo) {
            return response()->json(['error' => "Reclamo no existe"]);
        }

        return response()->json($reclamo);
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
    public function updatePrioridad(Request $request, $reclamo_id)
    {
        $reclamo = Reclamo::find($reclamo_id);
        $prioridad_id = $request->input('prioridad_id');

        if (!$prioridad_id || ($prioridad_id < 1 && $prioridad_id > 3)) {
            print($prioridad_id);
            return response()->json(['error' => "Parametro PRIORIDAD incorrecto"]);
        }

        if (!$reclamo) {
            return response()->json(['error' => "Reclamo no encontrado"]);
        }

        if ($reclamo->estado == "resuelto") {
            return response()->json(['error' => "Reclamo ha sido resuelto"]);;
        }

        $reclamo->prioridad_id = $prioridad_id;

        try {
            $reclamo->save();
        } catch (Exception $e) {
            return response()->json(['error' => "No se pudo actualizar la prioridad"]);
        }

        return response()->json($reclamo);
    }

    // Actualiza el estado del reclamo
    public function updateEstado(Request $request, $reclamo_id)
    {
        $reclamo = Reclamo::find($reclamo_id);
        $estado = $request->input('estado');

        if (!$estado || !in_array($estado, ["espera", "revisado", "resuelto"])) {
            return response()->json(['error' => "Parametro ESTADO incorrecto"]);
        }

        if (!$reclamo) {
            return response()->json(['error' => "Reclamo no encontrado"]);
        }

        if ($reclamo->estado == "resuelto") {
            return response()->json(['error' => "Reclamo ya ha sido resuelto"]);
        }

        $reclamo->estado = $estado;

        try {
            $reclamo->save();
        } catch (Exception $e) {
            return response()->json(['error' => "No se pudo actualizar el estado"]);
        }

        return response()->json($reclamo);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reclamo $reclamo)
    {
        //
    }
}
