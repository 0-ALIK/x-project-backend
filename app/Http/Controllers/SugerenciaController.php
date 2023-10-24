<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sugerencia;
use Exception;
class SugerenciaController extends Controller
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

    public function guardarSugerencia(Request $request)
    {
        try{
            Sugerencia::create(
                [
                    'cliente_id'    => $request['cliente_id'],
                    'contenido'     => $request['contenido'],
                    'fecha'         => $request['fecha'],
                    'valoracion'    => $request['valoracion']
                ]
            );
        } catch (Exception $e) {
            print($e);
            return "No se pudo realizar la sugerencia";
        }
        return "Sugerencia registrada";
    }

    /**
     * Display the specified resource.
     */
    public function show(Sugerencia $sugerencia)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sugerencia $sugerencia)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sugerencia $sugerencia)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sugerencia $sugerencia)
    {
        //
    }
}