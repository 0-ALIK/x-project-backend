<?php

use App\Http\Controllers\AgregarMarcaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReclamoController as Reclamo;
use App\Http\Controllers\SugerenciaController as Sugerencia;
use App\Http\Controllers\EliminarMarcaController;
use App\Http\Controllers\EliminarProductoController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\BusquedaInventarioController;

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
#RUTAS MODULO 1
Route::get('/api/inventario', [InventarioController::class, 'verInventario']);
Route::post('/api/inventario',[BusquedaInventarioController::class, 'buscarProductos']);
Route::post('/api/agregar-marca', [AgregarMarcaController::class, 'guardarMarca'])->name('guardar.marca');
Route::post('/api/agregar-producto', [AgregarMarcaController::class, 'guardarProducto'])->name('guardar.producto');
Route::delete('/api/eliminar-producto/{id_producto}', [EliminarProductoController::class, 'eliminarProducto'])->name('eliminar.producto');
Route::delete('/api/eliminar-marca/{id_marca}', [EliminarMarcaController::class, 'eliminarMarca'])->name('eliminar.marca');



