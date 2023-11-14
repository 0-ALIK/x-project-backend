<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto;

class AgregarProductoController extends Controller
{
    public function guardarProducto(Request $request){
        $request->validate([
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
        $AgregarProducto->nombre = $nombre;
        $AgregarProducto->precio_unit = $request->input('precio_unit');
        $AgregarProducto->cantidad_por_caja = $request->input('cantidad_por_caja');
        $AgregarProducto->foto = $request->input('foto');
        $AgregarProducto->punto_reorden = $request->input('punto_reorden');
        $AgregarProducto->cantidad_caja = $request->input('cantidad_caja');
        $AgregarProducto->save();
        
        return redirect()->route('app/inventario'); // Redirige a la página que desees después de guardar el producto.
    }

}
