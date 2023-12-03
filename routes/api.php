<?php

use App\Http\Controllers\AgregarMarcaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminVentasController;
use App\Http\Controllers\CarritoComprasController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\InventarioController;
use App\Models\Producto;
use App\Http\Controllers\EmpresaController as Empresa;
use App\Http\Controllers\SolicitudesController as Solicitudes;
use App\Http\Controllers\ClienteController as Cliente;
use App\Http\Controllers\DireccionClienteController as DireccionCliente;
use App\Http\Controllers\SucursalController as Sucursal;
use App\Http\Controllers\UsuarioController as Usuario;
use App\Http\Controllers\AdminController as Admin;
use App\Http\Controllers\ProvinciaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application.
| These routes are loaded by the RouteServiceProvider and all of them
| will be assigned to the "api" middleware group. Make something great!
|
*/

// Rutas de ventas para el administrador
Route::group(['prefix' => '/api/admin'], function () {
    // Rutas para listar pedidos y cambiar el estado de un pedido
    Route::get('/pedidos', [AdminVentasController::class, 'listarPedidos']);
    Route::put('/pedidos/{pedidoId}/cambiar-estado', [AdminVentasController::class, 'cambiarEstadoPedido']);
    Route::get('/pedidos/{pedidoId}', [AdminVentasController::class, 'obtenerPedidoConPago']);
    Route::post('/pedidos/agregar', [AdminVentasController::class, 'agregarPedido'])
        ->middleware(['auth:sanctum', 'ability:cliente,admin_clientes,admin']);
    // Puedes agregar más rutas según sea necesario
});

// Rutas de ventas para el administrador
Route::group(['prefix' => '/api/admin'], function () {
    // Rutas para listar pedidos y cambiar el estado de un pedido
    Route::get('/formas_pago', [PagoController::class, 'listarFormasPago']);
    Route::get('/pagos', [PagoController::class, 'listarPagos']);
    Route::post('/pagos/{pedidoId}', [PagoController::class, 'registrarPago']);
    Route::put('/pagos/{pagoId}', [PagoController::class, 'actualizarPago']);
    Route::delete('/pagos/{pagoId}', [PagoController::class, 'eliminarPago']);
    // Puedes agregar más rutas según sea necesario
});

// Rutas para la interfaz de cliente
Route::group(['prefix' => '/api'], function () {
    // Nuevas rutas del carrito
    Route::get('/carrito/ver', [CarritoComprasController::class, 'verCarrito']);
    Route::post('/carrito/agregar/{productoId}', [CarritoComprasController::class, 'agregarAlCarrito']);
    Route::delete('/carrito/eliminar/{productoId}', [CarritoComprasController::class, 'eliminarDelCarrito']);
    Route::get('/carrito/pago', [CarritoComprasController::class, 'irAPago']);
    Route::post('/carrito/pagar', [CarritoComprasController::class, 'procesarPedido']);
    Route::get('/carrito/factura/{pedidoId}', [CarritoComprasController::class, 'generarFactura']);
});

// Ruta para obtener todos los productos
Route::get('/productos', [ProductoController::class, 'index']);

// Ruta para obtener detalles de un producto
Route::get('/productos/{id}', [ProductoController::class, 'show']);

// Ruta para crear un nuevo producto
Route::post('/productos', [ProductoController::class, 'store']);
###########################################
########    RUTAS DE USUARIOS   ###########
###########################################
Route::post('/api/login', [Usuario::class, 'login']);

Route::post('/api/logout', [Usuario::class, 'logout'])->middleware(['auth:sanctum']);
###########################################
########    RUTAS DE ADMIN   ###########
###########################################
Route::post('api/admin', [Admin::class, 'crearAdmin']);

Route::put('api/admin/{id}', [Admin::class, 'actualizarAdmin']);

Route::delete('api/admin/{id}', [Admin::class, 'borrarAdmin'])->middleware(['auth:sanctum', 'ability:admin']);
###########################################
########    RUTAS DE EMPRESAS   ###########
###########################################
Route::get('/api/empresas',  [Empresa::class, 'getAllEmpresas'])->middleware(['auth:sanctum', 'ability:admin,admin_clientes']);

Route::get('/api/empresas/{id}',  [Empresa::class, 'getEmpresa']);

Route::post('/api/empresas',  [Empresa::class, 'guardarEmpresa']);

