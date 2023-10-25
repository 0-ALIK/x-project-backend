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
Route::get('/api/reclamo/categorias', [Reclamo::class, 'getAllCategorias']);
Route::get('/api/reclamo/estados', [Reclamo::class, 'getAllEstados']);
Route::get('/api/reclamo/prioridades', [Reclamo::class, 'getAllPrioridades']);
   


