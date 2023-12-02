<?php

namespace App\Http\Controllers;
use App\Models\Cliente;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Reclamo;
use App\Models\Empresa;


class DashboardController extends Controller
{
    public function getAllProductos(Request $request){
        $categoria = $request->input('categoria');
        $marca = $request->input('marca');
        $precio_unit_min = $request->input('precio_unit_min');
        $precio_unit_max = $request->input('precio_unit_max');
        $punto_reorden = $request->input('punto_reorden');

        $query = DB::table('producto')->select('producto.nombre as nombre','marca.nombre as marca','categoria.nombre as categoria', 'producto.precio_unit', DB::raw('(producto.cantidad_por_caja * producto. cantidad_cajas) as Stock'))
        ->join('marca','marca.id_marca','=','producto.marca_id')
        ->join('categoria','categoria.id_categoria','=','producto.categoria_id');

        $categoria ? $query->where('categoria.nombre', '=', $categoria) : null;
        $marca ? $query->where('marca.nombre', '=', $marca) : null;

        $precio_unit_max ? $query->where('producto.precio_unit', '<=', $precio_unit_max) : null;
        $precio_unit_min ? $query->where('producto.precio_unit', '>=', $precio_unit_min ) : null;

        $punto_reorden == "Por debajo" ? $query->where('producto.punto_reorden','>', DB::raw('producto.cantidad_por_caja * producto.cantidad_cajas')): null;
        $punto_reorden == "Por encima" ? $query->where('producto.punto_reorden', '<',DB::raw('producto.cantidad_por_caja * producto.cantidad_cajas')) : null;
        $punto_reorden == "En la raya" ? $query->where('producto.punto_reorden', '=', DB::raw('producto.cantidad_por_caja * producto.cantidad_cajas')) : null;

        $productos = $query->get();

        return $productos;
    }


    public function getAllClientes(Request $request){
        $genero = $request->input('genero');
        $empresa = $request->input('empresa');
        $estado = $request->input('estado');
        $provincia = $request->input('provincia');



        $query = Cliente::select('usuario.nombre as Nombre', 'cliente.apellido as Apellido', 'cliente.cedula as Cédula', 'usuarioEmpresa.nombre as Nombre de Empresa', 'cliente.genero as Género', 'cliente.estado as Estado', 'usuario.nombre as Empresa', 'usuario.correo as Correo', 'usuario.telefono as Teléfono', 'provincia.nombre as Provincia')   
            ->join('usuario', 'cliente.usuario_id', '=', 'usuario.id_usuario')
            ->join('empresa', 'empresa.id_empresa', '=', 'cliente.empresa_id')
            ->join('usuario as usuarioEmpresa', 'usuarioEmpresa.id_usuario', '=', 'empresa.usuario_id')
            ->join('cliente_direcciones', 'cliente_direcciones.cliente_id', '=', 'cliente.id_cliente')
            ->join('direccion', 'direccion.id_direccion', '=', 'cliente_direcciones.direccion_id')
            ->join('provincia', 'provincia.id_provincia', '=', 'direccion.provincia_id');

        // Aplicamos los filtros
        $genero ? $query->where('cliente.genero', '=', $genero) : null;
        $empresa ? $query->where('usuarioEmpresa.nombre', '=', $empresa): null;
        $estado ? $query->where('cliente.estado', '=', $estado): null;
        $provincia ? $query->where('provincia.nombre', '=', $provincia): null;


        $clientes = $query->get();

        return $clientes;        
    }

    public function getAllEmpresas(Request $request){
        //obtenemos los filtros params
        $provincia = $request->input('provincia');
        $estado = $request->input('estado');

        $query = Empresa::select( 'usuario.nombre as nombre', 'empresa.razon_social', 'empresa.ruc', 'usuario.correo', 'usuario.telefono','empresa.estado as Estado', 'empresa.documento', 'usuario.foto', 'provincia.nombre as Provincia')
        ->join('usuario', 'empresa.usuario_id', '=', 'usuario.id_usuario')
        ->join('empresa_direcciones', 'empresa_direcciones.empresa_id', '=', 'empresa.id_empresa')
        ->join('direccion', 'direccion.id_direccion', '=', 'empresa_direcciones.direccion_id')
        ->join('provincia', 'provincia.id_provincia', '=', 'direccion.provincia_id');

        $provincia ? $query->where('provincia.nombre', '=', $provincia) : null;
        $estado ? $query->where('empresa.estado', '=', $estado) : null;

        $empresas = $query->get();
        return $empresas;
    }

