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

    public function getAllReclamos(){
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reclamo $reclamo)
    {
        //
    }
}
