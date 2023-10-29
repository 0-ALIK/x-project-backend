<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReclamoController as Reclamo;
use App\Http\Controllers\SugerenciaController as Sugerencia;
use App\Http\Controllers\EmpresaController as Empresa;
use App\Http\Controllers\SolicitudesController as Solicitudes;

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
########    RUTAS DE EMPRESAS   ###########
###########################################
Route::get('/api/empresas',  [Empresa::class, 'getAllEmpresas']);
Route::get('/api/empresas/{id}',  [Empresa::class, 'getEmpresa']);

Route::post('/api/empresas',  [Empresa::class, 'guardarEmpresa']);

Route::put('/api/empresas/{id}',  [Empresa::class, 'actualizarEmpresa']);

Route::delete('/api/empresas/{id}',  [Empresa::class, 'eliminarEmpresa']);

###########################################
########    RUTAS DE SOLICITUDES   ########
###########################################
Route::get('/api/solicitudes',  [Solicitudes::class, 'getAllSolicitudes']);

Route::put('/api/solicitudes/{id}',  [Solicitudes::class, 'actualizarSolicitud']);

//se rechaza la empresa y se elimina xd
Route::delete('/api/solicitudes/{id}',  [Solicitudes::class, 'rechazarSolicitud']);

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






