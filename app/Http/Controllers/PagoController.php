<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\FormaPago;
use App\Models\Pedido;
use App\Models\PedidoProducto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PagoController extends Controller
{
    public function listarPagos()
    {
        return response()->json(Pago::all());
    }

    public function listarFormasPago() {
        return FormaPago::all();
    }

    public function registrarPago(Request $request, $pedidoId) {
        // Validar los datos del pago
        $request->validate([
            'monto' => 'required|numeric|gt:0',
            'forma_pago_id' => 'required|exists:forma_pago,id_forma_pago',
            // Agrega más validaciones según sea necesario
        ]);

        $importe = $this->calcularImporte($pedidoId);

        $importePagado = Pago::where('pedido_id', $pedidoId)->sum('monto');

        $importeDebido = $importe - $importePagado;

        if($importeDebido == 0) {
            return response()->json(['error' => 'El pedido ya esta pagado'], 422);
        }

        if($request->input('monto') > $importeDebido) {
            return response()->json(['error' => 'El monto del pago excede el importe debido del pedido'], 422);
        }

        $pagoCreado = Pago::create([
            'pedido_id' => $pedidoId,
            'forma_pago_id' => $request->input('forma_pago_id'),
            'monto' => $request->input('monto'),
            'fecha' => now(),
        ])->with(['formaPago']);

        return Pago::with(['formaPago'])->latest()->first();
    }

    public function actualizarPago(Request $request, $pagoId)
    {
        // Validar los datos del pago a actualizar
        $request->validate([
            'monto' => 'required|numeric',
            'forma_pago_id' => 'required|exists:forma_pago,id_forma_pago',
            // Agrega más validaciones según sea necesario
        ]);

        // Buscar el pago por su ID
        $pago = Pago::find($pagoId);

        if (!$pago) {
            return response()->json(['error' => 'Pago no encontrado'], 404);
        }

        // Obtener el pedido asociado al pago
        $pedidoId = $pago->pedido_id;

        // Calcular el importe total del pedido
        $importeTotalPedido = $this->calcularImporte($pedidoId);

        // Calcular el monto total pagado para el pedido, excluyendo el monto del pago actual
        $importeTotalPagado = Pago::where('pedido_id', $pedidoId)
            ->where('id_pago', '!=', $pagoId)
            ->sum('monto');

        // Calcular el importe total pagado, incluyendo el nuevo monto del pago a actualizar
        $importeTotalPagadoConNuevoPago = $importeTotalPagado + $request->input('monto');

        // Verificar si el nuevo monto pagado supera el importe total del pedido
        if ($importeTotalPagadoConNuevoPago > $importeTotalPedido) {
            return response()->json(['error' => 'El monto del pago excede el importe total del pedido'], 422);
        }

        // Actualizar los datos del pago
        $pago->update([
            'forma_pago_id' => $request->input('forma_pago_id'),
            'monto' => $request->input('monto'),
            // Puedes actualizar otros campos aquí si es necesario
        ]);

        return response()->json(['message' => 'Pago actualizado con éxito']);
    }

    public function eliminarPago($pagoId)
    {
        // Buscar el pago por su ID
        $pago = Pago::find($pagoId);

        if (!$pago) {
            return response()->json(['error' => 'Pago no encontrado'], 404);
        }

        // Obtener el ID del pedido asociado al pago
        $pedidoId = $pago->pedido_id;

        // Eliminar el pago
        $pago->delete();

        // Aquí puedes realizar cualquier otra lógica necesaria después de eliminar el pago, si es necesario

        return response()->json(['message' => 'Pago eliminado con éxito']);
    }

    private function calcularImporte($pedidoId) {
        $pedidoProductos = PedidoProducto::where('pedido_id', $pedidoId)->with('producto')->get();

        $importe = 0;
        foreach ($pedidoProductos as $pedidoProducto) {
            $importeProducto = $pedidoProducto->cantidad *
                                $pedidoProducto->producto->precio_unit *
                                $pedidoProducto->producto->cantidad_por_caja;

            $importe += $importeProducto;
        }

        return $importe;
    }
}