Route::put('/api/empresas/{id}',  [Empresa::class, 'actualizarEmpresa'])->middleware(['auth:sanctum', 'ability:empresa,admin,admin_clientes']);

Route::delete('/api/empresas/{id}',  [Empresa::class, 'eliminarEmpresa'])->middleware(['auth:sanctum', 'ability:empresa,admin,admin_clientes']);
###########################################
########    RUTAS DE SUCURSALES   ###########
###########################################
Route::get('/api/sucursales/{id}', [Sucursal::class, 'getSucursales']);
Route::post('/api/sucursales/{id}', [Sucursal::class, 'guardarSucursal'])->middleware(['auth:sanctum', 'ability:empresa,admin,admin_clientes']);
Route::put('/api/sucursales/{empresa_id}/{direccion_id}', [Sucursal::class, 'actualizarSucursal'])->middleware(['auth:sanctum', 'ability:empresa,admin,admin_clientes']);
Route::delete('/api/sucursales/{empresa_id}/{direccion_id}', [Sucursal::class, 'eliminarSucursal'])->middleware(['auth:sanctum', 'ability:empresa,admin,admin_clientes']);
###########################################
########    RUTAS DE SOLICITUDES   ########
###########################################
Route::get('/api/solicitudes',  [Solicitudes::class, 'getAllSolicitudes'])->middleware(['auth:sanctum', 'ability:admin,admin_clientes']);

Route::put('/api/solicitudes/{id}',  [Solicitudes::class, 'actualizarSolicitud'])->middleware(['auth:sanctum', 'ability:admin,admin_clientes']);

//se rechaza la empresa y se elimina xd
Route::delete('/api/solicitudes/{id}',  [Solicitudes::class, 'rechazarSolicitud'])->middleware(['auth:sanctum', 'ability:admin,admin_clientes']);

###########################################
########    RUTAS DE Clientes    ##########
###########################################

Route::get('/api/clientes',  [Cliente::class, 'getAllClientes'])->middleware(['auth:sanctum', 'ability:empresa,admin,admin_clientes']);

Route::get('/api/clientes/{id}',  [Cliente::class, 'getCliente']);

Route::post('/api/clientes',  [Cliente::class, 'guardarCliente'])->middleware(['auth:sanctum', 'ability:empresa']);

Route::put('/api/clientes/{id}',  [Cliente::class, 'actualizarCliente'])->middleware(['auth:sanctum', 'ability:admin,cliente,empresa,admin_clientes']);

Route::delete('/api/clientes/{id}',  [Cliente::class, 'eliminarCliente'])->middleware(['auth:sanctum', 'ability:cliente,empresa,admin_clientes,admin']);

###########################################
##    RUTAS DE Direccion Clientes    ######
###########################################

Route::get('/api/clientes/{id}/direcciones',  [DireccionCliente::class, 'getClienteDirecciones']);

Route::post('/api/clientes/{id}/direcciones',  [DireccionCliente::class, 'guardarClienteDireccion'])->middleware(['auth:sanctum', 'ability:cliente,admin_clientes,admin']);

Route::put('/api/clientes/{id}/direcciones/{id_direccion}',  [DireccionCliente::class, 'actualizarClienteDireccion'])->middleware(['auth:sanctum', 'ability:cliente,admin_clientes,admin']);

Route::delete('/api/clientes/{id}/direcciones/{id_direccion}',  [DireccionCliente::class, 'eliminarClienteDireccion'])->middleware(['auth:sanctum', 'ability:cliente,admin_clientes,admin']);

Route::get('/api/provincias', [ProvinciaController::class, 'getAllProvincias']);

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

// Ruta para actualizar un producto
Route::put('/productos/{id}', [ProductoController::class, 'update']);

// Ruta para eliminar un producto
Route::delete('/productos/{id}', [ProductoController::class, 'destroy']);

// Ruta para obtener detalles de un producto
Route::get('/producto/detalles/{id}', [ProductoController::class, 'mostrarDetalles']);

// Rutas para el método de pago
Route::group(['prefix' => '/api/payment'], function () {
    // Rutas para el controlador de pagos
    Route::get('/show-page', [PagoController::class, 'showPaymentPage'])->name('payment.page');
    Route::post('/process-payment/process-payment', [PagoController::class, 'processPayment'])->name('process.payment');
    // Otras rutas relacionadas con el método de pago
});
