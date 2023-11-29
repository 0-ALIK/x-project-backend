<?php

namespace App\Http\Controllers;

use App\Models\Producto;

use Exception;
use Illuminate\Http\Request;

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
            'foto' => 'required',
            'punto_reorden' => 'required',
            'cantidad_cajas' => 'required',
        ]);

        try {
            // Validación para comprobar si ya existe un producto con el mismo nombre
            $nombre = $request->input('nombre');
            $productoExistente = Producto::where('nombre', $nombre)->first();

            if ($productoExistente) {
                return response()->json(['error' => 'Ya existe un producto con el mismo nombre.'], 400);
            }

            // Crear un nuevo producto
            $nuevoProducto = new Producto;
            $nuevoProducto->marca_id = $request->input('marca_id');
            $nuevoProducto->categoria_id = $request->input('categoria_id');
            $nuevoProducto->nombre = $nombre;
            $nuevoProducto->precio_unit = $request->input('precio_unit');
            $nuevoProducto->cantidad_por_caja = $request->input('cantidad_por_caja');
            $nuevoProducto->foto = $request->input('foto');
            $nuevoProducto->punto_reorden = $request->input('punto_reorden');
            $nuevoProducto->cantidad_cajas = $request->input('cantidad_cajas');
            $nuevoProducto->save();

            // Devolver una respuesta exitosa
            return response()->json(['message' => 'Producto creado con éxito.'], 201);
        } catch (Exception $e) {
            // Manejar cualquier error y devolver una respuesta de error
            return response()->json(['error' => 'Error al procesar la solicitud.'], 500);
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
    
        $producto->fill($request->all());
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
            $producto->delete();

            return response()->json(['message' => 'Producto eliminado correctamente'], 404);
        } catch (Exception $e) {
            print($e);
            return response()->json(['error' => 'Error al procesar la solicitud.'], 500);
        }
    }    
}
