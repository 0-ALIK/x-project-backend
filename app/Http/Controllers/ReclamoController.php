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
