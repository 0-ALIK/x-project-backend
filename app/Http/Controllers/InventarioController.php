<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto;

class InventarioController extends Controller
{
    public function verInventario(Request $request)
    {
        // Realiza las validaciones
        $request->validate([
            // Debe haber productos para mostrar
            'productos' => 'required',
        ]);

        // Validación para mostrar un punto de reorden
        // Comprueba si hay algún producto cuya cantidad en stock esté por debajo del punto de reorden.
        $productosPuntoReorden = Producto::whereColumn('cantidad_stock', '<', 'punto_reorden')->get();

        if ($productosPuntoReorden->isEmpty()) {
            return response()->json(['message' => 'No hay productos por debajo del punto de reorden.'], 200);
        }

        // Consulta los productos
        $productos = Producto::all();

        if ($productos->isEmpty()) {
            return response()->json(['message' => 'No hay productos para mostrar.'], 404);
        }

        return response()->json($productos, 200);
    }
}

