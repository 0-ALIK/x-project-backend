<?php

namespace App\Http\Controllers;
use App\Models\Cliente;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Provincia;


class DashboardController extends Controller
{
    public function getAllProductos(Request $request){
        $formato = $request->input('formato');
        $categoria = $request->input('categoria');
        $marca = $request->input('marca');
        $precio_unit_min = $request->input('precio_unit_min');
        $precio_unit_max = $request->input('precio_unit_max');
        $punto_reorden = $request->input('punto_reorden');

        $query = DB::table('producto')->select('producto.nombre as nombre','foto','marca.nombre as marca','categoria.nombre as categoria', 'producto.precio_unit')
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
        $formato = $request->input('formato');
        $provincia = $request->input('provincia');
        $genero = $request->input('genero');
        $nombreEmpresa = $request->input('empresa');
        $nombreProducto = $request->input('producto');

        $query = Cliente::select('cliente.id_cliente as id', 'usuario.nombre', 'cliente.apellido as apellido', 'cliente.cedula', 'cliente.genero', 'usuarioEmpresa.nombre as nombre_empresa', 'usuario.correo as correo_empresa', 'usuario.telefono as telefono_empresa', 'usuario.foto', DB::raw('69 as frecuencia'), DB::raw('96 as totalPedidos'))   
            ->join('usuario', 'cliente.usuario_id', '=', 'usuario.id_usuario')
            ->join('empresa', 'empresa.id_empresa', '=', 'cliente.empresa_id')
            ->join('usuario as usuarioEmpresa', 'usuarioEmpresa.id_usuario', '=', 'empresa.usuario_id');

            /*->join('cliente_direcciones', 'cliente_direcciones.cliente_id', '=', "cliente.id_cliente")
            ->join('direccion', 'direccion.id_direccion', '=', 'cliente_direcciones.direccion_id')
            ->join('provincia', 'direccion.provincia_id', '=' , 'provincia.id_provincia');*/
            

           

            
        // Aplicamos los filtros
        $genero ? $query->where('cliente.genero', '=', $genero) : null;
        $nombreEmpresa ? $query->where('usuarioEmpresa.nombre', 'like', '%' . $nombreEmpresa . '%') : null;

        $provincia ? $query->where('provincia.nombre', '=', $provincia) : null;
        $nombreProducto ? $query->where('producto.nombre', 'like', '%' . $nombreProducto . '%') : null;

        $clientes = $query->get();

        return $clientes;
    }

    public function getAllPedidos(Request $request){
        $formato = $request->input('formato');
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
        $clientes = $query->get();

        return $clientes;
    }
}



   
