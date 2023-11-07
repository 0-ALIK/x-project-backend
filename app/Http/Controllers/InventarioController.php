<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto;

class InventarioController extends Controller
{
    public function VerInventario(Request $request)
    {
        // Realiza las validaciones
        $request->validate([
            // Debe haber productos para mostrar
            'productos' => 'required',
        ]);

        // 1. Validación para mostrar un punto de reorden
        // Comprueba si hay algún producto cuya cantidad en stock esté por debajo del punto de reorden.
        $productosPuntoReorden = Producto::where('cantidad_stock', '<', 'punto_reorden')->get();

        if ($productosPuntoReorden->isEmpty()) {
            return response()->json(['message' => 'No hay productos por debajo del punto de reorden.'], 200);
        }

        // 2. Filtros para buscar productos específicos
        $filtroNombre = $request->input('nombre');
        $filtroCategoria = $request->input('categoria');

        // Consulta los productos con los filtros si es que son pedidos
        $query = Producto::query();

        if (!empty($filtroNombre)) {
            $query->where('nombre', 'like', '%' . $filtroNombre . '%');
        }

        if (!empty($filtroCategoria)) {
            $query->where('categoria', $filtroCategoria);
        }

        $productosFiltrados = $query->get();

        if ($productosFiltrados->isEmpty()) {
            return response()->json(['message' => 'No se encontraron productos con los filtros especificados.'], 200);
        }

        $productos = Producto::all();

        if ($productos->isEmpty()) {
            return response()->json(['message' => 'No hay productos para mostrar.'], 404);
        }

        return response()->json($productos, 200);
    }
}
