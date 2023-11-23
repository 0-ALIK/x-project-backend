<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\FormaPago;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PagoController extends Controller
{
    // Mostrar la página de pago (opcional)

    public function showPaymentPage()
    {
        // Aquí podrías cargar una vista con el formulario de pago si estás creando una interfaz web.
        // Si estás trabajando en una API, este paso puede no ser necesario.
    }

    // Procesar el pago
    public function processPayment(Request $request)
    {
        // Validaciones necesarias (monto, tarjeta, etc.)
        $request->validate([
            'pedido_id' => 'required|exists:pedido,id_pedido',
            'monto' => 'required|numeric',
            'forma_pago_id' => 'required|exists:forma_pago,id_forma_pago'
            // Agrega más validaciones según sea necesario
        ]);

        // Obtener la información del formulario
        $pedidoId = $request->input('pedido_id');
        $monto = $request->input('monto');
        $formaPagoId = $request->input('forma_pago_id');

        // Puedes realizar más validaciones según sea necesario, como verificar la autenticidad de la tarjeta, etc.

        // Iniciar una transacción de base de datos para garantizar la integridad de los datos
        DB::beginTransaction();

        try {

            // Registrar el pago en la tabla 'pago'
            $pago = Pago::create([
                'pedido_id' => $pedidoId,
                'monto' => $monto,
                'forma_pago_id' => $formaPagoId,
                'fecha' => now(),
            ]);

            // Actualizar el estado del pedido a 'Pagado' u otro estado según sea necesario
            // Esto puede depender de cómo manejes los estados de los pedidos en tu aplicación
            // Ejemplo:
            $pedido = Pedido::find($request->input('pedido_id'));
            $pedido->estado_id = 2; // Suponiendo que 'Pagado' tiene el ID 2
            $pedido->save();

            // Commit de la transacción
            DB::commit();

            // Puedes devolver una respuesta de éxito
            return response()->json(['message' => 'Pago registrado con éxito']);
        } catch (\Exception $e) {
            // Si hay algún error, realiza un rollback de la transacción
            DB::rollBack();

            // Puedes devolver una respuesta de error
            return response()->json(['error' => 'Error al procesar el pago'], 500);
        }
    }
}
