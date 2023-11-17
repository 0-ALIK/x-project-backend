<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pedido;

class AdminVentasDetalleController extends Controller
{
    public function show($id)
    {
        // Obtener el pedido por ID
        $pedido = Pedido::find($id);

        // Verificar si el pedido existe
        if (!$pedido) {
            return response()->json(['message' => 'Pedido no encontrado.'], 404);
        }

        // Devolver la vista con los detalles del pedido
        return view('admin_ventas.detalle', ['pedido' => $pedido]);
    }
}

