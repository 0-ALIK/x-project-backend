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
        $request->validate([
            'marca_id'=> 'required',
            'categoria_id'=> 'required',
            'nombre'=> 'required',
            'precio_unit'=> 'required',
            'cantidad_por_caja'=> 'required',
            'foto'=> 'required',
            'punto_reorden'=> 'required',
            'cantidad_caja'=> 'required'
        ]);

        //Validación para comprobar si ya existe un producto con el mismo nombre
        $nombre = $request->input('nombre');
        $productoExistente = Producto::where('nombre', $nombre)->first();
    
        if ($productoExistente) {
            return redirect()->back()->with('error', 'Ya existe un producto con el mismo nombre.');
        }

        $AgregarProducto = new Producto;
        $AgregarProducto->marca_id = $request->input('marca');
        $AgregarProducto->categoria_id = $request->input('categoria');
        $AgregarProducto->nombre = $nombre;
        $AgregarProducto->precio_unit = $request->input('precio_unit');
        $AgregarProducto->cantidad_por_caja = $request->input('cantidad_por_caja');
        $AgregarProducto->foto = $request->input('foto');
        $AgregarProducto->punto_reorden = $request->input('punto_reorden');
        $AgregarProducto->cantidad_caja = $request->input('cantidad_caja');
        $AgregarProducto->save();
        
        return redirect()->route('app/inventario'); // Redirige a la página que desees después de guardar el producto.
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
    public function deleteProducto($id) {
        try {
            $producto = Producto::findOrFail($id);
            $producto->delete();

            return redirect()->route('productos.index')->with('success', 'Producto eliminado con éxito');
        } catch (Exception $e) {
            return back()->with('error', 'Producto no encontrado');
        }
    }    
}
