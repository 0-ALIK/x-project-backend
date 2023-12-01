<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\PedidoEstado;
use App\Models\PedidoProducto;


class AdminVentasController extends Controller{
    public function listarPedidos()
    {
        $pedidos = Pedido::with([
            'cliente.usuario',
            'cliente.empresa',
            'estado',
            'direccion',
            'pago',
            'pedido_productos.producto'
        ])->get();

        if ($pedidos->isEmpty()) {
            return response()->json(['error' => 'Pedidos no encontrados'], 404);
        }

        $pedidos = $pedidos->map(function ($pedido) {
            $pedido->cedula = $pedido->cliente->cedula;
            $pedido->apellido = $pedido->cliente->apellido;
            $pedido->genero = $pedido->cliente->genero;
            $pedido->id_empresa = $pedido->cliente->empresa->id_empresa;

            // Moviendo los atributos de usuario al objeto cliente
            $pedido->cliente->nombre = $pedido->cliente->usuario->nombre;
            $pedido->cliente->correo = $pedido->cliente->usuario->correo;
            $pedido->cliente->foto = $pedido->cliente->usuario->foto;
            $pedido->cliente->telefono = $pedido->cliente->usuario->telefono;
            $pedido->cliente->detalles = $pedido->cliente->usuario->detalles;

            // Moviendo los atributos de usuario y cliente al objeto empresa
            $pedido->cliente->empresa->cedula = $pedido->cliente->cedula;
            $pedido->cliente->empresa->apellido = $pedido->cliente->apellido;
            $pedido->cliente->empresa->genero = $pedido->cliente->genero;
            $pedido->cliente->empresa->estado = $pedido->cliente->estado;
            $pedido->cliente->empresa->nombre = $pedido->cliente->usuario->nombre;
            $pedido->cliente->empresa->correo = $pedido->cliente->usuario->correo;
            $pedido->cliente->empresa->foto = $pedido->cliente->usuario->foto;
            $pedido->cliente->empresa->telefono = $pedido->cliente->usuario->telefono;
            $pedido->cliente->empresa->detalles = $pedido->cliente->usuario->detalles;
            unset($pedido->cliente->usuario); // Eliminando el objeto usuario
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
    public function agregarPedido(Request $request)
    {/*
        // Autenticación - obteniendo el id del cliente a través del token
        $cliente_id = $request->user()->id;

        // Validar los datos entrantes
        $request->validate([
            'direccion_id' => 'required|exists:direcciones,id',
            'detalle' => 'required|string',
            'pedido_productos' => 'required|array',
            'pedido_productos.*.id' => 'required|exists:productos,id',
            'pedido_productos.*.cantidad' => 'required|integer|min:1'
        ]);

        // Obtener un 'estado en proceso' desde la base de datos para configurar de forma predeterminada
        $estado_id = PedidoEstado::where('nombre', '=', 'en proceso')->first()->id;

        // Crear un nuevo pedido
        $pedido = new Pedido([
            'cliente_id' => $cliente_id,
            'estado_id' => $estado_id,
            'direccion_id' => $request->input('direccion_id'),
            'detalle' => $request->input('detalle'),
            'fecha' => now() // La fecha se genera automáticamente
        ]);

        // Guardar el pedido
        $pedido->save();

        // Agregar productos al pedido
        foreach($request->pedido_productos as $producto) {
            PedidoProducto::create([
                'producto_id' => $producto['id'],
                'pedido_id' => $pedido->id,
                'cantidad' => $producto['cantidad']
            ]);
        }
        return response()->json(['message' => 'Pedido creado con éxito', 'data' => $pedido]);
    */
    }
}
