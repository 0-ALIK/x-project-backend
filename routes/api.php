<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReclamoController as Reclamo;
use App\Http\Controllers\SugerenciaController as Sugerencia;
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
Route::post('/api/sucursales', [Sucursal::class, 'guardarSucursal'])->middleware(['auth:sanctum', 'ability:empresa, admin']);
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






