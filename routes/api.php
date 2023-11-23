<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminVentasController;
use App\Http\Controllers\CarritoComprasController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\PagoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application.
| These routes are loaded by the RouteServiceProvider and all of them
| will be assigned to the "api" middleware group. Make something great!
|
*/

// Rutas de ventas para el administrador
Route::group(['prefix' => '/api/admin'], function () {
    // Rutas para listar pedidos y cambiar el estado de un pedido
    Route::get('/pedidos', [AdminVentasController::class, 'listarPedidos']);
    Route::put('/pedidos/{pedidoId}/cambiar-estado', [AdminVentasController::class, 'cambiarEstadoPedido']);
    // Puedes agregar más rutas según sea necesario
});

// Rutas para la interfaz de cliente
Route::group(['prefix' => '/api'], function () {
    // Nuevas rutas del carrito
    Route::get('/carrito/ver', [CarritoComprasController::class, 'verCarrito']);
    Route::post('/carrito/agregar/{productoId}', [CarritoComprasController::class, 'agregarAlCarrito']);
    Route::delete('/carrito/eliminar/{productoId}', [CarritoComprasController::class, 'eliminarDelCarrito']);
    Route::get('/carrito/pago', [CarritoComprasController::class, 'irAPago']);
    Route::post('/carrito/pagar', [CarritoComprasController::class, 'procesarPedido']);
    Route::get('/carrito/factura/{pedidoId}', [CarritoComprasController::class, 'generarFactura']);
});

// Ruta para obtener todos los productos
Route::get('/productos', [ProductoController::class, 'index']);

// Ruta para obtener detalles de un producto
Route::get('/productos/{id}', [ProductoController::class, 'show']);

// Ruta para crear un nuevo producto
Route::post('/productos', [ProductoController::class, 'store']);

// Ruta para actualizar un producto
Route::put('/productos/{id}', [ProductoController::class, 'update']);

// Ruta para eliminar un producto
Route::delete('/productos/{id}', [ProductoController::class, 'destroy']);

// Ruta para obtener detalles de un producto
Route::get('/producto/detalles/{id}', [ProductoController::class, 'mostrarDetalles']);

// Rutas para el método de pago
Route::group(['prefix' => '/api/payment'], function () {
    // Rutas para el controlador de pagos
    Route::get('/show-page', [PagoController::class, 'showPaymentPage'])->name('payment.page');
    Route::post('/process-payment/process-payment', [PagoController::class, 'processPayment'])->name('process.payment');
    // Otras rutas relacionadas con el método de pago
});
