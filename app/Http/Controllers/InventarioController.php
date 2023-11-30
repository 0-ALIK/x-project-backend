<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto;

class InventarioController extends Controller
{

    //muestra todo el inventario
    public function verInventario(Request $request)
{
    // Consulta los productos con relaciones cargadas
    $productos = Producto::with(['marca', 'categoria'])
        ->join('marca', 'producto.marca_id', '=', 'marca.id_marca')
        ->join('categoria', 'producto.categoria_id', '=', 'categoria.id_categoria')
        ->select(
            'categoria.*', // Todas las columnas de la tabla 'categoria'
            'marca.*', // Todas las columnas de la tabla 'marca'
            'producto.*' // Todas las columnas de la tabla 'producto'
        )
        ->get();

    if ($productos->isEmpty()) {
        return response()->json(['message' => 'No hay productos para mostrar.'], 404);
    }

    return response()->json($productos, 200);
}

    //trae un producto en especifico
    public function buscarProductos(Request $request){
    // Validaciones para los parámetros de búsqueda
    $request->validate([
        'nombre' => 'string',
        'categoria' => 'integer',
    ]);

    try {
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

        // Devuelve los resultados de la búsqueda como respuesta JSON
        return response()->json(['productos' => $productosFiltrados], 200);
    } catch (\Exception $e) {
        // Manejar cualquier error y devolver una respuesta de error
        return response()->json(['error' => 'Error al procesar la solicitud.'], 500);
    }
}

}

