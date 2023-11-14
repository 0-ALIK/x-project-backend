<?php

namespace App\Http\Controllers;

use App\Models\Marca;

use Exception;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
    public function getAllMarcas() {
        try {
            $marcas = Marca::all();
            return response()->json( ["data" => $marcas, "status" => 200] );

        } catch(Exception $e){
            print($e);
            return response()->json( ["mensaje" => "Ocurrió un problema al buscar las marcas", "status" => 404] );
        }
    }

    //actualiza la informacion de un producto en especifico
    public function updateProducto(Request $request, $id_marca) {
        $marca = Marca::find($id_marca);
    
        if (!$marca) {
            return response()->json(["mensaje" => "La marca no existe", "status" => 400]);
        }
    
        $marca->fill($request->all());
        $marca->save();
    
        return response()->json(["data" => $marca, "status" => 200]);
    }
}
