<?php

use App\Http\Controllers\AgregarMarcaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\InventarioController;
use App\Models\Producto;
use App\Http\Controllers\EmpresaController as Empresa;
use App\Http\Controllers\SolicitudesController as Solicitudes;
use App\Http\Controllers\ClienteController as Cliente;
use App\Http\Controllers\DireccionClienteController as DireccionCliente;
use App\Http\Controllers\SucursalController as Sucursal;
use App\Http\Controllers\UsuarioController as Usuario;

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
########    RUTAS DE USUARIOS   ###########
###########################################
Route::post('/api/login', [Usuario::class, 'login']);

Route::post('/api/logout', [Usuario::class, 'logout'])->middleware(['auth:sanctum']);
###########################################
########    RUTAS DE EMPRESAS   ###########
###########################################
Route::get('/api/empresas',  [Empresa::class, 'getAllEmpresas'])->middleware(['auth:sanctum', 'ability:empresa, admin']);

Route::get('/api/empresas/{id}',  [Empresa::class, 'getEmpresa']);

Route::post('/api/empresas',  [Empresa::class, 'guardarEmpresa']);

Route::put('/api/empresas/{id}',  [Empresa::class, 'actualizarEmpresa'])->middleware(['auth:sanctum', 'ability:empresa']);

Route::delete('/api/empresas/{id}',  [Empresa::class, 'eliminarEmpresa'])->middleware(['auth:sanctum', 'ability:empresa']);
###########################################
########    RUTAS DE SUCURSALES   ###########
###########################################
Route::get('/api/sucursales/{id}', [Sucursal::class, 'getSucursales']);
Route::post('/api/sucursales/{id}', [Sucursal::class, 'guardarSucursal'])->middleware(['auth:sanctum', 'ability:empresa, admin']);
Route::put('/api/sucursales/{empresa_id}/{direccion_id}', [Sucursal::class, 'actualizarSucursal'])->middleware(['auth:sanctum', 'ability:empresa, admin']);
Route::delete('/api/sucursales/{empresa_id}/{direccion_id}', [Sucursal::class, 'eliminarSucursal'])->middleware(['auth:sanctum', 'ability:empresa, admin']);
###########################################
########    RUTAS DE SOLICITUDES   ########
###########################################
Route::get('/api/solicitudes',  [Solicitudes::class, 'getAllSolicitudes'])->middleware(['auth:sanctum', 'ability:admin']);

Route::put('/api/solicitudes/{id}',  [Solicitudes::class, 'actualizarSolicitud'])->middleware(['auth:sanctum', 'ability:admin']);

//se rechaza la empresa y se elimina xd
Route::delete('/api/solicitudes/{id}',  [Solicitudes::class, 'rechazarSolicitud'])->middleware(['auth:sanctum', 'ability:admin']);

###########################################
########    RUTAS DE Clientes    ##########
###########################################

Route::get('/api/clientes',  [Cliente::class, 'getAllClientes'])->middleware(['auth:sanctum', 'ability:empresa, admin']);

Route::get('/api/clientes/{id}',  [Cliente::class, 'getCliente']);

Route::post('/api/clientes',  [Cliente::class, 'guardarCliente'])->middleware(['auth:sanctum', 'ability:empresa']);

Route::put('/api/clientes/{id}',  [Cliente::class, 'actualizarCliente'])->middleware(['auth:sanctum', 'ability:admin,cliente,empresa']);

Route::delete('/api/clientes/{id}',  [Cliente::class, 'eliminarCliente'])->middleware(['auth:sanctum', 'ability:cliente,empresa']);

###########################################
##    RUTAS DE Direccion Clientes    ######
###########################################

Route::get('/api/clientes/{id}/direcciones',  [DireccionCliente::class, 'getClienteDirecciones']);

Route::post('/api/clientes/{id}/direcciones',  [DireccionCliente::class, 'guardarClienteDireccion'])->middleware(['auth:sanctum', 'ability:cliente']);

Route::put('/api/clientes/{id}/direcciones/{id_direccion}',  [DireccionCliente::class, 'actualizarClienteDireccion'])->middleware(['auth:sanctum', 'ability:cliente']);

Route::delete('/api/clientes/{id}/direcciones/{id_direccion}',  [DireccionCliente::class, 'eliminarClienteDireccion'])->middleware(['auth:sanctum', 'ability:cliente']);
#RUTAS MODULO 1
Route::get('/api/inventario', [InventarioController::class, 'verInventario']);
Route::post('/api/inventario',[InventarioController::class, 'buscarProductos']);
Route::get('/api/marca', [MarcaController::class, 'getAllMarcas']);
Route::post('/api/marca', [MarcaController::class, 'guardarMarca']);
Route::get('/api/marca/{id_marca}', [MarcaController::class, 'getMarca']);
Route::put('/api/marca/{id_marca}', [MarcaController::class, 'updateMarca']);
Route::delete('/api/marca/{id_marca}', [MarcaController::class, 'deleteMarca']);
Route::get('/api/categoria', [CategoriaController::class, 'getAllCategorias']);
Route::post('/api/categoria', [CategoriaController::class, 'guardarCategoria']);
Route::get('/api/categoria/{id_categoria}', [CategoriaController::class, 'getCategoria']);
Route::put('/api/categoria/{id_categoria}', [CategoriaController::class, 'updateCategoria']);
Route::delete('/api/categoria/{id_categoria}', [CategoriaController::class, 'deleteCategoria']);
Route::get('/api/producto', [ProductoController::class, 'getAllProductos']);
Route::post('/api/producto', [ProductoController::class, 'guardarProducto']);
Route::get('/api/producto/{id_producto}', [ProductoController::class, 'getProducto']);
Route::put('/api/producto/{id_producto}', [ProductoController::class, 'updateProducto']);
Route::delete('/api/producto/{id_producto}', [ProductoController::class, 'deleteProducto']);



