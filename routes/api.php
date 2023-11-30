<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReclamoController as Reclamo;
use App\Http\Controllers\SugerenciaController as Sugerencia;
use App\Http\Controllers\ChatController as Chat;


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
Route::patch('/api/reclamo/{reclamo_id}/prioridad', [Reclamo::class, 'updatePrioridad']);
Route::patch('/api/reclamo/{reclamo_id}/estado',    [Reclamo::class, 'updateEstado']);

Route::post('/api/sugerencia',   [Sugerencia::class, 'guardarSugerencia']);
Route::get('/api/sugerencia',    [Sugerencia::class, 'getSugerencia']);

Route::get('/api/chat/{reclamo_id}', [Chat::class, 'index']);
Route::post('/api/chat/receive',     [Chat::class, 'receive']);
Route::post('/api/chat/broadcast',   [Chat::class, 'broadcast']);