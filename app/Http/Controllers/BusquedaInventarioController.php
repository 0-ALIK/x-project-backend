<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto;

class BusquedaInventarioController extends Controller
{
    public function buscarProductos(Request $request)
    {
        // Validaciones para los parámetros de búsqueda
        $request->validate([
            'nombre' => 'string',
            'categoria' => 'integer',
        ]);

        // Obtén los parámetros de búsqueda del formulario
        $nombre = $request->input('nombre');
        $categoria = $request->input('categoria');

        // Consulta de productos con filtros
        $query = Producto::query();

        if (!empty($nombre)) {
            $query->where('nombre', 'like', '%' . $nombre . '%');
        }

        if (!empty($categoria)) {
            $query->where('categoria_id', $categoria);
        }

        $productosFiltrados = $query->get();

        // Devuelve los resultados de la búsqueda a la vista
        return view('inventario.resultados', ['productos' => $productosFiltrados]);
    }
}
