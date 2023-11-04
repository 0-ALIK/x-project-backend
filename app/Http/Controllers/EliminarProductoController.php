<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto;

use Exception;

class EliminarProductoController extends Controller
{
    public function eliminarProducto($id) {
        try {
            $producto = Producto::findOrFail($id);
            $producto->delete();

            return redirect()->route('productos.index')->with('success', 'Producto eliminado con éxito');
        } catch (Exception $e) {
            return back()->with('error', 'Producto no encontrado');
        }
    }
}
