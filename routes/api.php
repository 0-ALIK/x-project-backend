<?php

use App\Http\Controllers\AgregarMarcaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReclamoController as Reclamo;
use App\Http\Controllers\SugerenciaController as Sugerencia;
use App\Http\Controllers\EliminarMarcaController;
use App\Http\Controllers\EliminarProductoController;
use App\Http\Controllers\InventarioController;

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

Route::post('/api/reclamo', [Reclamo::class, 'guardarReclamo']);
Route::get('/api/reclamo',  [Reclamo::class, 'getAllReclamos']);
Route::get('/api/reclamo/estados',      [Reclamo::class, 'getAllEstados']);
Route::get('/api/reclamo/categorias',   [Reclamo::class, 'getAllCategorias']);
Route::get('/api/reclamo/prioridades',  [Reclamo::class, 'getAllPrioridades']);
Route::get('/api/reclamo/{reclamo_id}',         [Reclamo::class, 'getReclamoById']);
Route::get('/api/reclamo/cliente/{cliente_id}', [Reclamo::class, 'getReclamosCliente']);
Route::patch('/api/reclamo/{reclamo_id}/estado',    [Reclamo::class, 'updateEstado']);
Route::patch('/api/reclamo/{reclamo_id}/prioridad', [Reclamo::class, 'updatePrioridad']);

Route::post('api/sugerencia',   [Sugerencia::class, 'guardarSugerencia']);
Route::get('api/sugerencia',    [Sugerencia::class, 'getSugerencia']);

#RUTAS MODULO 1
Route::get('/api/inventario', [InventarioController::class, 'VerInventario']);
Route::post('/api/agregar-marca', [AgregarMarcaController::class, 'GuardarMarca']);
Route::post('/api/agregar-producto', [AgregarMarcaController::class, 'GuardarProducto']);
Route::delete('/api/eliminar-producto/{id_producto}', [EliminarProductoController::class, 'eliminarProducto'])->name('eliminar.producto');
Route::delete('/api/eliminar-marca/{id_marca}', [EliminarMarcaController::class, 'eliminarMarca'])->name('eliminar.marca');



