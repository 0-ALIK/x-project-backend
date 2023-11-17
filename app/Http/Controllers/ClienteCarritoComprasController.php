<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// app/Http/Controllers/ClienteCarritoComprasController.php
use App\Models\CarritoCompraItem;

class ClienteCarritoComprasController extends Controller
{
    // Método para mostrar la vista de pago
    public function mostrarPago()
    {
        // Obtener productos en el carrito desde la sesión
        $productosEnCarrito = Session::get('carrito', []);

        // Calcular el resumen (cantidad de ítems, descuento, total, etc.)
        $resumen = $this->calcularResumen($productosEnCarrito);

        return view('cliente_carrito_compras.pago', [
            'productosEnCarrito' => $productosEnCarrito,
            'resumen' => $resumen,
        ]);
    }

    private function calcularResumen($productosEnCarrito)
    {
        // Lógica para calcular el resumen según tus necesidades
        $cantidadItems = count($productosEnCarrito);

        $total = 0;
        foreach ($productosEnCarrito as $producto) {
            $total += $producto['cantidad'] * $producto['precio'];
        }

        // Puedes agregar más lógica según tus necesidades

        return [
            'cantidadItems' => $cantidadItems,
            'total' => $total,
            // Agrega más información de resumen según sea necesario
        ];
    }

    // Método para procesar el pago
    public function procesarPago(Request $request)
    {
        // Lógica para procesar el pago, registrar la compra, etc.

        // Ejemplo  registrar la compra en la base de datos
        Compra::create([
            'usuario_id' => auth()->user()->id, // Suponiendo que estás utilizando la autenticación de Laravel
            'fecha' => now(),
            'producto_id' => $request->producto_id,
            'cantidad' => $request->cantidad,
            'precio_init' => $request->precio_init,
            'detalles' => $request->detalles,
        ]);

        // Lógica adicional

        // Redirigir a una página de confirmación o a otra vista
        return view('cliente_carrito_compras.confirmacion');
    }
}
