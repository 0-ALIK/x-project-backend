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

    //agrega una marca
    public function guardarMarca(Request $request){
    // Validar la solicitud
    $request->validate([
        'nombre' => 'required|unique:marca',
        'descripcion' => 'required',
        'logo' => 'required',
    ]);

    try {
        // Verificar si la marca ya existe
        $nombre = $request->input('nombre');
        $marcaExistente = Marca::where('nombre', $nombre)->first();

        if ($marcaExistente) {
            return response()->json(['error' => 'Ya existe una marca con el mismo nombre.'], 400);
        }

        // Crear una nueva marca
        $nuevaMarca = new Marca;
        $nuevaMarca->nombre = $nombre;
        $nuevaMarca->descripcion = $request->input('descripcion');
        $nuevaMarca->logo = $request->input('logo');
        $nuevaMarca->save();

        // Devolver una respuesta exitosa
        return response()->json(['message' => 'Marca creada con éxito.'], 201);
    } catch (Exception $e) {
        print($e);
        return response()->json(['error' => 'Error al procesar la solicitud.'], 500);
    }
}

    //obtiene una marca en especifico
    public function getMarca($id_marca) {
        try {
            $marca = Marca::findOrFail($id_marca);
    
            return response()->json(["data" => $marca, "status" => 200]);
        } catch (Exception $e) {
            return response()->json(["mensaje" => "Error al obtener la marca", "status" => 500]);
        }
    } 
    
    //actualiza la informacion de una marca en especifico
    public function updateMarca(Request $request, $id_marca) {
        try {
            $marca = Marca::find($id_marca);

            //verifica que la marca exista
            if (!$marca) {
                return response()->json(["mensaje" => "La marca no existe", "status" => 400]);
            }

            $marca->fill($request->all());
            $marca->save();

            return response()->json( ["data" => $marca, "status" => 200] );

        } catch(Exception $e){
            print($e);
            return response()->json( ["mensaje" => "Ocurrió un problema al buscar la marcas", "status" => 404] );
        }
    }

    //eliminar una marca en especifico
    public function deleteMarca($id_marca){
        $marca = Marca::find($id_marca);

        if (!$marca) {
            return response()->json(['error' => 'Marca no encontrada']);
        }

        $marca->delete();

        return response()->json(['message' => 'Marca eliminada correctamente']);
    }
}
