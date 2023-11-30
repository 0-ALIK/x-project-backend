<?php

namespace App\Http\Controllers;

use App\Models\Producto;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ProductoController extends Controller
{
    public function getAllProductos() {
        try {
            $productos = Producto::all();
            return response()->json( ["data" => $productos, "status" => 200] );

        } catch(Exception $e){
            print($e);
            return response()->json( ["mensaje" => "Ocurrió un problema al obtener los productos", "status" => 404] );
        }
    }

    //agrega un nuevo producto
    public function guardarProducto(Request $request){
        // Validar la solicitud
        $request->validate([
            'marca_id' => 'required',
            'categoria_id' => 'required',
            'nombre' => 'required|unique:producto',
            'precio_unit' => 'required',
            'cantidad_por_caja' => 'required',
            'foto' => 'required|image|mimes:png,jpg',
            'punto_reorden' => 'required',
            'cantidad_caja' => 'required',
        ]);

        try {
            // Validación para comprobar si ya existe un producto con el mismo nombre
            $nombre = $request->input('nombre');
            $productoExistente = Producto::where('nombre', $nombre)->first();

            if ($productoExistente) {
                return response()->json(['error' => 'Ya existe un producto con el mismo nombre.'], 400);
            }

            // Verificar si la imagen es válida
            if (!$request->file('foto')->isValid()) {
                return response()->json(['error' => 'Archivo de imagen no válido.'], 400);
            }

            // Verificar el tipo de archivo
            $extension = $request->file('foto')->getClientOriginalExtension();
            if ($extension != 'jpg' && $extension != 'png') {
                return response()->json(['error' => 'Tipo de archivo no soportado. Solo se permiten archivos jpg y png.'], 400);
            }

            //crea una carpeta con el nombre marca_logo
            if (!Storage::disk('public')->exists('producto_foto')){
                Storage::disk('public')->makeDirectory('producto_foto');
            }

            // almacena el archivo en la carpeta 'producto_foto'
            $result = $request->file('foto')->storeOnCloudinary('producto_foto');

            // Crear un nuevo producto
            $nuevoProducto = new Producto;
            $nuevoProducto->marca_id = $request->input('marca_id');
            $nuevoProducto->categoria_id = $request->input('categoria_id');
            $nuevoProducto->nombre = $nombre;
            $nuevoProducto->precio_unit = $request->input('precio_unit');
            $nuevoProducto->cantidad_por_caja = $request->input('cantidad_por_caja');
            $nuevoProducto->foto = $result->getSecurePath(); //obtiene la ruta en donde esta almacenado el archivo
            $nuevoProducto->punto_reorden = $request->input('punto_reorden');
            $nuevoProducto->cantidad_caja = $request->input('cantidad_caja');
            $nuevoProducto->save();

            // Devolver una respuesta exitosa
            return response()->json(['message' => 'Producto creado con éxito.'], 201);
        } catch (Exception $e) {
            // Manejar cualquier error y devolver una respuesta de error
            return response()->json(['error' => 'Error al insertar el producto'], 500);
        }
    }

     //obtiene una producto en especifico
     public function getProducto($id_producto) {
        try {
            $producto = Producto::findOrFail($id_producto);

            return response()->json(["data" => $producto, "status" => 200]);
        } catch (Exception $e) {
            print($e);
            return response()->json(["mensaje" => "Error al obtener la marca", "status" => 500]);
        }
    }

    //actualiza la informacion de un producto en especifico
    public function updateProducto(Request $request, $id_producto) {
        $producto = Producto::find($id_producto);

        if (!$producto) {
            return response()->json(["mensaje" => "El producto no existe", "status" => 400]);
        }

        //verifica si se ha subido un archivo
        if ($request->hasFile('foto')){
            // Verificar el tipo de archivo
            $extension = $request->file('foto')->getClientOriginalExtension();
            if ($extension != 'jpg' && $extension != 'png') {
                return response()->json(['error' => 'Tipo de archivo no soportado. Solo se permiten archivos jpg y png.'], 400);
            }
        }

        //verifica si el nombre de la marca ha sido cambiado
        if ($request->input('nombre') != $producto->nombre){
            //verifica que la nueva marca no este en la bd
            if (Producto::where('nombre', $request->input('nombre'))->first()) {
                return response()->json(['error' => 'Ya existe una marca con el mismo nombre.'], 400);
            } else {
                $producto->nombre = $request->input('nombre');
            }
        }

        //obtiene el public_id de la url de la imagen
        $key = explode('/', pathinfo(parse_url($producto->foto, PHP_URL_PATH), PATHINFO_DIRNAME));
        $public_id = end($key) . '/' . pathinfo(parse_url($producto->foto, PHP_URL_PATH), PATHINFO_FILENAME);

        Cloudinary::destroy($public_id);
        $result = $request->file('foto')->storeOnCloudinary('producto_foto');

        $producto->fill($request->all());
        $producto->foto = $result->getSecurePath();
        $producto->save();

        return response()->json(["data" => $producto, "status" => 200]);
    }

    //elimina un producto en especifico
    public function deleteProducto($id_producto){
        try {
            $producto = Producto::find($id_producto);

            if (!$producto) {
                return response()->json(['error' => 'Producto no encontrado'], 404);
            }

            //obtiene el public_id de la url de la imagen
            $key = explode('/', pathinfo(parse_url($producto->foto, PHP_URL_PATH), PATHINFO_DIRNAME));
            $public_id = end($key) . '/' . pathinfo(parse_url($producto->foto, PHP_URL_PATH), PATHINFO_FILENAME);

            //borra la imagen en donde esta almacenada
            Cloudinary::destroy($public_id);

            $producto->delete();

            return response()->json(['message' => 'Producto eliminado correctamente'], 201);
        } catch (Exception $e) {
            print($e);
            return response()->json(['error' => 'Error al procesar la solicitud.'], 500);
        }
    }
}
