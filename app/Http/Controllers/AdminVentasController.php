<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pedido;


class AdminVentasController extends Controller
{
    public function getAllVentas()
    {
        // Obtener la lista de pedidos desde la base de datos
        $pedidos = Pedido::all();

        // Validar si hay pedidos
        if ($pedidos->isEmpty()) {
            return response()->json(['message' => 'No hay pedidos para mostrar.'], 404);
        }

        // Devolver la vista con los datos
        return response()->json($pedidos, 200);
    }
}
