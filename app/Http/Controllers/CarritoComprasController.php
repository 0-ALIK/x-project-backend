<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB; // Agrega esta línea para importar la clase DB
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\PedidoProducto;
use App\Models\Producto;
use App\Models\Pedido;
use App\Models\Marca;
use App\Models\FormaPago;
use App\Models\Pago;



class CarritoComprasController extends Controller
{
    public function verCarrito()
    {
        $carrito = PedidoProducto::with('producto.marca')->get();

        if ($carrito->isEmpty()) {
            return response()->json(['message' => 'El carrito está vacío']);
        }

        // Calcular la cantidad total de productos y la suma total de precios
        $cantidadTotal = $carrito->sum('cantidad');
        $sumaTotal = $carrito->sum(function ($item) {
            return $item->cantidad * $item->producto->precio_unit;
        });

        return response()->json([
            'carrito' => $carrito,
            'cantidad_total' => $cantidadTotal,
            'suma_total' => $sumaTotal,
        ]);
    }

    public function agregarAlCarrito(Request $request, $productoId)
    {
        // Simular un usuario autenticado con ID 1
        $clienteId = 1;

        // Verificar si hay un pedido activo para el cliente
        $pedidoActivo = Pedido::where('cliente_id', $clienteId)
            ->where('estado_id', 1) // Supongo que el estado "En Proceso" tiene el ID 1
            ->first();

        // Si no hay un pedido activo, crea uno
        if (!$pedidoActivo) {
            $pedidoActivo = Pedido::create([
                'cliente_id' => $clienteId,
                'estado_id' => 1, // Supongo que el estado "En Proceso" tiene el ID 1
                'direccion_id' => 1, // Aquí deberías establecer la dirección adecuada para el pedido
                'detalles' => 'Detalles del pedido', // Ajusta según sea necesario
                'fecha' => now(), // Ajusta según sea necesario
                'fecha_cambio_estado' => now(), // Ajusta según sea necesario
                // Agrega otros campos específicos del pedido
            ]);
        }

        // Ahora, puedes agregar el producto al carrito asociándolo al pedido activo
        PedidoProducto::create([
            'pedido_id' => $pedidoActivo->id_pedido,
            'producto_id' => $productoId,
            'cantidad' => $request->input('cantidad'), // Asegúrate de tener este valor en tu solicitud
            // Agrega otros campos específicos del pedido_productos
        ]);

        // Puedes devolver una respuesta adecuada, por ejemplo, un mensaje de éxito
        return response()->json(['message' => 'Producto agregado al carrito con éxito']);
    }



    public function eliminarDelCarrito($productoId)
    {
        PedidoProducto::where('producto_id', $productoId)->delete();

        return response()->json(['message' => 'Producto eliminado del carrito con éxito']);
    }

    public function irAPago()
    {
        // Simular un usuario autenticado con ID 1
        $clienteId = 1;

        // Obtener el pedido activo para el cliente
        $pedidoActivo = Pedido::where('cliente_id', $clienteId)
            ->where('estado_id', 1) // Supongo que el estado "En Proceso" tiene el ID 1
            ->first();

        // Verificar si hay productos en el carrito
        $productosEnCarrito = PedidoProducto::where('pedido_id', $pedidoActivo->id_pedido)->exists();

        if (!$productosEnCarrito) {
            return response()->json(['message' => 'El carrito está vacío']);
        }

        // Obtener los métodos de pago disponibles
        $metodosPago = FormaPago::all();

        return response()->json([
            'message' => 'Selecciona un método de pago',
            'metodos_pago' => $metodosPago
        ]);
    }

    public function procesarPedido(Request $request)
    {
        // Validaciones necesarias (pedido_id, monto, forma_pago_id, etc.)
        $request->validate([
            'pedido_id' => 'required|exists:pedido,id_pedido',
            'monto' => 'required|numeric',
            'forma_pago_id' => 'required|exists:forma_pago,id_forma_pago',
            // Agregar más validaciones según sea necesario
        ]);

        // Obtener el pedido actual
        $pedido = Pedido::find($request->input('pedido_id'));

        // Crear el registro de pago
        $pago = Pago::create([
            'pedido_id' => $pedido->id_pedido,
            'forma_pago_id' => $request->input('forma_pago_id'),
            'monto' => $request->input('monto'),
            'fecha' => now(),
        ]);

        // Actualizar el estado del pedido a 'Pagado' u otro estado según sea necesario
        $pedido->update(['estado_id' => 2]);

        // Otras acciones que puedas necesitar realizar

        return response()->json(['message' => 'Pedido procesado con éxito']);
    }
    public function generarFactura($pedidoId)
    {
        // Realiza un join entre las tablas necesarias para obtener la información de la factura
        $factura = PedidoProducto::join('producto', 'pedido_productos.producto_id', '=', 'producto.id_producto')
            ->join('marca', 'producto.marca_id', '=', 'marca.id_marca')
            ->select(
                'producto.id_producto',
                'producto.nombre as nombre_producto',
                'marca.nombre as nombre_marca',
                'pedido_productos.cantidad',
                'producto.precio_unit',
                DB::raw('pedido_productos.cantidad * producto.precio_unit as subtotal')
            )
            ->where('pedido_productos.pedido_id', $pedidoId)
            ->get();

        // Calcula la suma total de la factura
        $sumaTotal = $factura->sum('subtotal');

        // Puedes devolver los resultados como un arreglo JSON o adaptarlo según tus necesidades
        return response()->json([
            'factura' => $factura,
            'suma_total' => $sumaTotal,
        ]);
    }

}
