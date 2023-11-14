<?php

namespace App\Http\Controllers;

use App\Models\Categoria;

use Exception;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function getAllCategorias() {
        try {
            $categorias = Categoria::all();
            return response()->json( ["data" => $categorias, "status" => 200] );

        } catch(Exception $e){
            print($e);
            return response()->json( ["mensaje" => "Ocurrió un problema al buscar las categorias", "status" => 404] );
        }
    }

    //actualiza la informacion de un producto en especifico
    public function updateProducto(Request $request, $id_marca) {
        $categoria = Categoria::find($id_marca);
    
        if (!$categoria) {
            return response()->json(["mensaje" => "La categoria no existe", "status" => 400]);
        }
    
        $categoria->fill($request->all());
        $categoria->save();
    
        return response()->json(["data" => $categoria, "status" => 200]);
    }
}
