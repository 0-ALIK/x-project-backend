<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\FormaPago;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PagoController extends Controller
{
    public function listarPagos()
    {
        return response()->json(Pago::all());
    }
    public function registrarPago(Request $request, $pedidoId)
    {
        // Validar los datos del pago
        $request->validate([
            'monto' => 'required|numeric',
            'forma_pago_id' => 'required|exists:forma_pago,id_forma_pago',
            // Agrega más validaciones según sea necesario
        ]);

        // Obtener el pedido
        $pedido = Pedido::findOrFail($pedidoId);

        // Validar los datos del pago
        $request->validate([
            'monto' => [
                'required',
                'numeric',
                Rule::max($pedido->montoTotalPendiente()), // Usa una regla personalizada para verificar el monto máximo
            ],
            'forma_pago_id' => 'required|exists:forma_pago,id_forma_pago',
            // Agrega más validaciones según sea necesario
        ]);

        // Verificar si el pedido ya ha sido pagado
        if ($pedido->pago) {
            return response()->json(['error' => 'Este pedido ya ha sido pagado'], 400);
        }

        // Registrar el pago
        $pago = Pago::create([
            'pedido_id' => $pedido->id_pedido,
            'forma_pago_id' => $request->input('forma_pago_id'),
            'monto' => $request->input('monto'),
            'fecha' => now(),
        ]);

        // Puedes realizar otras acciones relacionadas con el pago o el pedido aquí

        return response()->json(['message' => 'Pago registrado con éxito']);
    }
}
