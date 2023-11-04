<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto;

class InventarioController extends Controller
{
    public function index(Request $request)
{
    // Realiza las validaciones
    $request->validate([
        // Debe haber productos para mostrar
        'productos' => 'required',
        // Agregar la validación personalizada para "No puede bajar de un mínimo de cantidad de producto" aquí.
    ]);

    // Consulta los productos y devuelve una respuesta JSON
    $productos = Producto::all();

    if ($productos->isEmpty()) {
        return response()->json(['message' => 'No hay productos para mostrar.'], 404);
    }

    return response()->json($productos, 200);
}

}
