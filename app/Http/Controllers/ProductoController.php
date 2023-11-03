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
            return response()->json( ["mensaje" => "Ocurrió un problema al buscar los productos", "status" => 404] );
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
    
}
