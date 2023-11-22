<?php

namespace App\Http\Controllers;
use App\Models\Cliente;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Provincia;

class DashboardController extends Controller
{
    public function getAllInventario(Request $request){
        $categoria = $request->input('categoria');
        $marca = $request->input('marca');
        $precio_unit_min = $request->input('precio_unit_min');
        $precio_unit_max = $request->input('precio_unit_max');
        $punto_reorden = $request->input('punto_reorden');

        $query = Cliente::select('cliente.id_cliente as id', 'usuario.nombre', 'cliente.apellido as apellido', 'cliente.cedula', 'cliente.genero', 'usuarioEmpresa.nombre as nombre_empresa', 'usuario.correo as correo_empresa', 'usuario.telefono as telefono_empresa', 'usuario.foto', DB::raw('69 as frecuencia'), DB::raw('96 as totalPedidos'))   
            ->join('usuario', 'cliente.usuario_id', '=', 'usuario.id_usuario')
            ->join('empresa', 'empresa.id_empresa', '=', 'cliente.empresa_id')
            ->join('usuario as usuarioEmpresa', 'usuarioEmpresa.id_usuario', '=', 'empresa.usuario_id');

            /*->join('cliente_direcciones', 'cliente_direcciones.cliente_id', '=', "cliente.id_cliente")
            ->join('direccion', 'direccion.id_direccion', '=', 'cliente_direcciones.direccion_id')
            ->join('provincia', 'direccion.provincia_id', '=' , 'provincia.id_provincia');*/
    
    }


    public function getAllClientes(Request $request){
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
}



   
