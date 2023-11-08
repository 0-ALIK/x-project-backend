<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Marca;

class AgregarMarcaController extends Controller
{
    public function guardarMarca(Request $request){
        $request->validate([
            'nombre'=> 'required',
            'descripcion'=> 'required',
            'logo'=> 'required'
        ]);

        $nombre = $request->input('nombre');
        $productoExistente = Marca::where('nombre', $nombre)->first();
    
        if ($productoExistente) {
            return redirect()->back()->with('error', 'Ya existe un producto con el mismo nombre.');
        }

        $AgregarMarca = new Marca;
        $AgregarMarca->nombre = $nombre;
        $AgregarMarca->precio_unit = $request->input('precio_unit');
        $AgregarMarca->cantidad_por_caja = $request->input('cantidad_por_caja');
        $AgregarMarca->save();

        return redirect()->route('app/inventario'); // Redirige a la página que desees después de guardar el producto.
    }
}
