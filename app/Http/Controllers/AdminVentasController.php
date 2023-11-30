<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\PedidoEstado;


class AdminVentasController extends Controller
{

    public function listarPedidos()
    {
        $pedido = Pedido::with(['cliente', 'estado', 'direccion', 'pago', 'pedido_productos'])->get();

        if (!$pedido) {
            return response()->json(['error' => 'Pedido no encontrado'], 404);
        }

        return response()->json($pedido);
    }
    public function cambiarEstadoPedido(Request $request, $pedidoId)
    {
        // Validar y cambiar el estado del pedido
        $request->validate([
            'estado_id' => 'required|exists:pedido_estado,id_pedido_estado',
        ]);

        $pedido = Pedido::findOrFail($pedidoId);
        $pedido->update(['estado_id' => $request->input('estado_id')]);

        return response()->json(['message' => 'Estado del pedido actualizado con éxito']);
    }
    public function obtenerPago(int $pedidoId)
    {
        $pedido = Pedido::findOrFail($pedidoId);
        $pago = $pedido->pago;

        return $pago;
    }

    public function obtenerPedidoConPago($pedidoId)
    {
        $pedido = Pedido::with(['cliente', 'estado', 'direccion', 'pago'])->find($pedidoId);

        if (!$pedido) {
            return response()->json(['error' => 'Pedido no encontrado'], 404);
        }

        return response()->json($pedido);
    }
}
