<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto;

class InventarioController extends Controller
{

    //muestra todo el inventario
    public function verInventario(Request $request){
        // Realiza las validaciones
        // $request->validate([
        //     // Debe haber productos para mostrar
        //     'producto' => 'required'
        // ]);

        // Validación para mostrar un punto de reorden
        // Comprueba si hay algún producto cuya cantidad en stock esté por debajo del punto de reorden.
        // $productosPuntoReorden = Producto::whereColumn('cantidad_stock', '<', 'punto_reorden')->get();

        // if ($productosPuntoReorden->isEmpty()) {
        //     return response()->json(['message' => 'No hay productos por debajo del punto de reorden.'], 200);
        // }

        // Consulta los productos
        $productos = Producto::all();

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

