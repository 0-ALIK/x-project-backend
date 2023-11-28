<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'logo' => 'required|image|mimes:png,jpg',
        ]);

        try {
            // Verificar si la marca ya existe
            $nombre = $request->input('nombre');
            $marcaExistente = Marca::where('nombre', $nombre)->first();

            if ($marcaExistente) {
                return response()->json(['error' => 'Ya existe una marca con el mismo nombre.'], 400);
            }

            // Verificar si la imagen es válida
            if (!$request->file('logo')->isValid()) {
                return response()->json(['error' => 'Archivo de imagen no válido.'], 400);
            }

            // Verificar el tipo de archivo
            $extension = $request->file('logo')->getClientOriginalExtension();
            if ($extension != 'jpg' && $extension != 'png') {
                return response()->json(['error' => 'Tipo de archivo no soportado. Solo se permiten archivos jpg y png.'], 400);
            }

            //crea una carpeta con el nombre marca_logo
            if (!Storage::disk('public')->exists('marca_logo')){
                Storage::disk('public')->makeDirectory('marca_logo');
            }

            // almacena el archivo en la carpeta 'marca_logo'
            $result = $request->file('logo')->storeOnCloudinary('marca_logo');

            // Crear una nueva marca
            $nuevaMarca = new Marca;
            $nuevaMarca->nombre = $nombre;
            $nuevaMarca->descripcion = $request->input('descripcion');
            $nuevaMarca->logo = $result->getSecurePath(); //obtiene la ruta en donde esta almacenado el archivo
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

            if ($request->hasfile)

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
