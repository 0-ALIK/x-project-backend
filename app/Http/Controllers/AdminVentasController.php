<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\PedidoEstado;
use App\Models\PedidoProducto;
use App\Models\Producto;

class AdminVentasController extends Controller{
    public function listarPedidos()
    {
        $pedidos = Pedido::with([
            'cliente.usuario',
            'cliente.empresa',
            'estado',
            'direccion.provincia',
            'pago.formaPago',
            'pedido_productos.producto'
        ])->get();

        if ($pedidos->isEmpty()) {
            return response()->json(['error' => 'Pedidos no encontrados'], 404);
        }

        $pedidos = $pedidos->map(function ($pedido) {
            $pedido->id_empresa = $pedido->cliente->empresa->id_empresa;

            $pedido->cliente->nombre = $pedido->cliente->usuario->nombre;
            $pedido->cliente->correo = $pedido->cliente->usuario->correo;
            $pedido->cliente->foto = $pedido->cliente->usuario->foto;
            $pedido->cliente->telefono = $pedido->cliente->usuario->telefono;
            $pedido->cliente->detalles = $pedido->cliente->usuario->detalles;

            $pedido->cliente->empresa->nombre = $pedido->cliente->empresa->usuario->nombre;
            $pedido->cliente->empresa->correo = $pedido->cliente->empresa->usuario->correo;
            $pedido->cliente->empresa->foto = $pedido->cliente->empresa->usuario->foto;
            $pedido->cliente->empresa->telefono = $pedido->cliente->empresa->usuario->telefono;
            $pedido->cliente->empresa->detalles = $pedido->cliente->empresa->usuario->detalles;

            unset($pedido->cliente->usuario); // Eliminando el objeto usuario
            unset($pedido->cliente->empresa->usuario);
            return $pedido;
        });

        return response()->json($pedidos);
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
    public function agregarPedido(Request $request) {
        $id_usuario = $request->user()->currentAccessToken()->tokenable_id;

        $cliente = Cliente::where('usuario_id', $id_usuario)->first();

        // Validar los datos entrantes
        $request->validate([
            'direccion_id' => 'required|exists:direccion,id_direccion',
            'detalles' => 'string',
            'pedido_productos' => 'required|array',
            'pedido_productos.*.producto_id' => 'required|exists:producto,id_producto',
            'pedido_productos.*.cantidad' => 'required|integer|min:1'
        ]);

        // Obtener un 'estado en proceso' desde la base de datos para configurar de forma predeterminada
        $estado_id = PedidoEstado::where('nombre', '=', 'Proceso')->first()->id_pedido_estado;

        // Crear un nuevo pedido
        $pedidoData = [
            'cliente_id' => $cliente->id_cliente,
            'estado_id' => $estado_id,
            'direccion_id' => $request->input('direccion_id'),
            'fecha' => now(),
            'fecha_cambio_estado' => now()
        ];

        // Verificar si 'detalles' está presente en la solicitud
        if ($request->has('detalles')) {
            $pedidoData['detalles'] = $request->input('detalles');
        }

        $pedido = new Pedido($pedidoData);
        $pedido->save();

        foreach($request->pedido_productos as $producto) {
            $pedidoProducto = PedidoProducto::create([
                'producto_id' => $producto['producto_id'],
                'pedido_id' => $pedido->id_pedido,
                'cantidad' => $producto['cantidad']
            ]);

            // Restar la cantidad de cajas del producto
            $productoModel = Producto::find($producto['producto_id']);
            if ($productoModel) {
                $productoModel->cantidad_cajas -= $producto['cantidad'];
                $productoModel->save();
            }
        }
        return response()->json(['message' => 'Pedido creado con éxito', 'data' => $pedido]);
    }

    public function borrarPedido($pedidoId) {
        $pedido = Pedido::find($pedidoId);

        if (!$pedido) {
            return response()->json(['error' => 'Pedido no encontrado'], 404);
        }

        // Obtener los productos asociados al pedido
        $productosPedido = PedidoProducto::where('pedido_id', $pedidoId)->get();

        // Devolver la cantidad de productos al stock
        foreach ($productosPedido as $productoPedido) {
            $producto = Producto::find($productoPedido->producto_id);

            if ($producto) {
                $producto->cantidad_cajas += $productoPedido->cantidad;
                $producto->save();
            }
        }

        // Eliminar el pedido y los productos asociados
        PedidoProducto::where('pedido_id', $pedidoId)->delete();
        $pedido->delete();

        return response()->json(['message' => 'Pedido eliminado con éxito']);
    }
}
