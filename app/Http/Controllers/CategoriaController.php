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

    //agrega una marca
    public function guardarCategoria(Request $request){
        $request->validate([
            'nombre'=> 'required'
        ]);

        $nombre = $request->input('nombre');
        $categoriaExistente = Categoria::where('nombre', $nombre)->first();
    
        if ($categoriaExistente) {
            return redirect()->back()->with('error', 'Ya existe una categoria con el mismo nombre.');
        }

        $AgregarCategoria = new Categoria;
        $AgregarCategoria->nombre = $nombre;

        //ruta pendiente para agregar la categoria
        return redirect()->route('app/inventario'); // Redirige a la página que desees después de guardar la categoria.
    }

    //actualiza la informacion de una categoria en especifico
    public function updateCategoria(Request $request, $id_categoria) {
        $categoria = Categoria::find($id_categoria);
    
        if (!$categoria) {
            return response()->json(["mensaje" => "La categoria no existe", "status" => 400]);
        }
    
        $categoria->fill($request->all());
        $categoria->save();
    
        return response()->json(["data" => $categoria, "status" => 200]);
    }

    //eliminar una categoria en especifico
    public function deleteCategoria($id_categoria){
        $categoria = Categoria::find($id_categoria);

        if (!$categoria) {
            return response()->json(['error' => 'Categoria no encontrada']);
        }

        $categoria->delete();

        return response()->json(['message' => 'Categoria eliminada correctamente']);
    }
}
