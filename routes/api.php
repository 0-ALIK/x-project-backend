<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReclamoController as Reclamo;

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
Route::get('/api/reclamo', [Reclamo::class, 'getAllReclamos']);

Route::patch('/api/reclamo/{reclamo_id}/prioridad', [Reclamo::class, 'updatePrioridad']);
Route::patch('/api/reclamo/{reclamo_id}/estado', [Reclamo::class, 'updateEstado']);

Route::get('/api/reclamo/cliente/{cliente_id}', [Reclamo::class, 'getReclamosCliente']);
Route::get('/api/reclamo/{reclamo_id}', [Reclamo::class, 'getReclamoById']);