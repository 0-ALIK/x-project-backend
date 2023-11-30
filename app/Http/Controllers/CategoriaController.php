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
        // Validar la solicitud
        $request->validate([
            'nombre' => 'required|unique:categoria',
        ]);

        try {
            // Verificar si la categoría ya existe
            $nombre = $request->input('nombre');
            $categoriaExistente = Categoria::where('nombre', $nombre)->first();

            if ($categoriaExistente) {
                return response()->json(['error' => 'Ya existe una categoría con el mismo nombre.'], 400);
            }

            // Crear una nueva categoría
            $nuevaCategoria = new Categoria;
            $nuevaCategoria->nombre = $nombre;
            $nuevaCategoria->save();

            // Devolver una respuesta exitosa
            return response()->json(['message' => 'Categoría creada con éxito.'], 201);
        } catch (Exception $e) {
            print($e);
            return response()->json(['error' => 'Error al procesar la solicitud.'], 500);
        }
    }

    //obtiene una categoria en especifico
    public function getCategoria($id_categoria) {
        try {
            $categoria = Categoria::findOrFail($id_categoria);
    
            return response()->json(["data" => $categoria, "status" => 200]);
        } catch (Exception $e) {
            print($e);
            return response()->json(["mensaje" => "Error al obtener la marca", "status" => 500]);
        }
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
