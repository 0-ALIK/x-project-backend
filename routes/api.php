<?php

use App\Http\Controllers\AgregarMarcaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;
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
#RUTAS MODULO 1
Route::get('/api/inventario', [InventarioController::class, 'verInventario']);
Route::post('/api/inventario',[InventarioController::class, 'buscarProductos']);
Route::post('/api/marca', [MarcaController::class, 'getAllMarcas']);
Route::post('/api/marca', [MarcaController::class, 'guardarMarca']);
Route::post('/api/marca', [MarcaController::class, 'updateMarca']);
Route::post('/api/marca', [MarcaController::class, 'deleteMarca']);
Route::post('/api/categoria', [CategoriaController::class, 'getAllCategorias']);
Route::post('/api/categoria', [CategoriaController::class, 'guardarCategoria']);
Route::post('/api/categoria', [CategoriaController::class, 'updateCategoria']);
Route::post('/api/categoria', [CategoriaController::class, 'deleteCategoria']);
Route::post('/api/producto', [ProductoController::class, 'getAllProductos']);
Route::post('/api/producto', [ProductoController::class, 'guardarProducto']);
Route::post('/api/producto', [ProductoController::class, 'updateProducto']);
Route::post('/api/producto', [ProductoController::class, 'deleteProducto']);