    public function getAllPedidos(Request $request){
        $provincia = $request->input('provincia');
        $producto = $request->input('producto');
        $genero = $request->input('genero');
        $cliente = $request->input('cliente');
        $estado = $request->input('estado');

        $query = DB::table('pedido')
            ->select('cliente.apellido as nombre', 'pedido.detalles', 'provincia.nombre as provincia', 'producto.nombre as producto', 'pedido_productos.cantidad')
            ->join('cliente', 'id_cliente', '=', 'pedido.cliente_id')
            ->join('pedido_estado', 'id_pedido_estado', '=', 'pedido.estado_id')   
            ->join('direccion','id_direccion', '=', 'pedido.direccion_id')
            ->join('provincia','provincia.id_provincia','=','direccion.provincia_id')
            ->join('pedido_productos','pedido_id','=','pedido.id_pedido')
            ->join('producto','producto.id_producto','=','pedido_productos.producto_id');

        // Aplicamos los filtros
        $provincia ? $query->where('provincia.nombre', '=', $provincia) : null;

        $genero ? $query->where('cliente.genero', '=', $genero) : null;
        $producto ? $query->where('producto.nombre', 'like', '%' . $producto . '%') : null;
        $cliente ? $query->where('cliente.apellido', 'like', '%' .$cliente.'%' ) : null;
        $genero ? $query->where('cliente.genero','=',$genero ) :null;
        $estado ? $query->where('pedido_estado.nombre', '=',$estado):null;
        $pedidos = $query->get();

        return $pedidos;
    }

   public function getAllTickets(Request $request){
        $estado = $request->input('estado');
        $categoria = $request->input('categoria');
        $usuario = $request->input('usuario');
        $prioridad = $request->input('prioridad');


        $query = Reclamo::select(DB::raw('CONCAT(usuario.nombre, " " ,cliente.apellido) as Usuario'), 'reclamo_categoria.categoria as Categoria del Pedido','reclamo_prioridad.prioridad as Nivel de Prioridad','reclamo_estado.estado as Estado del Reclamo','pedido.detalles as Detalles del Pedido', 'reclamo.descripcion as Descripción del Reclamo', 'reclamo.evidencia as Evidencia', 'reclamo.created_at as Fecha de Reclamo') 
            ->join('cliente', 'cliente.id_cliente', '=', 'reclamo.cliente_id')
            ->join('usuario', 'usuario.id_usuario', '=', 'cliente.usuario_id')
            ->join('pedido', 'reclamo.pedido_id', '=', 'pedido.id_pedido')
            ->join('reclamo_categoria', 'reclamo.categoria_id', '=', 'reclamo_categoria.id_r_categoria')
            ->join('reclamo_prioridad', 'reclamo.prioridad_id', '=', 'reclamo_prioridad.id_r_prioridad')
            ->join('reclamo_estado', 'reclamo.estado_id', '=', 'reclamo_estado.id_r_estado');

        // Aplicamos los filtros
        $estado ? $query->where('reclamo_estado.estado', '=', $estado) : null;
        $categoria ? $query->where('reclamo_categoria.categoria', '=', $categoria) : null;
        $usuario ? $query->where(DB::raw('CONCAT(usuario.nombre, " " ,cliente.apellido)'), '=', $usuario) : null;
        $prioridad ? $query->where('reclamo_prioridad.prioridad', '=', $prioridad) : null;


        $tickets = $query->get();

        return $tickets;
    }

    public function getCategorias(){
        $query = DB::table('categoria') 
        ->select('*') ;    

        $categorias = $query->get();

        return $categorias;
    }

    public function getMarcas(){
        $query = DB::table('marca') 
        ->select('*') ;    

        $categorias = $query->get();

        return $categorias;
    }

    public function getProvincias(){
        $query = DB::table('provincia') 
        ->select('*') ;    

        $categorias = $query->get();

        return $categorias;
    }

    public function getProductos(){
        $query = DB::table('producto') 
        ->select('*') ;    

        $categorias = $query->get();

        return $categorias;
    }

    public function getEstados(){
        $query = DB::table('pedido_estado') 
        ->select('*') ;    

        $categorias = $query->get();

        return $categorias;
    }

    public function getEstadosTickets(){
        $query = DB::table('reclamo_estado') 
        ->select('*') ;    

        $categorias = $query->get();

        return $categorias;
    }

    public function getCategoriasTickets(){
        $query = DB::table('reclamo_categoria') 
        ->select('*') ;    

        $categorias = $query->get();

        return $categorias;
    }

    public function getPrioridadTickets(){
        $query = DB::table('reclamo_prioridad') 
        ->select('*') ;    

        $categorias = $query->get();

        return $categorias;
    }
    
}



   
