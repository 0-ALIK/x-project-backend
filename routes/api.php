<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminVentasController;
use App\Http\Controllers\ClienteCarritoComprasController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

###########################################
########    RUTAS DE RECLAMOS    ##########
###########################################


// Rutas para la interfaz de administrador
Route::group(['prefix' => '/app'], function () {
    Route::get('/ventas', [AdminVentasController::class, 'index']);  // Página principal de ventas
    Route::get('/ventas/{id}', [AdminVentasController::class, 'mostrarVenta']); // Página de detalles de venta
    Route::get('/ordenes', [AdminVentasController::class, 'mostrarOrdenes']); // Página de órdenes
});

// Rutas para la API de administrador
Route::group(['prefix' => '/api'], function () {
    Route::get('/ventas', [AdminVentasController::class, 'getAllVentas']);  // Endpoint para obtener todas las ventas
    Route::get('/ventas/{id}', [AdminVentasController::class, 'getVentaById']); // Endpoint para obtener detalles de una venta
    Route::get('/ordenes', [AdminVentasController::class, 'getAllOrdenes']); // Endpoint para obtener todas las órdenes
});

// Rutas para la interfaz de cliente
Route::group(['prefix' => '/app'], function () {
    Route::get('/carrito-compras', [ClienteCarritoComprasController::class, 'mostrarCarrito']); // Página de carrito de compras
    Route::get('/articulos', [ClienteCarritoComprasController::class, 'mostrarArticulos']); // Página de artículos
    Route::get('/articulos/vista-articulo/{id}', [ClienteCarritoComprasController::class, 'mostrarVistaArticulo']); // Página de vista de artículo
    Route::get('/carrito', [ClienteCarritoComprasController::class, 'mostrarCarrito']); // Página de carrito de compras
    Route::get('/carrito/metodo-seleccionado', [ClienteCarritoComprasController::class, 'mostrarMetodoSeleccionado']); // Página de método de pago seleccionado
    Route::get('/factura/{id}', [ClienteCarritoComprasController::class, 'mostrarFactura']); // Página de factura
});

// Rutas para la API de cliente
Route::group(['prefix' => '/api'], function () {
    Route::get('/carrito-compras', [ClienteCarritoComprasController::class, 'getCarritoCompras']); // Endpoint para obtener el carrito de compras
    // ... Añade más rutas para la API de cliente según sea necesario
});







