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
        $request->validate([
            'nombre'=> 'required',
            'descripcion'=> 'required',
            'logo'=> 'required'
        ]);

        $nombre = $request->input('nombre');
        $marcaExistente = Marca::where('nombre', $nombre)->first();
    
        if ($marcaExistente) {
            return redirect()->back()->with('error', 'Ya existe una marca con el mismo nombre.');
        }

        $AgregarMarca = new Marca;
        $AgregarMarca->nombre = $nombre;
        $AgregarMarca->descripcion = $request->input('descripcion');
        $AgregarMarca->logo = $request->input('logo');
        $AgregarMarca->save();

        return redirect()->route('app/inventario'); // Redirige a la página que desees después de guardar la marca.
    }

    //actualiza la informacion de una marca en especifico
    public function updateMarca(Request $request, $id_marca) {
        $marca = Marca::find($id_marca);
    
        if (!$marca) {
            return response()->json(["mensaje" => "La marca no existe", "status" => 400]);
        }
    
        $marca->fill($request->all());
        $marca->save();
    
        return response()->json(["data" => $marca, "status" => 200]);
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
